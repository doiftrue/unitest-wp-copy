<?php

namespace Unitest_WP_Copy;

class Bootstrap {

	private static bool $initialized = false;

	public static function init(): void {
		if ( self::$initialized ) {
			return;
		}

		self::$initialized = true;

		$base_dir = dirname( __DIR__ );

		self::load_wp_symbols( $base_dir );
		self::load_wp_runtime( $base_dir );
	}

	private static function load_wp_symbols( string $base_dir ): void {
		self::require_files( [
			...glob( "$base_dir/copy/functions/*.php" ),
			...glob( "$base_dir/copy/functions/wp-includes/*.php" ),
			...glob( "$base_dir/copy/classes-statics/*.php" ),
			...glob( "$base_dir/copy/classes/*.php" ),
			...glob( "$base_dir/copy/mocks/wp-includes/*.php" ),
			"$base_dir/src/WP_Mock_Utils.php"
		] );
	}

	private static function load_wp_runtime( string $base_dir ): void {
		require_once "$base_dir/src/stub-wp-options.php";
		require_once "$base_dir/src/base-wp-constants.php";

		self::setup_wp_constants();
		self::require_files( [
			...glob( "$base_dir/copy/init-parts/wp-includes/*.php" ),
		] );
		self::setup_wp_globals();
	}

	private static function setup_wp_constants(): void {
		wp_initial_constants();
		wp_plugin_directory_constants();
		wp_cookie_constants();
		wp_ssl_constants();
		wp_functionality_constants();
	}

	private static function setup_wp_globals(): void {
		smilies_init();

		$GLOBALS['timestart'] = microtime( true );
		$_SERVER['HTTP_HOST'] = parse_url( $GLOBALS['stub_wp_options']->home, PHP_URL_HOST );

		global $wp_plugin_paths;
		$wp_plugin_paths || $wp_plugin_paths = [];

		global $shortcode_tags;
		$shortcode_tags = [];

		global $wp_locale;
		$wp_locale = new \WP_Locale();
	}

	private static function require_files( array $files ): void {
		foreach ( $files as $file ) {
			require_once $file;
		}
	}

}
