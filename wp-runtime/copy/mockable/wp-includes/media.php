<?php

// ------------------auto-generated---------------------

// wp-includes/media.php (WP 6.9.8)
if( ! function_exists( 'wp_high_priority_element_flag' ) ) :
	function wp_high_priority_element_flag( $value = null ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		static $high_priority_element = true;
	
		if ( is_bool( $value ) ) {
			$high_priority_element = $value;
		}
	
		return $high_priority_element;
	}
endif;

// wp-includes/media.php (WP 6.9.8)
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

