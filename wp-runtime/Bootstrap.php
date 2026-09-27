<?php
/**
 * Legacy PSR-4 compatibility entry point since runtime 5.0 version.
 *
 * Composer maps "Unitest_WP_Copy\Bootstrap" to this file. The Bootstrap class is
 * an alias of "WP_Runtime", so this file keeps "Bootstrap::init()" available for
 * existing consumers.
 *
 * New code must use "WP_Runtime::boot()".
 */

require_once __DIR__ . '/WP_Runtime.php';
