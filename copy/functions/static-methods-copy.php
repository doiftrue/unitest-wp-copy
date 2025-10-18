<?php
// TODO add this to parser

// wp-includes/class-wp-http.php  (WP 6.8.3)
// WP_Http::make_absolute_url()
function WP_Http__make_absolute_url( $maybe_relative_path, $url ) {
	if ( empty( $url ) ) {
		return $maybe_relative_path;
	}

	$url_parts = wp_parse_url( $url );
	if ( ! $url_parts ) {
		return $maybe_relative_path;
	}

	$relative_url_parts = wp_parse_url( $maybe_relative_path );
	if ( ! $relative_url_parts ) {
		return $maybe_relative_path;
	}

	// Check for a scheme on the 'relative' URL.
	if ( ! empty( $relative_url_parts['scheme'] ) ) {
		return $maybe_relative_path;
	}

	$absolute_path = $url_parts['scheme'] . '://';

	// Schemeless URLs will make it this far, so we check for a host in the relative URL
	// and convert it to a protocol-URL.
	if ( isset( $relative_url_parts['host'] ) ) {
		$absolute_path .= $relative_url_parts['host'];
		if ( isset( $relative_url_parts['port'] ) ) {
			$absolute_path .= ':' . $relative_url_parts['port'];
		}
	} else {
		$absolute_path .= $url_parts['host'];
		if ( isset( $url_parts['port'] ) ) {
			$absolute_path .= ':' . $url_parts['port'];
		}
	}

	// Start off with the absolute URL path.
	$path = ! empty( $url_parts['path'] ) ? $url_parts['path'] : '/';

	// If it's a root-relative path, then great.
	if ( ! empty( $relative_url_parts['path'] ) && '/' === $relative_url_parts['path'][0] ) {
		$path = $relative_url_parts['path'];

		// Else it's a relative path.
	} elseif ( ! empty( $relative_url_parts['path'] ) ) {
		// Strip off any file components from the absolute path.
		$path = substr( $path, 0, strrpos( $path, '/' ) + 1 );

		// Build the new path.
		$path .= $relative_url_parts['path'];

		// Strip all /path/../ out of the path.
		while ( strpos( $path, '../' ) > 1 ) {
			$path = preg_replace( '![^/]+/\.\./!', '', $path );
		}

		// Strip any final leading ../ from the path.
		$path = preg_replace( '!^/(\.\./)+!', '', $path );
	}

	// Add the query string.
	if ( ! empty( $relative_url_parts['query'] ) ) {
		$path .= '?' . $relative_url_parts['query'];
	}

	// Add the fragment.
	if ( ! empty( $relative_url_parts['fragment'] ) ) {
		$path .= '#' . $relative_url_parts['fragment'];
	}

	return $absolute_path . '/' . ltrim( $path, '/' );
}
