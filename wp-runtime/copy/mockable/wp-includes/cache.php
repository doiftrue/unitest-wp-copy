<?php

// ------------------auto-generated---------------------

// wp-includes/cache.php (WP 6.7.5)
if( ! function_exists( 'wp_cache_get_multiple' ) ) :
	function wp_cache_get_multiple( $keys, $group = '', $force = false ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_object_cache;
	
		return $wp_object_cache->get_multiple( $keys, $group, $force );
	}
endif;

