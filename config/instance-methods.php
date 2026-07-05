<?php

/**
 * Instance methods copied into traits for use by runtime-adapted classes.
 *
 * The target class must provide all properties and excluded method dependencies
 * required by the copied methods.
 *
 * Config format:
 * - 'path/to/class-file.php' => [
 *     'class'   => 'SourceClassName',
 *     'trait'   => 'GeneratedTraitName',
 *     'methods' => [ 'methodName' => '<since-version>' ],
 *   ]
 */
return [
	'wp-includes/class-wpdb.php' => [
		'class'   => 'wpdb',
		'trait'   => 'wpdb__Copied_Methods',
		'methods' => [
			'_escape_identifier_value'  => '6.2.0',
			'prepare'                   => '2.3.0',
			'esc_like'                  => '4.0.0',
			'placeholder_escape'        => '4.8.3',
			'add_placeholder_escape'    => '4.8.3',
			'remove_placeholder_escape' => '4.8.3',
		],
	],
];
