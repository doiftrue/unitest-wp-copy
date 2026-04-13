<?php
/**
 * Mock implementations of WordPress load.php functions.
 * Supports WP_Mock unit testing implementation.
 */

use Unitest_WP_Copy\WP_Mock_Utils;

if ( ! function_exists( 'is_multisite' ) ) :
	function is_multisite() {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		if ( defined( 'MULTISITE' ) ) {
			return MULTISITE;
		}

		if ( defined( 'SUBDOMAIN_INSTALL' ) || defined( 'VHOST' ) || defined( 'SUNRISE' ) ) {
			return true;
		}

		return false;
	}
endif;

if ( ! function_exists( 'is_admin' ) ) :
	function is_admin() {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return (bool) WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		if ( isset( $GLOBALS['current_screen'] ) ) {
			return $GLOBALS['current_screen']->in_admin();
		} elseif ( defined( 'WP_ADMIN' ) ) {
			return WP_ADMIN;
		}

		return false;
	}
endif;
