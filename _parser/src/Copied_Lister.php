<?php

namespace Parser;

class Copied_Lister {

	/**
	 * List of generated function/class names.
	 * Used to store the list of copied functions.
	 */
	public array $names = [];

	private string $doc_file_name = 'supported-symbols.md';

	private string $content = <<<MD
		The following functions and classes are available in the unit test environment. They are copied directly from WordPress {WP_VERSION} source and work as-is, so no mocks are required.
		
		{LIST}
		MD;


	public function __construct(
		private readonly Config $config,
	) {
	}

	public function generate_list(): void {
		asort( $this->names );
		$this->content = strtr( $this->content, [
			'{WP_VERSION}' => $this->config->wp_version,
			'{LIST}'       => implode( "\n", $this->names ),
		] );

		file_put_contents( "{$this->config->dest_dir}/$this->doc_file_name", $this->content );
	}

}
