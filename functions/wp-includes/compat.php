<?php
/**
 * Copy of WP functions to they work as is.
 * Here is only functions that not depends on DB or other external libs.
 */

// ------------------auto-generated---------------------

// wp-includes/compat.php (WP 6.8.1)
function _is_utf8_charset( $charset_slug ) {
	if ( ! is_string( $charset_slug ) ) {
		return false;
	}

	return (
		0 === strcasecmp( 'UTF-8', $charset_slug ) ||
		0 === strcasecmp( 'UTF8', $charset_slug )
	);
}

