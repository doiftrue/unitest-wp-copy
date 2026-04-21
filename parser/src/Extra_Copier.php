<?php

namespace Parser;

use RuntimeException;

/**
 * This class is responsible for copying extra files from `wp-core` to runtime extra destinations.
 */
class Extra_Copier {

	use Extra_Copier__Copy_Full_File;
	use Extra_Copier__Copy_File_Init_Head;

	private string $wp_line_extra_dir;
	private string $init_parts_dir;

	public function __construct(
		private readonly Config $config,
	){
		$this->wp_line_extra_dir = sprintf( '%s/wp-line-extra/%s', $this->config->runtime_dir, $this->config->wp_version_line );
		$this->init_parts_dir = "$this->wp_line_extra_dir/init-parts";
	}

	public function run(): void {
		$this->run_full_files_copy();
		$this->run_init_head_copy();
	}

	private function write_file( string $file_path, string $content ): void {
		$dest_dir = dirname( $file_path );
		is_dir( $dest_dir ) || mkdir( $dest_dir, 0777, true );

		file_put_contents( $file_path, $content );
	}

	private function read_wp_core_file( string $rel_path ): string {
		$src_file = "{$this->config->wp_core_dir}/$rel_path";
		if( ! is_file( $src_file ) ){
			throw new RuntimeException( "WARNING: Not found extra file `$rel_path` in wp-core." );
		}

		$content = file_get_contents( $src_file );
		if( false === $content ){
			throw new RuntimeException( "WARNING: Failed to read extra file `$rel_path`." );
		}

		return $content;
	}

}

trait Extra_Copier__Copy_Full_File {

	/**
	 * Source file path in wp-core => destination file path in wp-runtime/wp-line-extra/<wp-line>.
	 */
	private array $line_extra_files_map = [
		'wp-includes/version.php' => 'wp-includes/version.php',
	];

	private function run_full_files_copy(): void {
		foreach( $this->line_extra_files_map as $src_file => $dest_file ){
			$this->copy_full_file( $src_file, "$this->wp_line_extra_dir/$dest_file" );
			echo "Updated extra file: $dest_file\n";
		}
	}

	private function copy_full_file( string $src_rel_file, string $dest_file ): void {
		$this->write_file( $dest_file, $this->read_wp_core_file( $src_rel_file ) );
	}
}

/**
 * Copies head part of the file (before first function code)
 * to `init-parts` dir, which is used for runtime initialization.
 */
trait Extra_Copier__Copy_File_Init_Head {

	private array $init_parts_files_map = [
		'wp-includes/kses.php' => 'wp-includes/kses.php',
	];

	private function run_init_head_copy(): void {
		foreach( $this->init_parts_files_map as $src_file => $dest_file ){
			$this->copy_init_head( $src_file, "$this->init_parts_dir/$dest_file" );
			echo "Updated extra init-part file: $dest_file\n";
		}
	}

	private function copy_init_head( string $src_rel_file, string $dest_file ): void {
		$source_code = $this->read_wp_core_file( $src_rel_file );
		preg_match( '/^function\s+\w+\s*\(/m', $source_code, $m, PREG_OFFSET_CAPTURE );
		if( ! $m ){
			throw new RuntimeException( "WARNING: Not found first function in extra file `$src_rel_file`." );
		}

		$header_code = rtrim( substr( $source_code, 0, $m[0][1] ) ) . "\n";
		$this->write_file( $dest_file, $header_code );
	}

}

