<?php
namespace Parser\Strategy;

use Parser\Config;

abstract class File_Update_Strategy {

	public function __construct(
		protected readonly Config $config,
		protected readonly string $wp_version,
	){
	}

	protected function get_file_comment( string $rel_file ): string {
		return "// $rel_file (WP $this->wp_version)";
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	abstract public function get_items(): array;

	abstract public function get_dest_file( array $item ): string;

	abstract public function generate_content( array $item ): string;

	abstract public function get_log_message( array $item ): string;
}
