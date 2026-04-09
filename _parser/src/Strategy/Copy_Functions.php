<?php
namespace Parser\Strategy;

use Parser\Helpers;
use RuntimeException;

class Copy_Functions extends File_Update_Strategy {

	public function get_items(): array {
		$items = [];

		foreach( $this->config->funcs_data as $rel_file => $func_names ){
			$items[] = [
				'rel_file' => $rel_file,
				'func_names' => $func_names,
			];
		}

		return $items;
	}

	public function get_dest_file( array $item ): string {
		return "{$this->config->dest_dir}/functions/{$item['rel_file']}";
	}

	public function generate_content( array $item ): string {
		$rel_file = $item['rel_file'];
		$func_names = $item['func_names'];

		$core_file_content = file_get_contents( "{$this->config->wp_core_dir}/$rel_file" );
		$funcs_data = Helpers::get_class_func_code_from_php_code( $core_file_content, [ 'type' => 'func' ] );
		$funcs_data = array_intersect_key( $funcs_data, $func_names );
		$not_found_funcs = array_diff_key( $func_names, $funcs_data );

		if( $not_found_funcs ){
			throw new RuntimeException( "WARNING: Not found funcs in `$rel_file`:\n\t" . implode( "\n\t", array_keys( $not_found_funcs ) ) . "\n" );
		}

		$append = '';
		foreach( $funcs_data as $func_name => $code_lines ){
			$comment = $this->get_file_comment( $rel_file );
			$func_code = implode( "\n\t", $code_lines );
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
		return "Updated functions: {$item['rel_file']}";
	}

}
