<?php
namespace Parser\Strategy;

use Parser\Helpers;
use RuntimeException;

class Copy_Static_Methods extends File_Update_Strategy {

	public function get_items(): array {
		$items = [];

		foreach( $this->config->static_methods_data as $rel_file => $config ){
			$class_name = $config['class'] ?? '';
			$method_names = $config['methods'] ?? [];

			if( ! $class_name || ! $method_names ){
				throw new RuntimeException( "WARNING: Invalid static-method config for `$rel_file`. Expected keys: class, methods." );
			}

			$items[] = [
				'rel_file' => $rel_file,
				'class_name' => $class_name,
				'method_names' => $method_names,
			];
		}

		return $items;
	}

	public function get_dest_file( array $item ): string {
		return "{$this->config->dest_dir}/classes-statics/{$item['class_name']}.php";
	}

	public function generate_content( array $item ): string {
		$rel_file = $item['rel_file'];
		$class_name = $item['class_name'];
		$method_names = $item['method_names'];

		$core_file_content = file_get_contents( "{$this->config->wp_core_dir}/$rel_file" );
		$methods_data = Helpers::get_class_func_code_from_php_code( $core_file_content, [ 'type' => 'method' ] );
		$methods_data = array_intersect_key( $methods_data, $method_names );
		$not_found_methods = array_diff_key( $method_names, $methods_data );

		if( $not_found_methods ){
			throw new RuntimeException( "WARNING: Not found static methods:\n\t" . implode( "\n\t", array_keys( $not_found_methods ) ) . "\n" );
		}

		$append = '';
		foreach( $methods_data as $method_name => $code_lines ){
			$comment = $this->get_file_comment( $rel_file );
			$func_name = "{$class_name}__$method_name";
			$method_code = implode( "\n\t", $code_lines );
			$func_code = $this->rename_method_name( $method_code, $method_name, $func_name );

			$append .= <<<CODE
				$comment
				if( ! function_exists( '$func_name' ) ) :
					$func_code
				endif;
				CODE . "\n\n";
		}

		return $append;
	}

	public function get_log_message( array $item ): string {
		return "Updated static methods: {$item['rel_file']}";
	}

	/**
	 * Static class method replacement: `Class::method -> Class__method()`
	 */
	private function rename_method_name( string $method_code, string $method_name, string $func_name ): string {
		$method_code = trim( $method_code );
		$lines = explode( "\n", $method_code );

		$line = & $lines[0];
		$line = preg_replace( '~\b(?:final|abstract|public|protected|private|static)\s+~', '', $line );
		$line = str_replace( " $method_name(", " $func_name(", $line );

		return implode( "\n", $lines );
	}

}
