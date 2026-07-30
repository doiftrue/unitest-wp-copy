<?php

// ------------------auto-generated---------------------

// wp-includes/ms-functions.php (WP 7.0.2)
if( ! function_exists( 'get_current_site' ) ) :
	function get_current_site() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $current_site;
		return $current_site;
	}
endif;

