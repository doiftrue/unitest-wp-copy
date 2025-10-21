<?php

/// INCLUDE

$files = [
	...glob( __DIR__ . '/copy/functions/*.php' ),
	...glob( __DIR__ . '/copy/functions/wp-includes/*.php' ),
	...glob( __DIR__ . '/copy/classes/*.php' ),
	__DIR__ . '/copy/mocks.php',
];
foreach( $files as $file ){
	require_once $file;
}

/// INIT WP ENV

require_once __DIR__ . '/src/stub_wp_options.php';
require_once __DIR__ . '/src/constants.php';
foreach ( glob( __DIR__ . '/copy/init-parts/wp-includes/*.php' ) as $init_file ) {
	require_once $init_file;
}

wp_initial_constants();

wp_plugin_directory_constants();
global $wp_plugin_paths;
$wp_plugin_paths || $wp_plugin_paths = [];

wp_cookie_constants();
wp_ssl_constants();
wp_functionality_constants();

smilies_init();

global $shortcode_tags;
$shortcode_tags = [];

global $wp_locale;
$wp_locale = new WP_Locale();
