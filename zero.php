<?php
/**
 * This file allow you to load the wp-runtime directly without composer autoloader.
 * Can be useful if you use this lib as submodule or copy-paste into your project,
 * and don't want to setup composer autoloading.
 */

require_once __DIR__ . '/wp-runtime/Bootstrap.php';
\Unitest_WP_Copy\Bootstrap::init();
