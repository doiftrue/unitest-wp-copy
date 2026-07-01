<?php

// ------------------auto-generated---------------------

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
if( ! function_exists( 'wp_scripts' ) ) :
	function wp_scripts() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_scripts;
	
		if ( ! ( $wp_scripts instanceof WP_Scripts ) ) {
			$wp_scripts = new WP_Scripts();
		}
	
		return $wp_scripts;
	}
endif;

