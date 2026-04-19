<?php

require_once __DIR__ . '/functions.php';

$base_dir = dirname( __DIR__ );
wp_version( "$base_dir/wp-core" );
require_once "$base_dir/zero.php";
