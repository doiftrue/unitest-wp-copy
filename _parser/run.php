<?php

require_once __DIR__ . '/src/Updater.php';
require_once __DIR__ . '/src/Parser_Helpers.php';

$up = new Updater(
	dirname( __DIR__ ) . '/copy/functions',
	dirname( __DIR__ ) . '/vendor/wordpress/wordpress',
	include dirname( __DIR__ ) . '/config.php'
);
$up->setup();
$up->run();
