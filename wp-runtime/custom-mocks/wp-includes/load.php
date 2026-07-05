<?php
/**
 * Runtime-adapted WordPress bootstrap functions from wp-includes/load.php.
 */

/**
 * No-op replacement for the WordPress early translation bootstrap.
 *
 * The isolated runtime uses source-string translation fallbacks and therefore
 * does not need to load translation files before diagnostic messages.
 */
if ( ! function_exists( 'wp_load_translations_early' ) ) :
	function wp_load_translations_early() {
		// Intentionally left empty.
	}
endif;
