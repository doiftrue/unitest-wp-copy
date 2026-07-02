<?php
/**
 * Mock implementations of WordPress functions from wp-includes/functions.php.
 * Supports WP_Mock unit testing implementation.
 */

use Unitest_WP_Copy\WP_Mock_Utils;

if ( ! function_exists( 'wp_get_wp_version' ) ) :
	function wp_get_wp_version() {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		global $wp_version;
		return (string) ( $wp_version ?? '' );
	}
endif;
