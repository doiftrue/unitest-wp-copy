<?php

// ------------------auto-generated---------------------

// wp-includes/functions.php (WP 6.6.5)
if( ! function_exists( 'is_utf8_charset' ) ) :
	function is_utf8_charset( $blog_charset = null ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		return _is_utf8_charset( $blog_charset ?? $GLOBALS['stub_wp_options']->blog_charset );
	}
endif;

// wp-includes/functions.php (WP 6.6.5)
if( ! function_exists( '_deprecated_function' ) ) :
	function _deprecated_function( $function_name, $version, $replacement = '' ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
	
		/**
		 * Fires when a deprecated function is called.
		 *
		 * @since 2.5.0
		 *
		 * @param string $function_name The function that was called.
		 * @param string $replacement   The function that should have been called.
		 * @param string $version       The version of WordPress that deprecated the function.
		 */
		do_action( 'deprecated_function_run', $function_name, $replacement, $version );
	
		/**
		 * Filters whether to trigger an error for deprecated functions.
		 *
		 * @since 2.5.0
		 *
		 * @param bool $trigger Whether to trigger the error for deprecated functions. Default true.
		 */
		if ( WP_DEBUG && apply_filters( 'deprecated_function_trigger_error', true ) ) {
			if ( function_exists( '__' ) ) {
				if ( $replacement ) {
					$message = sprintf(
						/* translators: 1: PHP function name, 2: Version number, 3: Alternative function name. */
						__( 'Function %1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.' ),
						$function_name,
						$version,
						$replacement
					);
				} else {
					$message = sprintf(
						/* translators: 1: PHP function name, 2: Version number. */
						__( 'Function %1$s is <strong>deprecated</strong> since version %2$s with no alternative available.' ),
						$function_name,
						$version
					);
				}
			} else {
				if ( $replacement ) {
					$message = sprintf(
						'Function %1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.',
						$function_name,
						$version,
						$replacement
					);
				} else {
					$message = sprintf(
						'Function %1$s is <strong>deprecated</strong> since version %2$s with no alternative available.',
						$function_name,
						$version
					);
				}
			}
	
			wp_trigger_error( '', $message, E_USER_DEPRECATED );
		}
	}
endif;

// wp-includes/functions.php (WP 6.6.5)
if( ! function_exists( 'wp_timezone_string' ) ) :
	function wp_timezone_string() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$timezone_string = $GLOBALS['stub_wp_options']->timezone_string;
	
		if ( $timezone_string ) {
			return $timezone_string;
		}
	
		$offset  = (float) $GLOBALS['stub_wp_options']->gmt_offset;
		$hours   = (int) $offset;
		$minutes = ( $offset - $hours );
	
		$sign      = ( $offset < 0 ) ? '-' : '+';
		$abs_hour  = abs( $hours );
		$abs_mins  = abs( $minutes * 60 );
		$tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );
	
		return $tz_offset;
	}
endif;

// wp-includes/functions.php (WP 6.6.5)
if( ! function_exists( 'current_time' ) ) :
	function current_time( $type, $gmt = 0 ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		// Don't use non-GMT timestamp, unless you know the difference and really need to.
		if ( 'timestamp' === $type || 'U' === $type ) {
			return $gmt ? time() : time() + (int) ( $GLOBALS['stub_wp_options']->gmt_offset * HOUR_IN_SECONDS );
		}
	
		if ( 'mysql' === $type ) {
			$type = 'Y-m-d H:i:s';
		}
	
		$timezone = $gmt ? new DateTimeZone( 'UTC' ) : wp_timezone();
		$datetime = new DateTime( 'now', $timezone );
	
		return $datetime->format( $type );
	}
endif;

// wp-includes/functions.php (WP 6.6.5)
if( ! function_exists( 'wp_trigger_error' ) ) :
	function wp_trigger_error( $function_name, $message, $error_level = E_USER_NOTICE ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
	
		// Bail out if WP_DEBUG is not turned on.
		if ( ! WP_DEBUG ) {
			return;
		}
	
		/**
		 * Fires when the given function triggers a user-level error/warning/notice/deprecation message.
		 *
		 * Can be used for debug backtracking.
		 *
		 * @since 6.4.0
		 *
		 * @param string $function_name The function that was called.
		 * @param string $message       A message explaining what has been done incorrectly.
		 * @param int    $error_level   The designated error type for this error.
		 */
		do_action( 'wp_trigger_error_run', $function_name, $message, $error_level );
	
		if ( ! empty( $function_name ) ) {
			$message = sprintf( '%s(): %s', $function_name, $message );
		}
	
		$message = wp_kses(
			$message,
			array(
				'a'      => array( 'href' => true ),
				'br'     => array(),
				'code'   => array(),
				'em'     => array(),
				'strong' => array(),
			),
			array( 'http', 'https' )
		);
	
		trigger_error( $message, $error_level );
	}
endif;

// wp-includes/functions.php (WP 6.6.5)
if( ! function_exists( 'force_ssl_admin' ) ) :
	function force_ssl_admin( $force = null ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		static $forced = false;
	
		if ( ! is_null( $force ) ) {
			$old_forced = $forced;
			$forced     = $force;
			return $old_forced;
		}
	
		return $forced;
	}
endif;

// wp-includes/functions.php (WP 6.6.5)
if( ! function_exists( 'wp_suspend_cache_addition' ) ) :
	function wp_suspend_cache_addition( $suspend = null ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		static $_suspend = false;
	
		if ( is_bool( $suspend ) ) {
			$_suspend = $suspend;
		}
	
		return $_suspend;
	}
endif;

