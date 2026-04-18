<?php

/**
 * First call will set up the wp_version and cache it for subsequent calls.
 */
function wp_version( string $wp_dir = '' ): string {
	static $wp_version;
	if( ! $wp_version ){
		// INFO: declare $wp_version var
		require_once "$wp_dir/wp-includes/version.php";
	}
	return $wp_version;
}

/**
 * @param string $to_compare  Eg: '< 6.8.0'
 *
 * @return string wp_version if condition is true or empty string.
 */
function wp_version_compare( string $to_compare ): string {
	[ $operator, $version ] = explode( ' ', $to_compare, 2 );
	return version_compare( wp_version(), $version, $operator ) ? wp_version() : '';
}
