<?php

// ------------------auto-generated---------------------

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'get_current_blog_id' ) ) :
	function get_current_blog_id() {
		global $blog_id;
	
		return absint( $blog_id );
	}
endif;

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'get_current_network_id' ) ) :
	function get_current_network_id() {
		if ( ! is_multisite() ) {
			return 1;
		}
	
		$current_network = get_network();
	
		if ( ! isset( $current_network->id ) ) {
			return get_main_network_id();
		}
	
		return absint( $current_network->id );
	}
endif;

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'wp_convert_hr_to_bytes' ) ) :
	function wp_convert_hr_to_bytes( $value ) {
		$value = strtolower( trim( $value ) );
		$bytes = (int) $value;
	
		if ( str_contains( $value, 'g' ) ) {
			$bytes *= GB_IN_BYTES;
		} elseif ( str_contains( $value, 'm' ) ) {
			$bytes *= MB_IN_BYTES;
		} elseif ( str_contains( $value, 'k' ) ) {
			$bytes *= KB_IN_BYTES;
		}
	
		// Deal with large (float) values which run into the maximum integer size.
		return min( $bytes, PHP_INT_MAX );
	}
endif;

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'wp_is_ini_value_changeable' ) ) :
	function wp_is_ini_value_changeable( $setting ) {
		static $ini_all;
	
		if ( ! isset( $ini_all ) ) {
			$ini_all = false;
			// Sometimes `ini_get_all()` is disabled via the `disable_functions` option for "security purposes".
			if ( function_exists( 'ini_get_all' ) ) {
				$ini_all = ini_get_all();
			}
		}
	
		// Bit operator to workaround https://bugs.php.net/bug.php?id=44936 which changes access level to 63 in PHP 5.2.6 - 5.2.17.
		if ( isset( $ini_all[ $setting ]['access'] )
			&& ( INI_ALL === ( $ini_all[ $setting ]['access'] & 7 ) || INI_USER === ( $ini_all[ $setting ]['access'] & 7 ) )
		) {
			return true;
		}
	
		// If we were unable to retrieve the details, fail gracefully to assume it's changeable.
		if ( ! is_array( $ini_all ) ) {
			return true;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'is_wp_error' ) ) :
	function is_wp_error( $thing ) {
		$is_wp_error = ( $thing instanceof WP_Error );
	
		if ( $is_wp_error ) {
			/**
			 * Fires when `is_wp_error()` is called and its parameter is an instance of `WP_Error`.
			 *
			 * @since 5.6.0
			 *
			 * @param WP_Error $thing The error object passed to `is_wp_error()`.
			 */
			do_action( 'is_wp_error_instance', $thing );
		}
	
		return $is_wp_error;
	}
endif;

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'timer_float' ) ) :
	function timer_float() {
		return microtime( true ) - $_SERVER['REQUEST_TIME_FLOAT'];
	}
endif;

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'timer_start' ) ) :
	function timer_start() {
		global $timestart;
	
		$timestart = microtime( true );
	
		return true;
	}
endif;

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'timer_stop' ) ) :
	function timer_stop( $display = 0, $precision = 3 ) {
		global $timestart, $timeend;
	
		$timeend   = microtime( true );
		$timetotal = $timeend - $timestart;
	
		if ( function_exists( 'number_format_i18n' ) ) {
			$r = number_format_i18n( $timetotal, $precision );
		} else {
			$r = number_format( $timetotal, $precision );
		}
	
		if ( $display ) {
			echo $r;
		}
	
		return $r;
	}
endif;

