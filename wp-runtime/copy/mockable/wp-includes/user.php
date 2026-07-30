<?php

// ------------------auto-generated---------------------

// wp-includes/user.php (WP 7.0.2)
if( ! function_exists( 'wp_is_application_passwords_supported' ) ) :
	function wp_is_application_passwords_supported() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		return is_ssl() || 'local' === wp_get_environment_type();
	}
endif;

// wp-includes/user.php (WP 7.0.2)
if( ! function_exists( 'wp_get_session_token' ) ) :
	function wp_get_session_token() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$cookie = wp_parse_auth_cookie( '', 'logged_in' );
		return ! empty( $cookie['token'] ) ? $cookie['token'] : '';
	}
endif;

