<?php

define( 'WP_CORE_DIR', dirname( __DIR__, 3 ) . '/public_html/core' );
define( 'WP_COPY_DIR', dirname( __DIR__ ) );
define( 'CONFIG_FILE', WP_COPY_DIR . '/config.php' );

require_once __DIR__ . '/src/Updater.php';
require_once __DIR__ . '/src/Parser_Helpers.php';

$up = new Updater();
$up->setup();
$up->run();
