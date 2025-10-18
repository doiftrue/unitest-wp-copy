<?php

/// Include files

require_once __DIR__ . '/constants.php';

$files = [
	...glob( __DIR__ . '/copy/functions/*.php' ),
	...glob( __DIR__ . '/copy/functions/wp-includes/*.php' ),
	...glob( __DIR__ . '/copy/classes/*.php' ),
];
foreach( $files as $file ){
	require_once $file;
}

/// INIT

require_once __DIR__ . '/mocks.php';

foreach ( glob( __DIR__ . '/copy/init-parts/wp-includes/*.php' ) as $init_file ) {
	require_once $init_file;
}

wp_initial_constants();

wp_plugin_directory_constants();
global $wp_plugin_paths;
$wp_plugin_paths || $wp_plugin_paths = [];

smilies_init();

global $shortcode_tags;
$shortcode_tags = [];
