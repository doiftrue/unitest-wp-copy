<?php

// ------------------auto-generated---------------------

// wp-includes/ai-client.php (WP 7.0)
if( ! function_exists( 'wp_supports_ai' ) ) :
	function wp_supports_ai(): bool {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		// Return early if AI is disabled by the current environment.
		if ( defined( 'WP_AI_SUPPORT' ) && ! WP_AI_SUPPORT ) {
			return false;
		}
	
		/**
		 * Filters whether the current request can use AI.
		 *
		 * This allows plugins and 3rd-party code to disable AI features on a per-request basis, or to even override explicit
		 * preferences defined by the site owner.
		 *
		 * @since 7.0.0
		 *
		 * @param bool $is_enabled Whether AI is available. Default to true.
		 */
		return (bool) apply_filters( 'wp_supports_ai', true );
	}
endif;

