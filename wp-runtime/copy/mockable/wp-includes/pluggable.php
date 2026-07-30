<?php

// ------------------auto-generated---------------------

// wp-includes/pluggable.php (WP 6.7.5)
if( ! function_exists( 'wp_rand' ) ) :
		function wp_rand( $min = null, $max = null ) {
			if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
				return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
			}
	
			global $rnd_value;
	
			/*
			 * Some misconfigured 32-bit environments (Entropy PHP, for example)
			 * truncate integers larger than PHP_INT_MAX to PHP_INT_MAX rather than overflowing them to floats.
			 */
			$max_random_number = 3000000000 === 2147483647 ? (float) '4294967295' : 4294967295; // 4294967295 = 0xffffffff
	
			if ( null === $min ) {
				$min = 0;
			}
	
			if ( null === $max ) {
				$max = $max_random_number;
			}
	
			// We only handle ints, floats are truncated to their integer value.
			$min = (int) $min;
			$max = (int) $max;
	
			// Use PHP's CSPRNG, or a compatible method.
			static $use_random_int_functionality = true;
			if ( $use_random_int_functionality ) {
				try {
					// wp_rand() can accept arguments in either order, PHP cannot.
					$_max = max( $min, $max );
					$_min = min( $min, $max );
					$val  = random_int( $_min, $_max );
					if ( false !== $val ) {
						return absint( $val );
					} else {
						$use_random_int_functionality = false;
					}
				} catch ( Error $e ) {
					$use_random_int_functionality = false;
				} catch ( Exception $e ) {
					$use_random_int_functionality = false;
				}
			}
	
			/*
			 * Reset $rnd_value after 14 uses.
			 * 32 (md5) + 40 (sha1) + 40 (sha1) / 8 = 14 random numbers from $rnd_value.
			 */
			if ( strlen( $rnd_value ) < 8 ) {
				if ( defined( 'WP_SETUP_CONFIG' ) ) {
					static $seed = '';
				} else {
					$seed = get_transient( 'random_seed' );
				}
				$rnd_value  = md5( uniqid( microtime() . mt_rand(), true ) . $seed );
				$rnd_value .= sha1( $rnd_value );
				$rnd_value .= sha1( $rnd_value . $seed );
				$seed       = md5( $seed . $rnd_value );
				if ( ! defined( 'WP_SETUP_CONFIG' ) && ! defined( 'WP_INSTALLING' ) ) {
					set_transient( 'random_seed', $seed );
				}
			}
	
			// Take the first 8 digits for our value.
			$value = substr( $rnd_value, 0, 8 );
	
			// Strip the first eight, leaving the remainder for the next call to wp_rand().
			$rnd_value = substr( $rnd_value, 8 );
	
			$value = abs( hexdec( $value ) );
	
			// Reduce the value to be within the min - max range.
			$value = $min + ( $max - $min + 1 ) * $value / ( $max_random_number + 1 );
	
			return abs( (int) $value );
		}
endif;

// wp-includes/pluggable.php (WP 6.7.5)
if( ! function_exists( 'wp_nonce_tick' ) ) :
		function wp_nonce_tick( $action = -1 ) {
			if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
				return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
			}
	
			/**
			 * Filters the lifespan of nonces in seconds.
			 *
			 * @since 2.5.0
			 * @since 6.1.0 Added `$action` argument to allow for more targeted filters.
			 *
			 * @param int        $lifespan Lifespan of nonces in seconds. Default 86,400 seconds, or one day.
			 * @param string|int $action   The nonce action, or -1 if none was provided.
			 */
			$nonce_life = apply_filters( 'nonce_life', DAY_IN_SECONDS, $action );
	
			return ceil( time() / ( $nonce_life / 2 ) );
		}
endif;

