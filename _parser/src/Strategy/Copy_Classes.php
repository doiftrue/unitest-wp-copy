<?php
namespace Parser\Strategy;

use Parser\Helpers;

class Copy_Classes extends File_Update_Strategy {

	public function get_items(): array {
		$items = [];

		foreach( $this->config->classes_data as $rel_file => $class_name ){
			$items[] = [
				'rel_file' => $rel_file,
				'class_name' => $class_name,
			];
		}

		return $items;
	}

	public function get_dest_file( array $item ): string {
		return "{$this->config->dest_dir}/classes/{$item['class_name']}.php";
	}

	public function generate_content( array $item ): string {
		$rel_file = $item['rel_file'];
		$class_name = $item['class_name'];

		$file_content = file_get_contents( "{$this->config->wp_core_dir}/$rel_file" );
		$code_lines = Helpers::get_class_func_code_from_php_code( $file_content, [ 'type' => 'class', 'name' => $class_name ] );
		$comment = $this->get_file_comment( $rel_file );
		$class_code = implode( "\n\t", $code_lines );

		return <<<CODE
			$comment
			if( ! class_exists( '$class_name' ) ) :
				$class_code
			endif;
			CODE . "\n\n";
	}

	public function get_log_message( array $item ): string {
		return "Updated classes: {$item['rel_file']}";
	}

}
