<?php
/**
 * Mock implementations of WordPress load.php functions.
 * Supports WP_Mock unit testing implementation.
 */

if ( ! function_exists( 'is_multisite' ) ) :
	function is_multisite() {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return (bool) WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
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
