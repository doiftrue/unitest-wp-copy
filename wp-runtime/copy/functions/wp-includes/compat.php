<?php

// ------------------auto-generated---------------------

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( '_' ) ) :
		function _( $message ) {
			return $message;
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( '_is_utf8_charset' ) ) :
	function _is_utf8_charset( $charset_slug ) {
		if ( ! is_string( $charset_slug ) ) {
			return false;
		}
	
		return (
			0 === strcasecmp( 'UTF-8', $charset_slug ) ||
			0 === strcasecmp( 'UTF8', $charset_slug )
		);
	}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( '_wp_can_use_pcre_u' ) ) :
	function _wp_can_use_pcre_u( $set = null ) {
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

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( '_mb_substr' ) ) :
	function _mb_substr( $str, $start, $length = null, $encoding = null ) {
		if ( null === $str ) {
			return '';
		}
	
		// The solution below works only for UTF-8; treat all other encodings as byte streams.
		if ( ! _is_utf8_charset( $encoding ?? $GLOBALS['stub_wp_options']->blog_charset ) ) {
			return is_null( $length ) ? substr( $str, $start ) : substr( $str, $start, $length );
		}
	
		$total_length = ( $start < 0 || $length < 0 )
			? _wp_utf8_codepoint_count( $str )
			: 0;
	
		$normalized_start = $start < 0
			? max( 0, $total_length + $start )
			: $start;
	
		/*
		 * The starting offset is provided as characters, which means this needs to
		 * find how many bytes that many characters occupies at the start of the string.
		 */
		$starting_byte_offset = _wp_utf8_codepoint_span( $str, 0, $normalized_start );
	
		$normalized_length = $length < 0
			? max( 0, $total_length - $normalized_start + $length )
			: $length;
	
		/*
		 * This is the main step. It finds how many bytes the given length of code points
		 * occupies in the input, starting at the byte offset calculated above.
		 */
		$byte_length = isset( $normalized_length )
			? _wp_utf8_codepoint_span( $str, $starting_byte_offset, $normalized_length )
			: ( strlen( $str ) - $starting_byte_offset );
	
		// The result is a normal byte-level substring using the computed ranges.
		return substr( $str, $starting_byte_offset, $byte_length );
	}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( '_mb_strlen' ) ) :
	function _mb_strlen( $str, $encoding = null ) {
		return _is_utf8_charset( $encoding ?? $GLOBALS['stub_wp_options']->blog_charset )
			? _wp_utf8_codepoint_count( $str )
			: strlen( $str );
	}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'mb_substr' ) ) :
		function mb_substr( $string, $start, $length = null, $encoding = null ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.stringFound
			return _mb_substr( $string, $start, $length, $encoding );
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'mb_strlen' ) ) :
		function mb_strlen( $string, $encoding = null ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.stringFound
			return _mb_strlen( $string, $encoding );
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'is_countable' ) ) :
		function is_countable( $value ) {
			return ( is_array( $value )
				|| $value instanceof Countable
				|| $value instanceof SimpleXMLElement
				|| $value instanceof ResourceBundle
			);
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'array_key_first' ) ) :
		function array_key_first( array $array ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.arrayFound
			if ( empty( $array ) ) {
				return null;
			}
	
			foreach ( $array as $key => $value ) {
				return $key;
			}
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'array_key_last' ) ) :
		function array_key_last( array $array ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.arrayFound
			if ( empty( $array ) ) {
				return null;
			}
	
			end( $array );
	
			return key( $array );
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'array_is_list' ) ) :
		function array_is_list( $arr ) {
			if ( ( array() === $arr ) || ( array_values( $arr ) === $arr ) ) {
				return true;
			}
	
			$next_key = -1;
	
			foreach ( $arr as $k => $v ) {
				if ( ++$next_key !== $k ) {
					return false;
				}
			}
	
			return true;
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'str_contains' ) ) :
		function str_contains( $haystack, $needle ) {
			if ( '' === $needle ) {
				return true;
			}
	
			return false !== strpos( $haystack, $needle );
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'str_starts_with' ) ) :
		function str_starts_with( $haystack, $needle ) {
			if ( '' === $needle ) {
				return true;
			}
	
			return 0 === strpos( $haystack, $needle );
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'str_ends_with' ) ) :
		function str_ends_with( $haystack, $needle ) {
			if ( '' === $haystack ) {
				return '' === $needle;
			}
	
			$len = strlen( $needle );
	
			return substr( $haystack, -$len, $len ) === $needle;
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'array_find' ) ) :
		function array_find( array $array, callable $callback ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.arrayFound
			foreach ( $array as $key => $value ) {
				if ( $callback( $value, $key ) ) {
					return $value;
				}
			}
	
			return null;
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'array_find_key' ) ) :
		function array_find_key( array $array, callable $callback ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.arrayFound
			foreach ( $array as $key => $value ) {
				if ( $callback( $value, $key ) ) {
					return $key;
				}
			}
	
			return null;
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'array_any' ) ) :
		function array_any( array $array, callable $callback ): bool { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.arrayFound
			foreach ( $array as $key => $value ) {
				if ( $callback( $value, $key ) ) {
					return true;
				}
			}
	
			return false;
		}
endif;

// wp-includes/compat.php (WP 6.9.4)
if( ! function_exists( 'array_all' ) ) :
		function array_all( array $array, callable $callback ): bool { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.arrayFound
			foreach ( $array as $key => $value ) {
				if ( ! $callback( $value, $key ) ) {
					return false;
				}
			}
	
			return true;
		}
endif;

