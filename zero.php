<?php

/// Runtime extra constants - that needed for some functions to work
// `get_option( 'blog_charset' )` will be replaced with this constant in the code
defined( 'WPCOPY__OPTION_BLOG_CHARSET' ) || define( 'WPCOPY__OPTION_BLOG_CHARSET', 'UTF-8' );

/// Include files
$files = [
	...glob( __DIR__ . '/functions/*.php' ),
	...glob( __DIR__ . '/functions/wp-includes/*.php' ),
	...glob( __DIR__ . '/classes/*.php' ),
];
foreach( $files as $file ){
	require_once $file;
}

/// Set base constants
wp_initial_constants();
