<?php
/**
 * Mock implementations of WordPress load.php functions.
 * Supports WP_Mock unit testing implementation.
 */

if ( ! function_exists( 'is_multisite' ) ) :
	function is_multisite() {
		if ( wp_mock_has_handler( __FUNCTION__ ) ) {
			return (bool) wp_mock_call( __FUNCTION__, func_get_args() );
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
