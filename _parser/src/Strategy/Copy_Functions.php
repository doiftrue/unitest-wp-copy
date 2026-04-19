<?php
namespace Parser\Strategy;

use Parser\Helpers;
use RuntimeException;

class Copy_Functions extends File_Update_Strategy {

	public function get_items(): array {
		$items = [];

		foreach( $this->config->funcs_data as $rel_file => $funcs_data ){
			foreach( $this->split_target_func_names( $funcs_data ) as $target => $func_names ){
				$items[] = [
					'target'     => $target,
					'rel_file'   => $rel_file,
					'func_names' => $func_names,
				];
			}
		}

		return $items;
	}

	private function split_target_func_names( array $funcs_data ): array {
		$split = [];

		foreach( $funcs_data as $func_name => $info ){
			$meta = $this->parse_func_info( $info );
			if( ! $this->is_supported_for_current_wp( $meta['since'] ) ){
				continue;
			}

			$target = $meta['mockable'] ? 'mockable' : 'regular';
			$split[ $target ][ $func_name ] = '';
		}

		return $split;
	}

	/**
	 * Config value format: '6.8.0'  OR  'mockable' OR  '6.8.0 mockable'
	 *
	 * @return array{
	 *     since:string,
	 *     mockable:bool
	 * }
	 */
	private function parse_func_info( string $info ): array {
		if( ! trim( $info ) ){
			return [
				'since' => '0.0.0',
				'mockable' => false,
			];
		}

		$tokens = preg_split( '~\s+~', $info );
		$tokens = array_values( array_filter( $tokens ) );

		return [
			'since' => $tokens[0] ?: '0.0.0',
			'mockable' => (bool) ( $tokens[1] ?? '' ),
		];
	}

	public function get_dest_file( array $item ): string {
		return match ( $item['target'] ) {
			'regular'  => "{$this->config->dest_dir}/functions/{$item['rel_file']}",
			'mockable' => "{$this->config->dest_dir}/mockable/{$item['rel_file']}",
		};
	}

	public function generate_content( array $item ): string {
		$rel_file = $item['rel_file'];
		$func_names = $item['func_names'];

		$core_file_content = file_get_contents( "{$this->config->wp_core_dir}/$rel_file" );
		$all_funcs_data = Helpers::get_class_func_code_from_php_code( $core_file_content, [ 'type' => 'func' ] );

		$funcs_data = [];
		$not_found_funcs = [];

		foreach( array_keys( $func_names ) as $func_name ){
			$code_lines = $all_funcs_data[ $func_name ] ?? null;
			if( ! is_array( $code_lines ) || ! $code_lines ){
				$not_found_funcs[ $func_name ] = '';
				continue;
			}

			$funcs_data[ $func_name ] = $code_lines;
		}

		if( $not_found_funcs ){
			throw new RuntimeException( "WARNING: Not found funcs in `$rel_file`:\n\t" . implode( "\n\t", array_keys( $not_found_funcs ) ) . "\n" );
		}

		$append = '';
		foreach( $funcs_data as $func_name => $code_lines ){
			$comment = $this->get_file_comment( $rel_file );
			$code_lines = array_values( $code_lines );

			if( 'mockable' === $item['target'] ){
				$code_lines = $this->inject_mock_handler( $code_lines );
			}

			$func_code = implode( "\n\t", $code_lines );
			$append .= <<<CODE
				$comment
				if( ! function_exists( '$func_name' ) ) :
					$func_code
				endif;
				CODE . "\n\n";

			$this->lister->names[] = "$func_name()";
		}

		return $append;
	}

	public function get_log_message( array $item ): string {
		return match ( $item['target'] ) {
			'regular'  => "Updated functions: {$item['rel_file']}",
			'mockable' => "Updated mockable functions: {$item['rel_file']}",
		};
	}

	private function inject_mock_handler( array $code_lines ): array {
		$open_brace_line_num = null;

		foreach( $code_lines as $line_num => $line ){
			if( str_contains( $line, '{' ) ){
				$open_brace_line_num = $line_num;
				break;
			}
		}

		if( null === $open_brace_line_num ){
			throw new RuntimeException( 'Cannot inject WP_Mock handler: function body start `{` not found.' );
		}

		preg_match( '/^(\s*)/', $code_lines[ $open_brace_line_num ], $m );
		$indent = ( $m[1] ?? '' ) . "\t";

		$handler_lines = [
			"{$indent}if ( \\Unitest_WP_Copy\\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {",
			"{$indent}\treturn \\Unitest_WP_Copy\\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );",
			"{$indent}}",
			'',
		];

		array_splice( $code_lines, $open_brace_line_num + 1, 0, $handler_lines );

		return $code_lines;
	}

}
