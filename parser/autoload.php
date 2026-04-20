<?php
/**
 * PSR-4 compatible autoload
 */

namespace Parser;

require_once __DIR__ . '/src/functions.php';

spl_autoload_register( static function( $class ) {
	if( ! str_starts_with( $class, __NAMESPACE__ . '\\' ) ){
		return;
	}

	$class = str_replace( '\\', '/', $class );

	require_once str_replace( __NAMESPACE__ . '/', __DIR__ . '/src/', "$class.php" );
} );
