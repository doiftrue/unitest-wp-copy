<?php

namespace Unitest_WP_Copy;

class Bootstrap {

	private string $over_dir;
	private string $base_dir;

	public static function init(): self {
		static $inst;
		if ( ! $inst ) {
			$inst = new self();
			$inst->load();
		}
		return $inst;
	}

	public function __construct() {
		$this->base_dir = __DIR__;
		$this->over_dir = sprintf( "$this->base_dir/wp-line-extra/%s", $this->detect_wp_line() );
	}

	private function load(): void {
		$this->load_wp_symbols();
		$this->load_wp_runtime();
	}

	/**
	 * Gets WP version of current runtime copied code.
	 *
	 * @return string Eg: 6.5
	 */
	private function detect_wp_line(): string {
		$content = file_get_contents( "$this->base_dir/SYMBOLS-INFO.md", false, null, 0, 8 * 512 );
		preg_match( '~WordPress (\d+\.\d+)~m', $content, $m );
		return $m[1] ?? '';
	}

	private function load_wp_symbols(): void {
		// NOTE: Before runtime files to override copies
		if ( is_file( $file = "$this->over_dir/mocks/overlaps.php" ) ) {
			include_once $file;
		}

		$this->require_files( [
			...glob( "$this->base_dir/copy/functions/*.php" ),
			...glob( "$this->base_dir/copy/functions/wp-admin/includes/*.php" ),
			...glob( "$this->base_dir/copy/functions/wp-includes/*.php" ),
			...glob( "$this->base_dir/copy/classes-statics/*.php" ),
			...glob( "$this->base_dir/copy/classes/*.php" ),
			...glob( "$this->base_dir/copy/mockable/wp-includes/*.php" ),
			...glob( "$this->base_dir/mocks/wp-includes/*.php" ),
		] );
	}

	private function load_wp_runtime(): void {
		require_once "$this->base_dir/WP_Mock_Utils.php";
		require_once "$this->base_dir/base-wp-constants.php";
		require_once "$this->base_dir/stub-wp-options.php";

		$this->setup_wp_constants();
		$this->load_init_parts();
		$this->setup_wp_globals();
	}

	private function load_init_parts(): void {
		require_once "$this->over_dir/wp-includes/version.php";
		$this->require_files( [
			...glob( "$this->base_dir/init-parts/wp-includes/*.php" ),
		] );
	}

	private function setup_wp_constants(): void {
		wp_initial_constants();
		wp_plugin_directory_constants();
		wp_cookie_constants();
		wp_ssl_constants();
		wp_functionality_constants();
	}

	private function setup_wp_globals(): void {
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

	private function require_files( array $files ): void {
		foreach ( $files as $file ) {
			require_once $file;
		}
	}

}
