<?php

// ------------------auto-generated---------------------

// wp-includes/media.php (WP 6.7.5)
if( ! function_exists( 'wp_get_additional_image_sizes' ) ) :
	function wp_get_additional_image_sizes() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $_wp_additional_image_sizes;
	
		if ( ! $_wp_additional_image_sizes ) {
			$_wp_additional_image_sizes = array();
		}
	
		return $_wp_additional_image_sizes;
	}
endif;

