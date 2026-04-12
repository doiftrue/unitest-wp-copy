<?php
namespace Parser;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Config {

	public readonly string $dest_dir;

	public readonly string $wp_core_dir;

	/** @see _parser/config/functions/**.php */
	public readonly array $funcs_data;

	/** @see _parser/config/classes.php */
	public readonly array $classes_data;

	/** @see _parser/config/static-methods.php */
	public readonly array $static_methods_data;

	public function __construct() {
		$parser_dir = dirname( __DIR__ );
		$project_dir = dirname( $parser_dir );

		$this->dest_dir = "$project_dir/copy";
		$this->wp_core_dir = "$project_dir/wordpress";
		$this->funcs_data = $this->build_functions_config( "$parser_dir/config/functions" );
		$this->classes_data = include "$parser_dir/config/classes.php";
		$this->static_methods_data = include "$parser_dir/config/static-methods.php";
	}

	private function build_functions_config( string $base_dir ): array {
		$files = [];

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $base_dir, FilesystemIterator::SKIP_DOTS ),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach( $iterator as $file_info ){
			if( ! $file_info->isFile() || $file_info->getExtension() !== 'php' ){
				continue;
			}

			$files[] = $file_info->getPathname();
		}

		sort( $files );

		$config = [];
		foreach( $files as $path ){
			$rel_file = str_replace( '\\', '/', substr( $path, strlen( $base_dir ) + 1 ) );
			$config[ $rel_file ] = include $path;
		}

		return $config;
	}

}
