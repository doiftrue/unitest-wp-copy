<?php

require_once __DIR__ . '/src/Updater.php';
require_once __DIR__ . '/src/Parser_Helpers.php';

$up = new Updater(
	dirname( __DIR__ ),
	dirname( __DIR__ ) . '/vendor/wordpress/wordpress'
);
$up->setup();
$up->run();
