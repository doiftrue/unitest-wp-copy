<?php

return [
	'_wp_translate_php_url_constant_to_key' => '4.7.0',
	'_get_component_from_parsed_url_array'  => '4.7.0',
	'wp_remote_retrieve_cookies'            => '4.4.0',
	'wp_remote_retrieve_cookie'             => '4.4.0',
	'wp_remote_retrieve_cookie_value'       => '4.4.0',
	'wp_parse_url'                          => '4.4.0',
	'get_allowed_http_origins'              => '3.4.0',
	'is_allowed_http_origin'                => '3.4.0',
	'get_http_origin'                       => '3.4.0 mockable',
	'wp_http_validate_url'                  => '3.5.2',
	'wp_remote_retrieve_headers'            => '2.7.0',
	'wp_remote_retrieve_header'             => '2.7.0',
	'wp_remote_retrieve_response_code'      => '2.7.0',
	'wp_remote_retrieve_response_message'   => '2.7.0',
	'wp_remote_retrieve_body'               => '2.7.0',
];

/*
Not suitable in isolated PHPUnit env:

_wp_http_get_object                  // why: requires WP_HTTP class stack/runtime.
allowed_http_request_hosts           // why: HTTP policy/runtime dependency.
ms_allowed_http_request_hosts        // why: multisite runtime dependency.
send_origin_headers                  // why: sends headers/output side effects.
wp_http_supports                     // why: transport/runtime dependency.
wp_remote_get                        // why: network I/O dependency.
wp_remote_head                       // why: network I/O dependency.
wp_remote_post                       // why: network I/O dependency.
wp_remote_request                    // why: network I/O dependency.
wp_safe_remote_get                   // why: network I/O dependency.
wp_safe_remote_head                  // why: network I/O dependency.
wp_safe_remote_post                  // why: network I/O dependency.
wp_safe_remote_request               // why: network I/O dependency.
*/
