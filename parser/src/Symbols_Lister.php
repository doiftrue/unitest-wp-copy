<?php

namespace Parser;

class Symbols_Lister {
	/**
	 * Infrastructure mocks intentionally omitted from the public symbol list.
	 * Their presence alone must not make arbitrary option dependencies eligible.
	 */
	private const array SKIP_SYMBOL_NAMES = [
		'get_option()',
		'get_site_option()',
	];

	/**
	 * List of generated function/class names.
	 * Used to store the list of copied functions.
	 */
	public array $names = [];

	private string $doc_file_name = 'SYMBOLS-INFO.md';

	private string $content = <<<MD
		The following functions and classes are available in this (unit test) environment. Symbols are copied from WordPress {WP_VERSION}.
		
		Runtime-adapted classes (NOT mockable via WP_Mock).
		Partially copied WordPress classes provided by the runtime. Use them directly or extend them to build your own mock.
		Method marks: `[wp]` — unchanged copied WordPress method, `[adapted]` — runtime-specific implementation.
		```text
		{CLASSES_LIST}
		```
		
		Custom-adapted WordPress symbols (Mockable via WP_Mock):
		```text
		{MOCKS_LIST}
		```
		
		Copied WP symbols (Mockable via WP_Mock):
		```text
		{MOCKABLE_LIST}
		```
		
		Copied WP symbols (not mockable):
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
		$mockable_names = array_values( array_diff( $mockable_names, self::SKIP_SYMBOL_NAMES ) );
		asort( $mockable_names );

		$mocks_names = $this->get_mock_function_names( "$config->runtime_dir/custom-mocks" );
		$mocks_names = array_values( array_unique( $mocks_names ) );
		$mocks_names = array_values( array_diff( $mocks_names, self::SKIP_SYMBOL_NAMES ) );
		asort( $mocks_names );

		$excluded_names = array_values( array_unique( array_merge( $mockable_names, $mocks_names ) ) );

		$copied_names = array_values( array_unique( $this->names ) );
		$copied_names = array_values( array_diff( $copied_names, $excluded_names, self::SKIP_SYMBOL_NAMES ) );
		asort( $copied_names );

		$this->content = strtr( $this->content, [
			'{WP_VERSION}'    => $config->wp_version,
			'{MOCKABLE_LIST}' => $mockable_names ? implode( "\n", $mockable_names ) : '(none)',
			'{MOCKS_LIST}'    => $mocks_names ? implode( "\n", $mocks_names ) : '(none)',
			'{COPIED_LIST}'   => $copied_names ? implode( "\n", $copied_names ) : '(none)',
			'{CLASSES_LIST}'  => new Runtime_Classes_Doc_Builder( "$config->runtime_dir/custom-mocks", "$config->copy_dir/traits" )->build(),
		] );

		$project_dir = dirname( $config->runtime_dir );
		file_put_contents( "$project_dir/$this->doc_file_name", "$this->content\n" );
	}

	private function get_mock_function_names( string $mocks_dir ): array {
		$names = [];

		foreach( Helpers::find_php_files( $mocks_dir ) as $file_path ){
			$file_content = file_get_contents( $file_path );
			$func_names = array_keys( Helpers::get_class_func_code_from_php_code( $file_content, [ 'type' => 'func' ] ) );

			foreach( $func_names as $func_name ){
				$names[] = "$func_name()";
			}
		}

		return $names;
	}

}
