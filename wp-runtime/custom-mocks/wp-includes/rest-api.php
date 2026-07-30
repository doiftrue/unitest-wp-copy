<?php
/**
 * Runtime-adapted WordPress REST API functions.
 * Supports WP_Mock unit testing implementation.
 */

use Unitest_WP_Copy\WP_Mock_Utils;

/**
 * Runtime adaptation of get_rest_url() from WordPress 7.0 wp-includes/rest-api.php.
 *
 * Known difference from WordPress core: this isolated runtime returns a stable
 * pretty REST URL and does not emulate permalink, rewrite, or multisite blog
 * option behavior. Use a WP_Mock handler when a test needs a different REST root.
 */
if ( ! function_exists( 'get_rest_url' ) ) :
	function get_rest_url( $blog_id = null, $path = '/', $scheme = 'rest' ) {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		if ( empty( $path ) ) {
			$path = '/';
		}

		$path = '/' . ltrim( $path, '/' );
		$url  = get_home_url( $blog_id, rest_get_url_prefix(), $scheme );
		$url  = rtrim( $url, '/' ) . $path;

		return apply_filters( 'rest_url', $url, $path, $blog_id, $scheme );
	}
endif;
