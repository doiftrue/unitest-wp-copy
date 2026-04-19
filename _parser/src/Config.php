<?php
namespace Parser;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Config {

	public readonly string $dest_dir;

	public readonly string $wp_core_dir;

	public readonly string $wp_version;

	public readonly string $wp_version_line;

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
		$this->dest_dir = "$project_dir/wp-runtime/copy";
		$this->wp_core_dir = "$project_dir/wp-core";

		require_once "$this->wp_core_dir/wp-includes/version.php";
		/** @var string $wp_version */
		$this->wp_version = $wp_version;
		$this->wp_version_line = $this->parse_version_line( $this->wp_version );
		$this->line_config_dir = "$this->config_dir/$this->wp_version_line";

		// load configs
		$this->funcs_data = $this->build_funcs_config();
		$this->classes_data = $this->build_classes_config();
		$this->static_methods_data = $this->build_static_methods_config();
	}

	/** EG: 6.8.0  >>>  6.8 */
	private function parse_version_line( string $wp_version ): string {
		preg_match( '~^(\d+\.\d+)~', $wp_version, $m );
		return $m[1];
	}

	private function build_funcs_config(): array {
		$base_config = $this->load_nested_config_files( "$this->config_dir/functions" );
		$moved_data  = $this->load_php_config_file( "$this->config_dir/symbols-moved.php", false );
		$ver_config  = $this->load_nested_config_files( "$this->line_config_dir/functions" );

		$base_config = $this->apply_moves_config(
			$base_config,
			$moved_data['functions'] ?? [],
			$this->wp_version_line
		);

		return $this->merge_nested_configs( $base_config, $ver_config );
	}

	private function build_classes_config(): array {
		$base_config = $this->load_php_config_file( "$this->config_dir/classes.php", true );
		$ver_config  = $this->load_php_config_file( "$this->line_config_dir/classes.php", false );

		return $this->merge_nested_configs( $base_config, $ver_config );
	}

	private function build_static_methods_config(): array {
		$base_config = $this->load_php_config_file( "$this->config_dir/static-methods.php", true );
		$ver_config  = $this->load_php_config_file( "$this->line_config_dir/static-methods.php", false );

		return $this->merge_flat_configs( $base_config, $ver_config );
	}

	private function load_nested_config_files( string $base_dir ): array {
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

	private function load_php_config_file( string $file, bool $required ): array {
		if( ! $required && ! file_exists( $file ) ){
			return [];
		}

		return require $file;
	}

	/**
	 * Move function config between source files by WP line.
	 *
	 * Config format (config/symbols-moved.php):
	 * [
	 *   'functions' => [
	 *     'func_name' => [
	 *       'moved_in' => '6.7',
	 *       'from'    => 'wp-includes/functions.php',
	 *       'to'      => 'wp-includes/load.php',
	 *     ],
	 *   ],
	 * ]
	 *
	 * For versions `< moved_in`, symbol should be in `from`.
	 * For versions `>= moved_in`, symbol should be in `to`.
	 */
	private function apply_moves_config( array $base_config, array $moves_config, string $wp_line ): array {
		foreach( $moves_config as $func_name => $mv_data ){
			$from = $mv_data['from'];
			$to   = $mv_data['to'];

			$target_file = version_compare( $wp_line, $mv_data['moved_in'], '<' ) ? $from : $to;
			$other_file  = $target_file === $from ? $to : $from;

			if( isset( $base_config[ $target_file ][ $func_name ] ) ){
				continue;
			}

			$base_config[ $target_file ][ $func_name ] = $base_config[ $other_file ][ $func_name ];
			unset( $base_config[ $other_file ][ $func_name ] );
		}

		return $base_config;
	}

	/**
	 * Merges config with flat array data: [ rel_file => value ] See: static-methods.php
	 *
	 * Override rules:
	 * - false value deletes array element.
	 * - scalar value replaces/creates element;
	 */
	private function merge_flat_configs( array $base_config, array $ver_config ): array {
		foreach( $ver_config as $rel_file => $value ){
			if( false === $value ){
				unset( $base_config[ $rel_file ] );
				continue;
			}

			$base_config[ $rel_file ] = $value;
		}

		return $base_config;
	}

	/**
	 * Merges nested symbol config.
	 *
	 * Example:
	 *
	 *     [
	 *         [wp-includes/compat.php] => [
	 *             [function_name] => '4.9.6 mockable'
	 *         ],
	 *         [wp-includes/class-wp-error.php] => [
	 *             [WP_Error] => '2.1.0'
	 *         ]
	 *     ]
	 *
	 * Override rules:
	 * - false value deletes array element.
	 * - scalar value replaces/creates element;
	 */
	private function merge_nested_configs( array $base_config, array $ver_config ): array {
		foreach( $ver_config as $rel_file => $data ){
			foreach( $data as $name => $info ){
				if( false === $info ){
					unset( $base_config[ $rel_file ][ $name ] );
					continue;
				}

				$base_config[ $rel_file ][ $name ] = $info;
			}
		}

		return $base_config;
	}

}
