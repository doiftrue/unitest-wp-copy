<?php

namespace Unitest_WP_Copy;

class Bootstrap {

	private static bool $initialized = false;

	public static function init(): void {
		if ( self::$initialized ) {
			return;
		}

		self::$initialized = true;

		$base_dir = __DIR__;
		self::load_wp_symbols( $base_dir );
		self::load_wp_runtime( $base_dir );
	}

	/**
	 * Gets WP version of current runtime copied code.
	 *
	 * @return string Eg: 6.5
	 */
	private static function detect_wp_line(): string {
		$content = file_get_contents( __DIR__ . '/SYMBOLS-INFO.md', false, null, 0, 8 * 512 );
		preg_match( '~WordPress (\d+\.\d+)~m', $content, $m );
		return $m[1] ?? '';
	}

	private static function load_wp_symbols( string $base_dir ): void {
		// NOTE: Before runtime files to override copies
		$line_file = sprintf( "$base_dir/mocks/wp-%s.php", self::detect_wp_line() );
		if ( is_file( $line_file ) ) {
			include_once $line_file;
		}

		self::require_files( [
			...glob( "$base_dir/copy/functions/*.php" ),
			...glob( "$base_dir/copy/functions/wp-admin/includes/*.php" ),
			...glob( "$base_dir/copy/functions/wp-includes/*.php" ),
			...glob( "$base_dir/copy/classes-statics/*.php" ),
			...glob( "$base_dir/copy/classes/*.php" ),
			...glob( "$base_dir/copy/mockable/wp-includes/*.php" ),
			...glob( "$base_dir/mocks/wp-includes/*.php" ),
		] );
	}

	private static function load_wp_runtime( string $base_dir ): void {
		require_once "$base_dir/WP_Mock_Utils.php";
		require_once "$base_dir/base-wp-constants.php";
		require_once "$base_dir/stub-wp-options.php";

		self::setup_wp_constants();
		self::require_files( [
			...glob( "$base_dir/init-parts/wp-includes/*.php" ),
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

		global $wp_post_types;
		$wp_post_types = is_array( $wp_post_types ?? null ) ? $wp_post_types : [];

		global $wp_taxonomies;
		$wp_taxonomies = is_array( $wp_taxonomies ?? null ) ? $wp_taxonomies : [];
	}

	private static function require_files( array $files ): void {
		foreach ( $files as $file ) {
			require_once $file;
		}
	}

}
