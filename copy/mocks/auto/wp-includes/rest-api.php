<?php

// ------------------auto-generated---------------------

// wp-includes/rest-api.php (WP 6.8.5)
if( ! function_exists( 'rest_handle_deprecated_function' ) ) :
	function rest_handle_deprecated_function( $function_name, $replacement, $version ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( ! WP_DEBUG || headers_sent() ) {
			return;
		}
		if ( ! empty( $replacement ) ) {
			/* translators: 1: Function name, 2: WordPress version number, 3: New function name. */
			$string = sprintf( __( '%1$s (since %2$s; use %3$s instead)' ), $function_name, $version, $replacement );
		} else {
			/* translators: 1: Function name, 2: WordPress version number. */
			$string = sprintf( __( '%1$s (since %2$s; no alternative available)' ), $function_name, $version );
		}
	
		header( sprintf( 'X-WP-DeprecatedFunction: %s', $string ) );
	}
endif;

// wp-includes/rest-api.php (WP 6.8.5)
if( ! function_exists( 'rest_handle_deprecated_argument' ) ) :
	function rest_handle_deprecated_argument( $function_name, $message, $version ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( ! WP_DEBUG || headers_sent() ) {
			return;
		}
		if ( $message ) {
			/* translators: 1: Function name, 2: WordPress version number, 3: Error message. */
			$string = sprintf( __( '%1$s (since %2$s; %3$s)' ), $function_name, $version, $message );
		} else {
			/* translators: 1: Function name, 2: WordPress version number. */
			$string = sprintf( __( '%1$s (since %2$s; no alternative available)' ), $function_name, $version );
		}
	
		header( sprintf( 'X-WP-DeprecatedParam: %s', $string ) );
	}
endif;

// wp-includes/rest-api.php (WP 6.8.5)
if( ! function_exists( 'rest_handle_doing_it_wrong' ) ) :
	function rest_handle_doing_it_wrong( $function_name, $message, $version ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( ! WP_DEBUG || headers_sent() ) {
			return;
		}
	
		if ( $version ) {
			/* translators: Developer debugging message. 1: PHP function name, 2: WordPress version number, 3: Explanatory message. */
			$string = __( '%1$s (since %2$s; %3$s)' );
			$string = sprintf( $string, $function_name, $version, $message );
		} else {
			/* translators: Developer debugging message. 1: PHP function name, 2: Explanatory message. */
			$string = __( '%1$s (%2$s)' );
			$string = sprintf( $string, $function_name, $message );
		}
	
		header( sprintf( 'X-WP-DoingItWrong: %s', $string ) );
	}
endif;

