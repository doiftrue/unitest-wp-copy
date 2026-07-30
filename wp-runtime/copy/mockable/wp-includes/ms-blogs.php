<?php

// ------------------auto-generated---------------------

// wp-includes/ms-blogs.php (WP 7.0.2)
if( ! function_exists( 'ms_is_switched' ) ) :
	function ms_is_switched() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		return ! empty( $GLOBALS['_wp_switched_stack'] );
	}
endif;

