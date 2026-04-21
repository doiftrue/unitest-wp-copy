<?php

namespace Parser;

use RuntimeException;

/**
 * This class is responsible for copying extra files from `wp-core` to `wp-line-extra`.
 */
class Extra_Copier {

	/**
	 * Source file path in wp-core => destination file path in wp-runtime/wp-line-extra/<wp-line>.
	 */
	private array $files_map = [
		'wp-includes/version.php' => 'wp-includes/version.php',
	];

	private string $wp_line_extra_dir;

	public function __construct(
		private readonly Config $config,
	){
		$this->wp_line_extra_dir = sprintf(
			'%s/wp-line-extra/%s',
			$this->config->runtime_dir,
			$this->config->wp_version_line
		);
	}

	public function run(): void {
		foreach( $this->files_map as $src_file => $dest_file ){
			$this->copy_file( $src_file, $dest_file );
			echo "Updated extra file: $dest_file\n";
		}
	}

	private function copy_file( string $src_rel_file, string $dest_rel_file ): void {
		$src_file = "{$this->config->wp_core_dir}/$src_rel_file";
		if( ! is_file( $src_file ) ){
			throw new RuntimeException( "WARNING: Not found extra file `$src_rel_file` in wp-core." );
		}

		$dest_file = "$this->wp_line_extra_dir/$dest_rel_file";

		$dest_dir = dirname( $dest_file );
		is_dir( $dest_dir ) || mkdir( $dest_dir, 0777, true );

		copy( $src_file, $dest_file );
	}
}
