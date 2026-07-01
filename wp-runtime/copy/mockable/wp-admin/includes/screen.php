<?php

// ------------------auto-generated---------------------

// wp-admin/includes/screen.php (WP 6.5.8)
if( ! function_exists( 'get_current_screen' ) ) :
	function get_current_screen() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $current_screen;
	
		if ( ! isset( $current_screen ) ) {
			return null;
		}
	
		return $current_screen;
	}
endif;

