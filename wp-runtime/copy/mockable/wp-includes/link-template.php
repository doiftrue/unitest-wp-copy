<?php

// ------------------auto-generated---------------------

// wp-includes/link-template.php (WP 6.9.4)
if( ! function_exists( 'get_home_url' ) ) :
	function get_home_url( $blog_id = null, $path = '', $scheme = null ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$orig_scheme = $scheme;
	
		if ( empty( $blog_id ) || ! is_multisite() ) {
			$url = $GLOBALS['stub_wp_options']->home;
		} else {
			switch_to_blog( $blog_id );
			$url = $GLOBALS['stub_wp_options']->home;
			restore_current_blog();
		}
	
		if ( ! in_array( $scheme, array( 'http', 'https', 'relative' ), true ) ) {
			if ( is_ssl() ) {
				$scheme = 'https';
			} else {
				$scheme = parse_url( $url, PHP_URL_SCHEME );
			}
		}
	
		$url = set_url_scheme( $url, $scheme );
	
		if ( $path && is_string( $path ) ) {
			$url .= '/' . ltrim( $path, '/' );
		}
	
		/**
		 * Filters the home URL.
		 *
		 * @since 3.0.0
		 *
		 * @param string      $url         The complete home URL including scheme and path.
		 * @param string      $path        Path relative to the home URL. Blank string if no path is specified.
		 * @param string|null $orig_scheme Scheme to give the home URL context. Accepts 'http', 'https',
		 *                                 'relative', 'rest', or null.
		 * @param int|null    $blog_id     Site ID, or null for the current site.
		 */
		return apply_filters( 'home_url', $url, $path, $orig_scheme, $blog_id );
	}
endif;

// wp-includes/link-template.php (WP 6.9.4)
if( ! function_exists( 'get_site_url' ) ) :
	function get_site_url( $blog_id = null, $path = '', $scheme = null ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( empty( $blog_id ) || ! is_multisite() ) {
			$url = $GLOBALS['stub_wp_options']->siteurl;
		} else {
			switch_to_blog( $blog_id );
			$url = $GLOBALS['stub_wp_options']->siteurl;
			restore_current_blog();
		}
	
		$url = set_url_scheme( $url, $scheme );
	
		if ( $path && is_string( $path ) ) {
			$url .= '/' . ltrim( $path, '/' );
		}
	
		/**
		 * Filters the site URL.
		 *
		 * @since 2.7.0
		 *
		 * @param string      $url     The complete site URL including scheme and path.
		 * @param string      $path    Path relative to the site URL. Blank string if no path is specified.
		 * @param string|null $scheme  Scheme to give the site URL context. Accepts 'http', 'https', 'login',
		 *                             'login_post', 'admin', 'relative' or null.
		 * @param int|null    $blog_id Site ID, or null for the current site.
		 */
		return apply_filters( 'site_url', $url, $path, $scheme, $blog_id );
	}
endif;

// wp-includes/link-template.php (WP 6.9.4)
if( ! function_exists( 'get_admin_url' ) ) :
	function get_admin_url( $blog_id = null, $path = '', $scheme = 'admin' ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$url = get_site_url( $blog_id, 'wp-admin/', $scheme );
	
		if ( $path && is_string( $path ) ) {
			$url .= ltrim( $path, '/' );
		}
	
		/**
		 * Filters the admin area URL.
		 *
		 * @since 2.8.0
		 * @since 5.8.0 The `$scheme` parameter was added.
		 *
		 * @param string      $url     The complete admin area URL including scheme and path.
		 * @param string      $path    Path relative to the admin area URL. Blank string if no path is specified.
		 * @param int|null    $blog_id Site ID, or null for the current site.
		 * @param string|null $scheme  The scheme to use. Accepts 'http', 'https',
		 *                             'admin', or null. Default 'admin', which obeys force_ssl_admin() and is_ssl().
		 */
		return apply_filters( 'admin_url', $url, $path, $blog_id, $scheme );
	}
endif;

// wp-includes/link-template.php (WP 6.9.4)
if( ! function_exists( 'plugins_url' ) ) :
	function plugins_url( $path = '', $plugin = '' ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
	
		$path          = wp_normalize_path( $path );
		$plugin        = wp_normalize_path( $plugin );
		$mu_plugin_dir = wp_normalize_path( WPMU_PLUGIN_DIR );
	
		if ( ! empty( $plugin ) && str_starts_with( $plugin, $mu_plugin_dir ) ) {
			$url = WPMU_PLUGIN_URL;
		} else {
			$url = WP_PLUGIN_URL;
		}
	
		$url = set_url_scheme( $url );
	
		if ( ! empty( $plugin ) && is_string( $plugin ) ) {
			$folder = dirname( plugin_basename( $plugin ) );
			if ( '.' !== $folder ) {
				$url .= '/' . ltrim( $folder, '/' );
			}
		}
	
		if ( $path && is_string( $path ) ) {
			$url .= '/' . ltrim( $path, '/' );
		}
	
		/**
		 * Filters the URL to the plugins directory.
		 *
		 * @since 2.8.0
		 *
		 * @param string $url    The complete URL to the plugins directory including scheme and path.
		 * @param string $path   Path relative to the URL to the plugins directory. Blank string
		 *                       if no path is specified.
		 * @param string $plugin The plugin file path to be relative to. Blank string if no plugin
		 *                       is specified.
		 */
		return apply_filters( 'plugins_url', $url, $path, $plugin );
	}
endif;

// wp-includes/link-template.php (WP 6.9.4)
if( ! function_exists( 'content_url' ) ) :
	function content_url( $path = '' ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$url = set_url_scheme( WP_CONTENT_URL );
	
		if ( $path && is_string( $path ) ) {
			$url .= '/' . ltrim( $path, '/' );
		}
	
		/**
		 * Filters the URL to the content directory.
		 *
		 * @since 2.8.0
		 *
		 * @param string $url  The complete URL to the content directory including scheme and path.
		 * @param string $path Path relative to the URL to the content directory. Blank string
		 *                     if no path is specified.
		 */
		return apply_filters( 'content_url', $url, $path );
	}
endif;

// wp-includes/link-template.php (WP 6.9.4)
if( ! function_exists( 'includes_url' ) ) :
	function includes_url( $path = '', $scheme = null ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$url = site_url( '/' . WPINC . '/', $scheme );
	
		if ( $path && is_string( $path ) ) {
			$url .= ltrim( $path, '/' );
		}
	
		/**
		 * Filters the URL to the includes directory.
		 *
		 * @since 2.8.0
		 * @since 5.8.0 The `$scheme` parameter was added.
		 *
		 * @param string      $url    The complete URL to the includes directory including scheme and path.
		 * @param string      $path   Path relative to the URL to the wp-includes directory. Blank string
		 *                            if no path is specified.
		 * @param string|null $scheme Scheme to give the includes URL context. Accepts
		 *                            'http', 'https', 'relative', or null. Default null.
		 */
		return apply_filters( 'includes_url', $url, $path, $scheme );
	}
endif;

