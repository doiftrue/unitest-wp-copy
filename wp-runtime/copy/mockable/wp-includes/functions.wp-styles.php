<?php

// ------------------auto-generated---------------------

// wp-includes/functions.wp-styles.php (WP 6.9.4)
if( ! function_exists( 'wp_styles' ) ) :
	function wp_styles() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_styles;
	
		if ( ! ( $wp_styles instanceof WP_Styles ) ) {
			$wp_styles = new WP_Styles();
		}
	
		return $wp_styles;
	}
endif;

