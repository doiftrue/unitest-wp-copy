<?php

return [
	'wp_parse_url'                          => '4.4.0',
	'_wp_translate_php_url_constant_to_key' => '4.7.0',
	'_get_component_from_parsed_url_array'  => '4.7.0',
	'wp_http_validate_url'                  => '3.5.2',
];

/*
Not suitable in isolated PHPUnit env (network/CORS/runtime coupling):

_wp_http_get_object                  // why: requires WP_HTTP class stack/runtime.
allowed_http_request_hosts           // why: HTTP policy/runtime dependency.
get_allowed_http_origins             // why: CORS/runtime dependency.
get_http_origin                      // why: request header runtime dependency.
is_allowed_http_origin               // why: CORS/runtime dependency.
ms_allowed_http_request_hosts        // why: multisite runtime dependency.
send_origin_headers                  // why: sends headers/output side effects.
wp_http_supports                     // why: transport/runtime dependency.
wp_remote_get                        // why: network I/O dependency.
wp_remote_head                       // why: network I/O dependency.
wp_remote_post                       // why: network I/O dependency.
wp_remote_request                    // why: network I/O dependency.
wp_remote_retrieve_body              // why: requires WP_HTTP response objects/runtime.
wp_remote_retrieve_cookie            // why: requires WP_HTTP response objects/runtime.
wp_remote_retrieve_cookie_value      // why: requires WP_HTTP response objects/runtime.
wp_remote_retrieve_cookies           // why: requires WP_HTTP response objects/runtime.
wp_remote_retrieve_header            // why: requires WP_HTTP response objects/runtime.
wp_remote_retrieve_headers           // why: requires WP_HTTP response objects/runtime.
wp_remote_retrieve_response_code     // why: requires WP_HTTP response objects/runtime.
wp_remote_retrieve_response_message  // why: requires WP_HTTP response objects/runtime.
wp_safe_remote_get                   // why: network I/O dependency.
wp_safe_remote_head                  // why: network I/O dependency.
wp_safe_remote_post                  // why: network I/O dependency.
wp_safe_remote_request               // why: network I/O dependency.
*/
