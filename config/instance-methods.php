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
 *     'imports' => [ 'GlobalDependencyClass' ],
 *     'methods' => [ 'methodName' => '<since-version>' ],
 *   ]
 */
return [
	'wp-includes/class-wpdb.php' => [
		'class'   => 'wpdb',
		'trait'   => 'wpdb__Copied_Methods',
		'methods' => [
			'_escape_identifier_value'  => '6.2.0',
			'esc_like'                  => '4.0.0',
			'placeholder_escape'        => '4.8.3',
			'add_placeholder_escape'    => '4.8.3',
			'remove_placeholder_escape' => '4.8.3',
			'_escape'                   => '2.8.0',
			'prepare'                   => '2.3.0',
		],
	],
	'wp-includes/rest-api/class-wp-rest-server.php' => [
		'class'   => 'WP_REST_Server',
		'trait'   => 'WP_REST_Server__Copied_Methods',
		'imports' => [
			'WP_Error',
			'WP_REST_Request',
			'WP_REST_Response',
		],
		'methods' => [
			'get_target_hints_for_link'  => '6.7.0',
			'is_dispatching'             => '6.5.0',
			'match_request_to_handler'   => '5.6.0',
			'respond_to_request'         => '5.6.0',
			'get_max_batch_size'         => '5.6.0',
			'serve_batch_request_v1'     => '5.6.0',
			'get_compact_response_links' => '4.5.0',
			'__construct'                => '4.4.0',
			'check_authentication'       => '4.4.0',
			'error_to_response'          => '4.4.0',
			'response_to_data'           => '4.4.0',
			'get_response_links'         => '4.4.0',
			'embed_links'                => '4.4.0',
			'envelope_response'          => '4.4.0',
			'register_route'             => '4.4.0',
			'get_routes'                 => '4.4.0',
			'get_namespaces'             => '4.4.0',
			'get_route_options'          => '4.4.0',
			'dispatch'                   => '4.4.0',
			'get_namespace_index'        => '4.4.0',
			'get_data_for_routes'        => '4.4.0',
			'get_data_for_route'         => '4.4.0',
			'send_headers'               => '4.4.0',
			'get_headers'                => '4.4.0',
		],
	],
];
