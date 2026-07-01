<?php

// ------------------auto-generated---------------------

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( '_wp_can_use_pcre_u' ) ) :
	function _wp_can_use_pcre_u( $set = null ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		static $utf8_pcre = null;
	
		if ( isset( $set ) ) {
			_deprecated_argument( __FUNCTION__, '6.9.0' );
		}
	
		if ( isset( $utf8_pcre ) ) {
			return $utf8_pcre;
		}
	
		$utf8_pcre = true;
		set_error_handler(
			function ( $errno, $errstr ) use ( &$utf8_pcre ) {
				if ( str_starts_with( $errstr, 'preg_match():' ) ) {
					$utf8_pcre = false;
					return true;
				}
	
				return false;
			},
			E_WARNING
		);
	
		/*
		 * Attempt to compile a PCRE pattern with the PCRE_UTF8 flag. For
		 * systems lacking Unicode support this will trigger a warning
		 * during compilation, which the error handler will intercept.
		 */
		preg_match( '//u', '' );
		restore_error_handler();
	
		return $utf8_pcre;
	}
endif;

