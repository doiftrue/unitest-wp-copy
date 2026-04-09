<?php

foreach( glob( __DIR__ . '/src/*.php' ) as $path ) {
	require_once $path;
}

$up = new Updater();
$up->setup();
$up->run();
