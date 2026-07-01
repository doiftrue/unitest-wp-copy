<?php

// ------------------auto-generated---------------------

// wp-includes/compat.php (WP 6.7.5)
if( ! function_exists( '_wp_can_use_pcre_u' ) ) :
	function _wp_can_use_pcre_u( $set = null ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		static $utf8_pcre = 'reset';
	
		if ( null !== $set ) {
			$utf8_pcre = $set;
		}
	
		if ( 'reset' === $utf8_pcre ) {
			// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- intentional error generated to detect PCRE/u support.
			$utf8_pcre = @preg_match( '/^./u', 'a' );
		}
	
		return $utf8_pcre;
	}
endif;

