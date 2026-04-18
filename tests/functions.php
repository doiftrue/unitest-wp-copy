<?php

function config(): \Parser\Config {
	static $c;
	if( ! $c ){
		require_once dirname( __DIR__ ) . '/_parser/src/Config.php';
		$c = new \Parser\Config();
	}
	return $c;
}

/**
 * @param string $wp_version_compare Eg: '< 6.8.0'
 *
 * @return string wp_version if condition is true or empty string.
 */
function wp_version_compare( string $wp_version_compare ): string{
	[ $operator, $version ] = explode( ' ', $wp_version_compare, 2 );
	return version_compare( config()->wp_version, $version, $operator ) ? config()->wp_version : '';
}
