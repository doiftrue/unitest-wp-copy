<?php
/**
 * CLI arguments passed to the parser runner.
 *
 * Usage:
 * - php parser/run.php
 * - php parser/run.php --verbose | --v
 *
 * @var string[] $argv Command-line arguments. The first item is this script path.
 */

namespace Parser;

require_once __DIR__ . '/autoload.php';

$verbose = in_array( '--verbose', $argv, true ) || in_array( '-v', $argv, true );

$up = new Updater( $verbose );
$up->setup();
$up->run();
