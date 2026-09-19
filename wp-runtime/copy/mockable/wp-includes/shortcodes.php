<?php

// ------------------auto-generated---------------------

// wp-includes/shortcodes.php (WP 7.0.5)
if( ! function_exists( 'shortcode_exists' ) ) :
	function shortcode_exists( $tag ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $shortcode_tags;
		return array_key_exists( $tag, $shortcode_tags );
	}
endif;

