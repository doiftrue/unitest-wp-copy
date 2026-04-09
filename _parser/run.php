<?php

namespace Parser;

require_once __DIR__ . '/autoload.php';

$up = new Updater();
$up->setup();
$up->run();
