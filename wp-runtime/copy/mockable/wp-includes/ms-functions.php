<?php

// ------------------auto-generated---------------------

// wp-includes/ms-functions.php (WP 7.1.1)
if( ! function_exists( 'get_current_site' ) ) :
	function get_current_site() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $current_site;
		return $current_site;
	}
endif;

// wp-includes/ms-functions.php (WP 7.1.1)
if( ! function_exists( 'force_ssl_content' ) ) :
	function force_ssl_content( $force = null ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		static $forced_content = false;
	
		if ( ! is_null( $force ) ) {
			$old_forced     = $forced_content;
			$forced_content = (bool) $force;
			return $old_forced;
		}
	
		return $forced_content;
	}
endif;

