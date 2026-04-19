<?php
namespace Parser\Strategy;

use Parser\Helpers;

class Copy_Classes extends File_Update_Strategy {

	public function get_items(): array {
		$items = [];

		foreach( $this->config->classes_data as $rel_file => $data ){
			foreach( $data as $class_name => $info ){
				$meta = $this->parse_class_info( $info );
				if( ! $this->is_supported_for_current_wp( $meta['since'] ) ){
					continue;
				}

				$items[] = [
					'rel_file' => $rel_file,
					'class_name' => $class_name,
				];
			}
		}

		return $items;
	}

	/**
	 * Config value format: '6.8.0'
	 *
	 * @return array{
	 *     since:string,
	 * }
	 */
	private function parse_class_info( string $info ): array {
		if( ! trim( $info ) ){
			return [
				'since' => '0.0.0',
			];
		}

		$tokens = preg_split( '~\s+~', $info );
		$tokens = array_values( array_filter( $tokens ) );

		return [
			'since' => $tokens[0] ?: '0.0.0',
		];
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
		$class_code = $this->remove_final_class_modifier( $class_code );

		$this->lister->names[] = "$class_name{}";

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

	/**
	 * INFO: "final" modifier prohibits class inheritance.
	 * So remove it to allow to create classes mocks.
	 */
	private function remove_final_class_modifier( string $class_code ): string {
		$updated = preg_replace_callback(
			'~(^|\n)([ \t]*)final([ \t]+(?:readonly[ \t]+)?class\b)~',
			static function ( array $matches ): string {
				// Keep indentation, drop only `final` and normalize declaration spacing.
				return $matches[1] . $matches[2] . ltrim( $matches[3] );
			},
			$class_code,
			1
		);

		return is_string( $updated ) ? $updated : $class_code;
	}

}
