<?php

namespace Parser;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Copied_Lister {

	/**
	 * List of generated function/class names.
	 * Used to store the list of copied functions.
	 *
	 * Example:
	 *  [  ]
	 */
	public array $names = [];

	private string $doc_file_name = 'SYMBOLS-INFO.md';

	private string $content = <<<MD
		The following functions and classes are available in this (unit test) environment. Symbols are copied from WordPress {WP_VERSION}.
		
		Changed (not full copies) symbols (Mockable - can be overridden via WP_Mock):
		```text
		{MOCKS_LIST}
		```
		
		Copied symbols (Mockable - can be overridden via WP_Mock):
		```text
		{MOCKABLE_LIST}
		```
		
		Full copies symbols of WP Core (not mockable):
		```text
		{COPIED_LIST}
		```
		MD;


	public function __construct(
		private readonly Config $config,
	) {
	}

	public function generate_list(): void {
		$config = $this->config;

		$mockable_names = $this->get_mock_function_names( "$config->copy_dir/mockable" );
		$mockable_names = array_values( array_unique( $mockable_names ) );
		asort( $mockable_names );

		$mocks_names = $this->get_mock_function_names( "$config->runtime_dir/mocks" );
		$mocks_names = array_values( array_unique( $mocks_names ) );
		asort( $mocks_names );

		$excluded_names = array_values( array_unique( array_merge( $mockable_names, $mocks_names ) ) );

		$copied_names = array_values( array_unique( $this->names ) );
		$copied_names = array_values( array_diff( $copied_names, $excluded_names ) );
		asort( $copied_names );

		$this->content = strtr( $this->content, [
			'{WP_VERSION}'    => $config->wp_version,
			'{MOCKABLE_LIST}' => $mockable_names ? implode( "\n", $mockable_names ) : '(none)',
			'{MOCKS_LIST}'    => $mocks_names ? implode( "\n", $mocks_names ) : '(none)',
			'{COPIED_LIST}'   => $copied_names ? implode( "\n", $copied_names ) : '(none)',
		] );

		file_put_contents( "$config->runtime_dir/$this->doc_file_name", $this->content );
	}

	private function get_mock_function_names( string $mocks_dir ): array {
		if( ! is_dir( $mocks_dir ) ){
			return [];
		}

		$names = [];

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $mocks_dir, FilesystemIterator::SKIP_DOTS ),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach( $iterator as $file_info ){
			if( ! $file_info->isFile() || $file_info->getExtension() !== 'php' ){
				continue;
			}

			// INFO: Skip WP-Line specific mocks ovverlaps. Eg: `wp-6.5.php`
			if( preg_match( '~^wp-\d+\.\d+\.php$~', $file_info->getBasename() ) ){
				continue;
			}

			$file_content = file_get_contents( $file_info->getPathname() );
			$func_names = array_keys( Helpers::get_class_func_code_from_php_code( $file_content, [ 'type' => 'func' ] ) );

			foreach( $func_names as $func_name ){
				$names[] = "$func_name()";
			}
		}

		return $names;
	}

}
