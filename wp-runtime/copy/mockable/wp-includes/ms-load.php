<?php

// ------------------auto-generated---------------------

// wp-includes/ms-load.php (WP 6.9.4)
if( ! function_exists( 'is_subdomain_install' ) ) :
	function is_subdomain_install() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( defined( 'SUBDOMAIN_INSTALL' ) ) {
			return SUBDOMAIN_INSTALL;
		}
	
		return ( defined( 'VHOST' ) && 'yes' === VHOST );
	}
endif;

