<?php

foreach( glob( __DIR__ . '/src/*.php' ) as $path ) {
	require_once $path;
}

$up = new Updater(
	dest_dir: dirname( __DIR__ ) . '/copy',
	wp_core_dir: dirname( __DIR__ ) . '/vendor/wordpress/wordpress',
	config_funcs: include __DIR__ . '/config-funcs.php',
	config_classes: include __DIR__ . '/config-classes.php',
);
$up->setup();
$up->run();
