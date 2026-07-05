<?php
namespace Parser\BaseStrats;

use Parser\Helpers;
use RuntimeException;

class Instance_Methods_Trait_Copier extends Symbols_Copy_Strategy {

	public function get_items(): array {
		$items = [];

		foreach( $this->config->instance_methods_data as $rel_file => $config ){
			$class_name = $config['class'] ?? '';
			$trait_name = $config['trait'] ?? '';
			$method_names = $config['methods'] ?? [];

			if( ! $class_name || ! $trait_name || ! $method_names ){
				throw new RuntimeException(
					"WARNING: Invalid instance-method config for `$rel_file`. Expected keys: class, trait, methods."
				);
			}

			$items[] = [
				'rel_file'     => $rel_file,
				'class_name'   => $class_name,
				'trait_name'   => $trait_name,
				'method_names' => $this->filter_supported_methods( $method_names ),
			];
		}

		return $items;
	}

	public function get_dest_file( array $item ): string {
		return "{$this->config->copy_dir}/traits/{$item['trait_name']}.php";
	}

	public function generate_content( array $item ): string {
		$rel_file = $item['rel_file'];
		$trait_name = $item['trait_name'];
		$method_names = $item['method_names'];

		$core_file_content = file_get_contents( "{$this->config->wp_core_dir}/$rel_file" );
		$class_code_lines = Helpers::get_class_func_code_from_php_code( $core_file_content, [
			'type' => 'class',
			'name' => $item['class_name'],
		] );

		if( ! $class_code_lines ){
			throw new RuntimeException( "WARNING: Not found source class: {$item['class_name']} in `$rel_file`." );
		}

		$class_code = implode( "\n", $class_code_lines );
		$methods_data = Helpers::get_class_func_code_from_php_code( $class_code, [ 'type' => 'method' ] );
		$methods_data = array_intersect_key( $methods_data, $method_names );
		$not_found_methods = array_diff_key( $method_names, $methods_data );

		if( $not_found_methods ){
			throw new RuntimeException(
				"WARNING: Not found instance methods in {$item['class_name']}:\n\t"
				. implode( "\n\t", array_keys( $not_found_methods ) ) . "\n"
			);
		}

		$methods_code = [];
		foreach( $methods_data as $code_lines ){
			$methods_code[] = implode( "\n", $code_lines );
		}

		$comment = $this->get_file_comment( $rel_file );

		$methods_code = "\n" . implode( "\n\n", $methods_code ) . "\n";

		return <<<CODE
			namespace Unitest_WP_Copy;

			$comment
			trait $trait_name {
			$methods_code
			}
			CODE . "\n";
	}

	public function get_log_message( array $item ): string {
		return "Updated instance-method trait: {$item['rel_file']}";
	}

	private function filter_supported_methods( array $method_names ): array {
		return array_filter(
			$method_names,
			fn( string $since ): bool => $this->is_supported_for_current_wp( $since ?: '0.0.0' )
		);
	}

}
