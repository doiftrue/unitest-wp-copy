<?php

// ------------------auto-generated---------------------

// wp-includes/widgets.php (WP 7.0)
if( ! function_exists( 'is_registered_sidebar' ) ) :
	function is_registered_sidebar( $sidebar_id ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_registered_sidebars;
	
		return isset( $wp_registered_sidebars[ $sidebar_id ] );
	}
endif;

