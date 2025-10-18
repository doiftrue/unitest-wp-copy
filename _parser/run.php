<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/src/Updater.php';
require_once __DIR__ . '/src/Parser_Helpers.php';

$up = new Updater( dirname( __DIR__ ) );
$up->setup();
$up->run();
