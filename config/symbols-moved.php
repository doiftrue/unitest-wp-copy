<?php

return [
	'functions' => [
		'absint' => [
			'moved_in' => '6.7',
			'from'    => 'wp-includes/functions.php',
			'to'      => 'wp-includes/load.php',
		],
		'addslashes_gpc' => [
			'moved_in' => '7.0',
			'from'    => 'wp-includes/formatting.php',
			'to'      => 'wp-includes/deprecated.php',
		],
		'register_block_pattern_category' => [
			'moved_in' => '7.0',
			'from'    => 'wp-includes/class-wp-block-pattern-categories-registry.php',
			'to'      => 'wp-includes/block-patterns.php',
		],
		'unregister_block_pattern_category' => [
			'moved_in' => '7.0',
			'from'    => 'wp-includes/class-wp-block-pattern-categories-registry.php',
			'to'      => 'wp-includes/block-patterns.php',
		],
		'wp_sanitize_script_attributes' => [
			'moved_in' => '7.0',
			'from'    => 'wp-includes/script-loader.php',
			'to'      => 'wp-includes/deprecated.php',
		],
	],
];
