<?php

// ------------------auto-generated---------------------

// wp-includes/error-protection.php (WP 6.7.5)
if( ! function_exists( 'wp_get_extension_error_description' ) ) :
	function wp_get_extension_error_description( $error ) {
		$constants   = get_defined_constants( true );
		$constants   = isset( $constants['Core'] ) ? $constants['Core'] : $constants['internal'];
		$core_errors = array();
	
		foreach ( $constants as $constant => $value ) {
			if ( str_starts_with( $constant, 'E_' ) ) {
				$core_errors[ $value ] = $constant;
			}
		}
	
		if ( isset( $core_errors[ $error['type'] ] ) ) {
			$error['type'] = $core_errors[ $error['type'] ];
		}
	
		/* translators: 1: Error type, 2: Error line number, 3: Error file name, 4: Error message. */
		$error_message = __( 'An error of type %1$s was caused in line %2$s of the file %3$s. Error message: %4$s' );
	
		return sprintf(
			$error_message,
			"<code>{$error['type']}</code>",
			"<code>{$error['line']}</code>",
			"<code>{$error['file']}</code>",
			"<code>{$error['message']}</code>"
		);
	}
endif;

// wp-includes/error-protection.php (WP 6.7.5)
if( ! function_exists( 'wp_is_fatal_error_handler_enabled' ) ) :
	function wp_is_fatal_error_handler_enabled() {
		$enabled = ! defined( 'WP_DISABLE_FATAL_ERROR_HANDLER' ) || ! WP_DISABLE_FATAL_ERROR_HANDLER;
	
		/**
		 * Filters whether the fatal error handler is enabled.
		 *
		 * **Important:** This filter runs before it can be used by plugins. It cannot
		 * be used by plugins, mu-plugins, or themes. To use this filter you must define
		 * a `$wp_filter` global before WordPress loads, usually in `wp-config.php`.
		 *
		 * Example:
		 *
		 *     $GLOBALS['wp_filter'] = array(
		 *         'wp_fatal_error_handler_enabled' => array(
		 *             10 => array(
		 *                 array(
		 *                     'accepted_args' => 0,
		 *                     'function'      => function() {
		 *                         return false;
		 *                     },
		 *                 ),
		 *             ),
		 *         ),
		 *     );
		 *
		 * Alternatively you can use the `WP_DISABLE_FATAL_ERROR_HANDLER` constant.
		 *
		 * @since 5.2.0
		 *
		 * @param bool $enabled True if the fatal error handler is enabled, false otherwise.
		 */
		return apply_filters( 'wp_fatal_error_handler_enabled', $enabled );
	}
endif;

