<?php

// ------------------auto-generated---------------------

// wp-includes/vars.php (WP 6.7.5)
if( ! function_exists( 'wp_is_mobile' ) ) :
	function wp_is_mobile() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( isset( $_SERVER['HTTP_SEC_CH_UA_MOBILE'] ) ) {
			// This is the `Sec-CH-UA-Mobile` user agent client hint HTTP request header.
			// See <https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Sec-CH-UA-Mobile>.
			$is_mobile = ( '?1' === $_SERVER['HTTP_SEC_CH_UA_MOBILE'] );
		} elseif ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$is_mobile = false;
		} elseif ( str_contains( $_SERVER['HTTP_USER_AGENT'], 'Mobile' ) // Many mobile devices (all iPhone, iPad, etc.)
			|| str_contains( $_SERVER['HTTP_USER_AGENT'], 'Android' )
			|| str_contains( $_SERVER['HTTP_USER_AGENT'], 'Silk/' )
			|| str_contains( $_SERVER['HTTP_USER_AGENT'], 'Kindle' )
			|| str_contains( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' )
			|| str_contains( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' )
			|| str_contains( $_SERVER['HTTP_USER_AGENT'], 'Opera Mobi' ) ) {
				$is_mobile = true;
		} else {
			$is_mobile = false;
		}
	
		/**
		 * Filters whether the request should be treated as coming from a mobile device or not.
		 *
		 * @since 4.9.0
		 *
		 * @param bool $is_mobile Whether the request is from a mobile device or not.
		 */
		return apply_filters( 'wp_is_mobile', $is_mobile );
	}
endif;

