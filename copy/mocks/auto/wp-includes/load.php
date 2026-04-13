<?php

// ------------------auto-generated---------------------

// wp-includes/load.php (WP 6.8.5)
if( ! function_exists( 'is_admin' ) ) :
	function is_admin() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( isset( $GLOBALS['current_screen'] ) ) {
			return $GLOBALS['current_screen']->in_admin();
		} elseif ( defined( 'WP_ADMIN' ) ) {
			return WP_ADMIN;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.8.5)
if( ! function_exists( 'is_multisite' ) ) :
	function is_multisite() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
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

