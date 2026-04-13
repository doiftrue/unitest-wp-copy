<?php

/**
 * Static class methods copied as plain functions (experimental).
 *
 * Rule:
 * - source method:  ClassName::methodName()
 * - copied function: ClassName__methodName()
 *
 * Config format:
 * - 'path/to/class-file.php' => [
 *     'class'   => 'ClassName',
 *     'methods' => [ 'methodName' => '' ],
 *   ]
 *
 * NOTE:
 * This is intentionally for utility-like static methods only, when:
 * - whole class cannot be copied into project runtime, and
 * - a small isolated static method is still needed as dependency.
 */
return [
	'wp-includes/class-wp-http.php' => [
		'class'   => 'WP_Http',
		'methods' => [
			'make_absolute_url' => '',
			'is_ip_address' => '',
		],
	],
];
