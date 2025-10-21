<?php

foreach( glob( __DIR__ . '/src/*.php' ) as $path ) {
	require_once $path;
}

$up = new Updater(
	dest_dir: dirname( __DIR__ ) . '/copy',
	wp_core_dir: dirname( __DIR__ ) . '/vendor/wordpress/wordpress',
	config_funcs: include dirname( __DIR__ ) . '/src/config-funcs.php',
	config_classes: include dirname( __DIR__ ) . '/src/config-classes.php',
);
$up->setup();
$up->run();
