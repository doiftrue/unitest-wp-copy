<?php

return [
	// Pure registry utility for additional REST fields.
	'register_rest_field' => '4.7.0',
	// Pure prefix accessor.
	'rest_get_url_prefix' => '4.4.0',
	// Pure recursive array helper.
	'_rest_array_intersect_key_recursive' => '5.3.0',
	// Pure field-name inclusion helper.
	'rest_is_field_included' => '5.3.0',
	// Simple filtered static list.
	'rest_get_avatar_sizes' => '4.7.0',
	// Pure date/color parsing helpers.
	'rest_parse_date'        => '4.4.0',
	'rest_parse_hex_color'   => '5.4.0',
	'rest_get_date_with_gmt' => '4.4.0',
	// Request-arg schema adapters (work with any object exposing get_attributes()).
	'rest_validate_request_arg' => '4.7.0',
	'rest_sanitize_request_arg' => '4.7.0',
	'rest_parse_request_arg'    => '4.7.0',
	// Lightweight deprecation/wrong-usage header emitters.
	'rest_handle_deprecated_function' => '4.4.0 mockable',
	'rest_handle_deprecated_argument' => '4.4.0 mockable',
	'rest_handle_doing_it_wrong'      => '5.5.0 mockable',
	// Runtime-safe IP validator (IPv6 check is adapted via parser replacer).
	'rest_is_ip_address' => '4.7.0',
	// Primitive type sanitizers/checkers.
	'rest_sanitize_boolean'                      => '4.7.0',
	'rest_is_boolean'                            => '4.7.0',
	'rest_is_integer'                            => '5.5.0',
	'rest_is_array'                              => '5.5.0',
	'rest_sanitize_array'                        => '5.5.0',
	'rest_is_object'                             => '5.5.0',
	'rest_sanitize_object'                       => '5.5.0',
	'rest_get_best_type_for_value'               => '5.5.0',
	'rest_handle_multi_type_schema'              => '5.5.0',
	'rest_validate_array_contains_unique_items'  => '5.5.0',
	'rest_stabilize_value'                       => '5.5.0',
	'rest_validate_json_schema_pattern'          => '5.6.0',
	'rest_find_matching_pattern_property_schema' => '5.6.0',
	// Schema combining-operation helpers.
	'rest_format_combining_operation_error' => '5.6.0',
	'rest_get_combining_operation_error'    => '5.6.0',
	'rest_find_any_matching_schema'         => '5.6.0',
	'rest_find_one_matching_schema'         => '5.6.0',
	'rest_are_values_equal'                 => '5.7.0',
	'rest_validate_enum'                    => '5.7.0',
	// JSON schema keywords + validation/sanitization chain.
	'rest_get_allowed_schema_keywords'        => '5.6.0',
	'rest_validate_value_from_schema'         => '4.7.0',
	'rest_validate_null_value_from_schema'    => '5.7.0',
	'rest_validate_boolean_value_from_schema' => '5.7.0',
	'rest_validate_object_value_from_schema'  => '5.7.0',
	'rest_validate_array_value_from_schema'   => '5.7.0',
	'rest_validate_number_value_from_schema'  => '5.7.0',
	'rest_validate_string_value_from_schema'  => '5.7.0',
	'rest_validate_integer_value_from_schema' => '5.7.0',
	'rest_sanitize_value_from_schema'         => '4.7.0',
	// Utility helpers used by preload/context filtering logic.
	'rest_parse_embed_param'          => '5.4.0',
	'rest_filter_response_by_context' => '5.5.0',
	// Required by register_theme_feature() for show_in_rest schemas.
	'rest_default_additional_properties_to_false' => '5.5.0',

];

/*
Not suitable in isolated PHPUnit env (REST server/full runtime coupling):

register_rest_route                       // why: depends on rest_get_server() + WP_REST_Server runtime.
rest_api_init                             // why: depends on full WP query/rewrite runtime.
rest_api_register_rewrites                // why: depends on add_rewrite_rule() and WP_Rewrite globals.
rest_api_default_filters                  // why: runtime hook wiring for full REST serving lifecycle.
create_initial_rest_routes                // why: requires many WP_REST_* controllers/registries.
rest_api_loaded                           // why: requires live server request dispatch.
get_rest_url                              // why: depends on get_blog_option() and permalink runtime.
rest_url                                  // why: wrapper around get_rest_url() chain.
rest_do_request                           // why: depends on WP_REST_Request / WP_REST_Server.
rest_get_server                           // why: instantiates WP_REST_Server (class not copied here).
rest_ensure_request                       // why: constructs WP_REST_Request.
rest_ensure_response                      // why: depends on WP_REST_Response / WP_HTTP_Response classes.
rest_send_cors_headers                    // why: depends on get_http_origin() (not included in this project).
rest_handle_options_request               // why: constructs WP_REST_Response and depends on REST server route objects.
rest_send_allow_header                    // why: depends on WP_REST_Response/WP_REST_Server interfaces.
rest_filter_response_fields               // why: depends on WP_REST_Response methods.
rest_output_rsd                           // why: depends on get_rest_url() chain.
rest_output_link_wp_head                  // why: depends on get_rest_url() and queried-resource route runtime.
rest_output_link_header                   // why: depends on get_rest_url() and queried-resource route runtime.
rest_cookie_check_errors                  // why: cookie auth/runtime globals chain.
rest_cookie_collect_status                // why: cookie auth/runtime globals chain.
rest_application_password_collect_status  // why: depends on WP_Application_Passwords runtime.
rest_get_authenticated_app_password       // why: depends on WP_Application_Passwords runtime.
rest_application_password_check_errors    // why: depends on WP_Application_Passwords runtime.
rest_add_application_passwords_to_index   // why: depends on WP_REST_Response object + app-password runtime.
rest_get_avatar_urls                      // why: depends on get_avatar_url() chain not included.
rest_authorization_required_code          // why: depends on is_user_logged_in() which is not available in this env.
rest_preload_api_request                  // why: depends on WP_REST_Request / WP_REST_Server / response objects.
rest_get_route_for_post                   // why: depends on WP_Post model + post-type runtime.
rest_get_route_for_post_type_items        // why: depends on get_post_type_object() runtime registry.
rest_get_route_for_term                   // why: depends on WP_Term model + taxonomy runtime.
rest_get_route_for_taxonomy_items         // why: depends on get_taxonomy() runtime registry.
rest_get_queried_resource_route           // why: depends on global query conditionals.
rest_get_endpoint_args_for_schema         // why: default arg uses WP_REST_Server::CREATABLE.
rest_convert_error_to_response            // why: returns WP_REST_Response (class not copied here).
wp_is_rest_endpoint                       // why: depends on wp_is_serving_rest_request() and WP_REST_Server runtime.
*/
