<?php

// ------------------auto-generated---------------------

// wp-includes/http.php (WP 6.5.8)
if( ! function_exists( 'get_http_origin' ) ) :
	function get_http_origin() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$origin = '';
		if ( ! empty( $_SERVER['HTTP_ORIGIN'] ) ) {
			$origin = $_SERVER['HTTP_ORIGIN'];
		}
	
		/**
		 * Change the origin of an HTTP request.
		 *
		 * @since 3.4.0
		 *
		 * @param string $origin The original origin for the request.
		 */
		return apply_filters( 'http_origin', $origin );
	}
endif;

