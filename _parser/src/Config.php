<?php
namespace Parser;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Config {

	public readonly string $dest_dir;

	public readonly string $wp_core_dir;

	public readonly string $wp_version;

	public readonly string $config_dir;

	public readonly string $line_config_dir;

	/** @see config/functions/**.php */
	public readonly array $funcs_data;

	/** @see config/classes.php */
	public readonly array $classes_data;

	/** @see config/static-methods.php */
	public readonly array $static_methods_data;

	public function __construct() {
		$parser_dir = dirname( __DIR__ );
		$project_dir = dirname( $parser_dir );

		$this->config_dir = "$project_dir/config";
		$this->dest_dir = "$project_dir/copy";
		$this->wp_core_dir = "$project_dir/wordpress";

		require_once "$this->wp_core_dir/wp-includes/version.php";
		/** @var string $wp_version */
		$this->wp_version = $wp_version;
		$version_line = $this->parse_version_line( $this->wp_version );
		$this->line_config_dir = "$this->config_dir/$version_line";

		// load configs
		$this->funcs_data = $this->load_functions_config();
		$this->classes_data = $this->load_config_file( 'classes.php' );
		$this->static_methods_data = $this->load_config_file( 'static-methods.php' );
	}

	private function load_functions_config(): array {
		$base_config = $this->build_functions_config( "$this->config_dir/functions" );
		$version_config = $this->build_functions_config( "$this->line_config_dir/functions" );

		return $this->merge_config_with_overrides( $base_config, $version_config );
	}

	private function load_config_file( string $file_name ): array {
		$base_config = $this->load_php_array_file( "$this->config_dir/$file_name", true );
		$ver_config = $this->load_php_array_file( "$this->line_config_dir/$file_name", false );

		return $this->merge_config_with_overrides( $base_config, $ver_config );
	}

	private function build_functions_config( string $base_dir ): array {
		if( ! is_dir( $base_dir ) ){
			return [];
		}

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

	private function load_php_array_file( string $file, bool $required ): array {
		if( ! $required && ! file_exists( $file ) ){
			return [];
		}

		return require $file;
	}

	/** EG: 6.8.0  >>>  6.8 */
	private function parse_version_line( string $wp_version ): string {
		preg_match( '~^(\d+\.\d+)~', $wp_version, $m );
		return $m[1];
	}

	/**
	 * Merges base config with version overrides.
	 *
	 * Override rules:
	 * - false deletes key from merged result.
	 * - scalar value replaces/creates key;
	 */
	private function merge_config_with_overrides( array $base_config, array $version_config ): array {
		foreach( $version_config as $key => $value ){
			if( false === $value ){
				unset( $base_config[ $key ] );
				continue;
			}

			$base_config[ $key ] = $value;
		}

		return $base_config;
	}

}
