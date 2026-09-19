<?php
namespace Parser\ExtraStrats;

/**
 * Copy source files without modification.
 */
class Files_Full extends Files_Copy_Strategy {

	/**
	 * `wp-core => destination` files map to be copied
	 */
	protected array $files_map = [
		'wp-includes/version.php' => 'wp-includes/version.php',
		'wp-includes/html-api/html5-named-character-references.php' => 'init-parts/wp-includes/html5-named-character-references.php',
	];

	public function run(): void {
		if ( version_compare( $this->config->wp_version_line, '6.6', '<' ) ) {
			unset( $this->files_map['wp-includes/html-api/html5-named-character-references.php'] );
		}

		parent::run();
	}

	protected function get_content( string $rel_file ): string {
		return file_get_contents( "{$this->config->wp_core_dir}/$rel_file" );
	}

	protected function get_dest_file( string $rel_file ): string {
		return "$this->wp_line_dir/$rel_file";
	}

	protected function get_log_message( string $rel_file ): string {
		return "Updated extra file: $rel_file";
	}

}
