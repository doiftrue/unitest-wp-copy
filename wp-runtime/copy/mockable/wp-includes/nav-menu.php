<?php

// ------------------auto-generated---------------------

// wp-includes/nav-menu.php (WP 7.0.2)
if( ! function_exists( 'get_registered_nav_menus' ) ) :
	function get_registered_nav_menus() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $_wp_registered_nav_menus;
		return $_wp_registered_nav_menus ?? array();
	}
endif;

