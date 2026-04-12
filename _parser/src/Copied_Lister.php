<?php

namespace Parser;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Copied_Lister {

	/**
	 * List of generated function/class names.
	 * Used to store the list of copied functions.
	 */
	public array $names = [];

	private string $doc_file_name = 'SYMBOLS-INFO.md';

	private string $content = <<<MD
		The following functions and classes are available in this (unit test) environment. Symbols are copied from WordPress {WP_VERSION}.
		
		Mock-friendly functions (override their behavior in tests when needed):
		{MOCKS_LIST}
		
		Functions and classes available as-is:
		{COPIED_LIST}
		MD;


	public function __construct(
		private readonly Config $config,
	) {
	}

	public function generate_list(): void {
		$mock_names = $this->get_mock_function_names();
		$mock_names = array_values( array_unique( $mock_names ) );
		asort( $mock_names );

		$copied_names = array_values( array_unique( $this->names ) );
		$copied_names = array_values( array_diff( $copied_names, $mock_names ) );
		asort( $copied_names );

		$this->content = strtr( $this->content, [
			'{WP_VERSION}' => $this->config->wp_version,
			'{MOCKS_LIST}' => $mock_names ? implode( "\n", $mock_names ) : '(none)',
			'{COPIED_LIST}' => $copied_names ? implode( "\n", $copied_names ) : '(none)',
		] );

		file_put_contents( "{$this->config->dest_dir}/$this->doc_file_name", $this->content );
	}

	private function get_mock_function_names(): array {
		$mocks_dir = "{$this->config->dest_dir}/mocks";
		$mock_names = [];

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $mocks_dir, FilesystemIterator::SKIP_DOTS ),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach( $iterator as $file_info ){
			if( ! $file_info->isFile() || $file_info->getExtension() !== 'php' ){
				continue;
			}

			$file_content = file_get_contents( $file_info->getPathname() );
			$func_names = array_keys( Helpers::get_class_func_code_from_php_code( $file_content, [ 'type' => 'func' ] ) );

			foreach( $func_names as $func_name ){
				$mock_names[] = "$func_name()";
			}
		}

		return $mock_names;
	}

}
