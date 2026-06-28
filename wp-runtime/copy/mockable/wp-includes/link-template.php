<?php

// ------------------auto-generated---------------------

// wp-includes/link-template.php (WP 6.5.8)
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

// wp-includes/link-template.php (WP 6.5.8)
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

// wp-includes/link-template.php (WP 6.5.8)
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

