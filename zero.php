<?php

/// Include files

require_once __DIR__ . '/constants.php';

$files = [
	...glob( __DIR__ . '/copy/functions/*.php' ),
	...glob( __DIR__ . '/copy/functions/wp-includes/*.php' ),
	...glob( __DIR__ . '/copy/classes/*.php' ),
	...glob( __DIR__ . '/copy/init-parts/wp-includes/*.php' ), /// INIT
];
foreach( $files as $file ){
	require_once $file;
}

/// INIT

wp_initial_constants();
wp_plugin_directory_constants();
global $wp_plugin_paths;
$wp_plugin_paths || $wp_plugin_paths = [];
