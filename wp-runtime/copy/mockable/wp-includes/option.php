<?php

// ------------------auto-generated---------------------

// wp-includes/option.php (WP 6.7.5)
if( ! function_exists( 'get_registered_settings' ) ) :
	function get_registered_settings() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_registered_settings;
	
		if ( ! is_array( $wp_registered_settings ) ) {
			return array();
		}
	
		return $wp_registered_settings;
	}
endif;

