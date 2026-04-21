<?php

define( 'TESTS_ROOT_DIR', __DIR__ );
define( 'PROJECT_ROOT_DIR', dirname( __DIR__ ) );
define( 'PROJECT_TMP_DIR', dirname( __DIR__ ) . '/tmp' );

require_once __DIR__ . '/functions.php';

$wp_line = getenv( 'WP_LINE' ) ?: 'Current';
$base_dir = PROJECT_ROOT_DIR . "/worktrees/wp-$wp_line";
$base_dir = is_dir( $base_dir ) ? $base_dir : PROJECT_ROOT_DIR;

wp_version( "$base_dir/wp-core" );
require_once "$base_dir/zero.php";

$cian = ["\033[36m", "\033[0m"];
echo "$cian[0]Run for WP: $wp_line$cian[1]\n";
