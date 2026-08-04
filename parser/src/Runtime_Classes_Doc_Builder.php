<?php

namespace Parser;

/**
 * Builds the `SYMBOLS-INFO.md` section describing runtime-adapted classes.
 *
 * Such classes live in manual mocks and are not WP_Mock-mockable symbols: they are
 * partially copied WordPress classes a test can use directly or extend for its own mock.
 * A method is marked `wp` when it comes from a generated trait of copied WordPress
 * methods, and `adapted` when the manual class declares it itself.
 */
class Runtime_Classes_Doc_Builder {

	private const string ORIGIN_WP = 'wp';

	private const string ORIGIN_ADAPTED = 'adapted';

	/**
	 * @param string $mocks_dir  Directory of manual mocks, @see wp-runtime/custom-mocks
	 * @param string $traits_dir Directory of generated traits, @see wp-runtime/copy/traits
	 */
	public function __construct(
		private readonly string $mocks_dir,
		private readonly string $traits_dir,
	) {
	}

	/** Builds the doc block for all found classes, or `(none)` when there are none. */
	public function build(): string {
		$blocks = [];

		foreach( Helpers::find_php_files( $this->mocks_dir ) as $file_path ){
			$file_content = file_get_contents( $file_path );

			if( ! preg_match( '~^namespace[ \t]+([\w\\\\]+)[ \t]*;~m', $file_content, $ns_match ) ){
				continue;
			}

			$lines = $file_content;
			$classes = Helpers::get_class_func_code_from_php_code( $lines, [ 'type' => 'class' ] );

			foreach( $classes as $class_name => $class_lines ){
				$full_name = "\\$ns_match[1]\\$class_name";
				$blocks[ $full_name ] = $this->build_class( $full_name, $class_lines, $file_content );
			}
		}

		if( ! $blocks ){
			return '(none)';
		}

		ksort( $blocks );

		return implode( "\n\n", $blocks );
	}

	/** @param array<int,string> $class_lines Class code lines, keyed by source line number. */
	private function build_class( string $full_name, array $class_lines, string $file_content ): string {
		$block = [ $full_name ];

		if( $summary = $this->get_summary( $class_lines, $file_content ) ){
			$block[] = "    $summary";
		}

		$block[] = '    Methods:';

		foreach( $this->collect_methods( $class_lines ) as $method_name => $origin ){
			$block[] = sprintf( '        %-28s [%s]', "$method_name()", $origin );
		}

		if( $properties = $this->get_public_property_names( $class_lines ) ){
			$block[] = '    Public properties:';
			$block[] = '        $' . implode( ', $', $properties );
		}

		return implode( "\n", $block );
	}

	/**
	 * @param array<int,string> $class_lines
	 *
	 * @return array<string,string> Method name => origin mark.
	 */
	private function collect_methods( array $class_lines ): array {
		$methods = [];

		foreach( $this->get_public_method_names( $class_lines ) as $method_name ){
			$methods[ $method_name ] = self::ORIGIN_ADAPTED;
		}

		foreach( $this->get_used_trait_names( $class_lines ) as $trait_name ){
			$trait_file = "$this->traits_dir/$trait_name.php";

			if( ! is_file( $trait_file ) ){
				continue;
			}

			$trait_lines = $this->split_lines( file_get_contents( $trait_file ) );

			foreach( $this->get_public_method_names( $trait_lines ) as $method_name ){
				$methods[ $method_name ] ??= self::ORIGIN_WP;
			}
		}

		ksort( $methods );

		return $methods;
	}

	/** Takes the class docblock summary, falling back to the file docblock summary. */
	private function get_summary( array $class_lines, string $file_content ): string {
		$file_lines = $this->split_lines( $file_content );
		$class_index = (int) array_key_first( $class_lines ) - 1;

		foreach( $this->get_summary_doc_starts( $file_lines, $class_index ) as $doc_start ){
			for( $index = $doc_start + 1; $index < count( $file_lines ); $index++ ){
				$line = trim( $file_lines[ $index ] );

				if( str_starts_with( $line, '*/' ) ){
					break;
				}

				$summary = trim( ltrim( $line, '*' ) );

				if( str_starts_with( $summary, '@' ) ){
					break;
				}
				if( '' !== $summary ){
					return $summary;
				}
			}
		}

		return '';
	}

	/**
	 * @param string[] $file_lines
	 *
	 * @return int[] Line indexes of the docblock closest above the class, then of the first file docblock.
	 */
	private function get_summary_doc_starts( array $file_lines, int $class_index ): array {
		$doc_starts = [];

		foreach( $file_lines as $index => $line ){
			if( '/**' === trim( $line ) ){
				$doc_starts[] = $index;
			}
		}

		$preceding_doc = null;

		foreach( $doc_starts as $doc_start ){
			if( $doc_start < $class_index ){
				$preceding_doc = $doc_start;
			}
		}

		return array_unique( array_filter(
			[ $preceding_doc, $doc_starts[0] ?? null ],
			static fn( $value ) => null !== $value
		) );
	}

	/** @param array<int|string,string> $code_lines */
	private function get_public_method_names( array $code_lines ): array {
		$names = [];

		foreach( $code_lines as $line ){
			if( preg_match( '~^[\t ]*(?:final[ ]+)?(?:public[ ]+)?(?:static[ ]+)?function[ &]+(\w+)[ ]*\(~', $line, $m ) ){
				$names[] = $m[1];
			}
		}

		return $names;
	}

	/** @param array<int|string,string> $code_lines */
	private function get_public_property_names( array $code_lines ): array {
		$names = [];

		foreach( $code_lines as $line ){
			if( preg_match( '~^[\t ]*public[ ]+(?:readonly[ ]+)?(?:[\w\\\\|?]+[ ]+)?\$(\w+)~', $line, $m ) ){
				$names[] = $m[1];
			}
		}

		return $names;
	}

	/** @param array<int|string,string> $code_lines */
	private function get_used_trait_names( array $code_lines ): array {
		$names = [];

		foreach( $code_lines as $line ){
			if( preg_match( '~^[\t ]*use[ ]+([\w\\\\, ]+);~', $line, $m ) ){
				foreach( explode( ',', $m[1] ) as $trait_name ){
					$names[] = ltrim( trim( $trait_name ), '\\' );
				}
			}
		}

		return $names;
	}

	/** @return string[] */
	private function split_lines( string $content ): array {
		return explode( "\n", str_replace( "\r", '', $content ) );
	}

}
