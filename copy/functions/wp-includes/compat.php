<?php

// ------------------auto-generated---------------------

// wp-includes/compat.php (WP 6.8.3)
if( ! function_exists( '_' ) ) :
		function _( $message ) {
			return $message;
		}
endif;

// wp-includes/compat.php (WP 6.8.3)
if( ! function_exists( '_wp_can_use_pcre_u' ) ) :
	function _wp_can_use_pcre_u( $set = null ) {
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

// wp-includes/compat.php (WP 6.8.3)
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

// wp-includes/compat.php (WP 6.8.3)
if( ! function_exists( 'mb_substr' ) ) :
		function mb_substr( $string, $start, $length = null, $encoding = null ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.stringFound
			return _mb_substr( $string, $start, $length, $encoding );
		}
endif;

// wp-includes/compat.php (WP 6.8.3)
if( ! function_exists( '_mb_substr' ) ) :
	function _mb_substr( $str, $start, $length = null, $encoding = null ) {
		if ( null === $str ) {
			return '';
		}
	
		if ( null === $encoding ) {
			$encoding = $GLOBALS['stub_wp_options']->blog_charset;
		}
	
		/*
		 * The solution below works only for UTF-8, so in case of a different
		 * charset just use built-in substr().
		 */
		if ( ! _is_utf8_charset( $encoding ) ) {
			return is_null( $length ) ? substr( $str, $start ) : substr( $str, $start, $length );
		}
	
		if ( _wp_can_use_pcre_u() ) {
			// Use the regex unicode support to separate the UTF-8 characters into an array.
			preg_match_all( '/./us', $str, $match );
			$chars = is_null( $length ) ? array_slice( $match[0], $start ) : array_slice( $match[0], $start, $length );
			return implode( '', $chars );
		}
	
		$regex = '/(
			[\x00-\x7F]                  # single-byte sequences   0xxxxxxx
			| [\xC2-\xDF][\x80-\xBF]       # double-byte sequences   110xxxxx 10xxxxxx
			| \xE0[\xA0-\xBF][\x80-\xBF]   # triple-byte sequences   1110xxxx 10xxxxxx * 2
			| [\xE1-\xEC][\x80-\xBF]{2}
			| \xED[\x80-\x9F][\x80-\xBF]
			| [\xEE-\xEF][\x80-\xBF]{2}
			| \xF0[\x90-\xBF][\x80-\xBF]{2} # four-byte sequences   11110xxx 10xxxxxx * 3
			| [\xF1-\xF3][\x80-\xBF]{3}
			| \xF4[\x80-\x8F][\x80-\xBF]{2}
		)/x';
	
		// Start with 1 element instead of 0 since the first thing we do is pop.
		$chars = array( '' );
	
		do {
			// We had some string left over from the last round, but we counted it in that last round.
			array_pop( $chars );
	
			/*
			 * Split by UTF-8 character, limit to 1000 characters (last array element will contain
			 * the rest of the string).
			 */
			$pieces = preg_split( $regex, $str, 1000, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
	
			$chars = array_merge( $chars, $pieces );
	
			// If there's anything left over, repeat the loop.
		} while ( count( $pieces ) > 1 && $str = array_pop( $pieces ) );
	
		return implode( '', array_slice( $chars, $start, $length ) );
	}
endif;

// wp-includes/compat.php (WP 6.8.3)
if( ! function_exists( 'mb_strlen' ) ) :
		function mb_strlen( $string, $encoding = null ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.stringFound
			return _mb_strlen( $string, $encoding );
		}
endif;

// wp-includes/compat.php (WP 6.8.3)
if( ! function_exists( '_mb_strlen' ) ) :
	function _mb_strlen( $str, $encoding = null ) {
		if ( null === $encoding ) {
			$encoding = $GLOBALS['stub_wp_options']->blog_charset;
		}
	
		/*
		 * The solution below works only for UTF-8, so in case of a different charset
		 * just use built-in strlen().
		 */
		if ( ! _is_utf8_charset( $encoding ) ) {
			return strlen( $str );
		}
	
		if ( _wp_can_use_pcre_u() ) {
			// Use the regex unicode support to separate the UTF-8 characters into an array.
			preg_match_all( '/./us', $str, $match );
			return count( $match[0] );
		}
	
		$regex = '/(?:
			[\x00-\x7F]                  # single-byte sequences   0xxxxxxx
			| [\xC2-\xDF][\x80-\xBF]       # double-byte sequences   110xxxxx 10xxxxxx
			| \xE0[\xA0-\xBF][\x80-\xBF]   # triple-byte sequences   1110xxxx 10xxxxxx * 2
			| [\xE1-\xEC][\x80-\xBF]{2}
			| \xED[\x80-\x9F][\x80-\xBF]
			| [\xEE-\xEF][\x80-\xBF]{2}
			| \xF0[\x90-\xBF][\x80-\xBF]{2} # four-byte sequences   11110xxx 10xxxxxx * 3
			| [\xF1-\xF3][\x80-\xBF]{3}
			| \xF4[\x80-\x8F][\x80-\xBF]{2}
		)/x';
	
		// Start at 1 instead of 0 since the first thing we do is decrement.
		$count = 1;
	
		do {
			// We had some string left over from the last round, but we counted it in that last round.
			--$count;
	
			/*
			 * Split by UTF-8 character, limit to 1000 characters (last array element will contain
			 * the rest of the string).
			 */
			$pieces = preg_split( $regex, $str, 1000 );
	
			// Increment.
			$count += count( $pieces );
	
			// If there's anything left over, repeat the loop.
		} while ( $str = array_pop( $pieces ) );
	
		// Fencepost: preg_split() always returns one extra item in the array.
		return --$count;
	}
endif;

// wp-includes/compat.php (WP 6.8.3)
if( ! function_exists( 'is_countable' ) ) :
		function is_countable( $value ) {
			return ( is_array( $value )
				|| $value instanceof Countable
				|| $value instanceof SimpleXMLElement
				|| $value instanceof ResourceBundle
			);
		}
endif;

// wp-includes/compat.php (WP 6.8.3)
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

// wp-includes/compat.php (WP 6.8.3)
if( ! function_exists( 'array_key_last' ) ) :
		function array_key_last( array $array ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.arrayFound
			if ( empty( $array ) ) {
				return null;
			}
	
			end( $array );
	
			return key( $array );
		}
endif;

// wp-includes/compat.php (WP 6.8.3)
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

// wp-includes/compat.php (WP 6.8.3)
if( ! function_exists( 'str_contains' ) ) :
		function str_contains( $haystack, $needle ) {
			if ( '' === $needle ) {
				return true;
			}
	
			return false !== strpos( $haystack, $needle );
		}
endif;

// wp-includes/compat.php (WP 6.8.3)
if( ! function_exists( 'str_starts_with' ) ) :
		function str_starts_with( $haystack, $needle ) {
			if ( '' === $needle ) {
				return true;
			}
	
			return 0 === strpos( $haystack, $needle );
		}
endif;

// wp-includes/compat.php (WP 6.8.3)
if( ! function_exists( 'str_ends_with' ) ) :
		function str_ends_with( $haystack, $needle ) {
			if ( '' === $haystack ) {
				return '' === $needle;
			}
	
			$len = strlen( $needle );
	
			return substr( $haystack, -$len, $len ) === $needle;
		}
endif;

// wp-includes/compat.php (WP 6.8.3)
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

// wp-includes/compat.php (WP 6.8.3)
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

// wp-includes/compat.php (WP 6.8.3)
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

// wp-includes/compat.php (WP 6.8.3)
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

