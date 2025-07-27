<?php

/// Runtime internal extra constants
const WPCOPY__OPTION_BLOG_CHARSET = 'UTF-8';

/// Include files
$files = [
	...glob( __DIR__ . '/functions/*.php' ),
	...glob( __DIR__ . '/functions/wp-includes/*.php' ),
	...glob( __DIR__ . '/classes/*.php' ),
];
foreach( $files as $file ){
	require_once $file;
}

//require_once __DIR__ . '/constants.php';

/// Set base constants
wp_initial_constants();
