<?php
/**
 * Runtime-adapted WordPress capability functions.
 * Supports WP_Mock unit testing implementation.
 */

use Unitest_WP_Copy\WP_Mock_Utils;

/**
 * Runtime adaptation of current_user_can() without a user or roles database.
 */
if ( ! function_exists( 'current_user_can' ) ) :
	function current_user_can( $capability, ...$args ) {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		return false;
	}
endif;
