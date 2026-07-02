<?php

// ------------------auto-generated---------------------

// wp-includes/pluggable.php (WP 6.8.5)
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

