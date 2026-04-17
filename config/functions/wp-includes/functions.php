<?php

return [
	'__return_empty_array'                   => '3.0.0',
	'__return_empty_string'                  => '3.7.0',
	'__return_false'                         => '3.0.0',
	'__return_null'                          => '3.4.0',
	'__return_true'                          => '3.0.0',
	'__return_zero'                          => '3.0.0',
	'_canonical_charset'                     => '3.6.0',
	'_cleanup_header_comment'                => '2.8.0',
	'_http_build_query'                      => '3.2.0',
	'_wp_array_get'                          => '5.6.0',
	'_wp_array_set'                          => '5.8.0',
	'_wp_json_convert_string'                => '4.1.0',
	'_wp_json_prepare_data'                  => '4.4.0',
	'_wp_json_sanity_check'                  => '4.1.0',
	'_wp_to_kebab_case'                      => '0.0.0',
	'add_query_arg'                          => '1.5.0',
	'bool_from_yn'                           => '1.0.0',
	'build_query'                            => '2.3.0',
	'get_allowed_mime_types'                 => '2.8.6',
	'get_file_data'                          => '2.9.0',
	'is_serialized'                          => '2.0.5',
	'is_serialized_string'                   => '2.0.5',
	'is_utf8_charset'                        => '6.6.0 mockable',
	'maybe_serialize'                        => '2.0.5',
	'maybe_unserialize'                      => '2.0.0',
	'mbstring_binary_safe_encoding'          => '3.7.0',
	'path_is_absolute'                       => '2.5.0',
	'path_join'                              => '2.5.0',
	'remove_query_arg'                       => '1.5.0',
	'reset_mbstring_encoding'                => '3.7.0',
	'validate_file'                          => '1.2.0',
	'wp_array_slice_assoc'                   => '3.1.0',
	'wp_check_filetype'                      => '2.0.4',
	'wp_check_jsonp_callback'                => '4.6.0',
	'wp_checkdate'                           => '3.5.0',
	'wp_debug_backtrace_summary'             => '3.4.0',
	'get_status_header_desc'                 => '2.3.0',
	'get_tag_regex'                          => '3.6.0',
	'human_readable_duration'                => '5.1.0',
	'wp_ext2type'                            => '2.5.0',
	'wp_filter_object_list'                  => '3.0.0',
	'wp_find_hierarchy_loop'                 => '3.1.0',
	'wp_find_hierarchy_loop_tortoise_hare'   => '3.1.0',
	'wp_fuzzy_number_match'                  => '5.3.0',
	'wp_generate_uuid4'                      => '4.7.0',
	'wp_get_default_extension_for_mime_type' => '5.8.1',
	'wp_get_ext_types'                       => '4.6.0',
	'wp_get_image_mime'                      => '4.7.1',
	'wp_get_mime_types'                      => '3.5.0',
	'wp_get_nocache_headers'                 => '2.8.0',
	'wp_is_heic_image_mime_type'             => '6.7.0',
	'wp_is_numeric_array'                    => '4.4.0',
	'wp_is_stream'                           => '3.5.0',
	'wp_is_uuid'                             => '4.9.0',
	'wp_json_encode'                         => '4.1.0',
	'wp_json_file_decode'                    => '5.9.0',
	'wp_list_filter'                         => '3.1.0',
	'wp_list_pluck'                          => '3.1.0',
	'wp_list_sort'                           => '4.7.0',
	'wp_normalize_path'                      => '3.9.0',
	'wp_parse_args'                          => '2.2.0',
	'wp_parse_id_list'                       => '3.0.0',
	'wp_parse_list'                          => '5.1.0',
	'wp_parse_slug_list'                     => '4.7.0',
	'wp_privacy_anonymize_data'              => '4.9.6',
	'wp_privacy_anonymize_ip'                => '4.9.6',
	'wp_recursive_ksort'                     => '6.0.0',
	'wp_unique_id'                           => '5.0.3',
	'wp_unique_id_from_values'               => '6.8.0',
	'wp_unique_prefixed_id'                  => '6.4.0',
	'wp_validate_boolean'                    => '4.0.0',
	'_deprecated_function'                   => '2.5.0 mockable',
	'wp_timezone'                            => '5.3.0',
	'wp_timezone_string'                     => '5.3.0 mockable',
	'current_datetime'                       => '5.3.0',
	'current_time'                           => '1.0.0 mockable',
	'date_i18n'                              => '0.71',
	'mysql2date'                             => '0.71',
	'mysql_to_rfc3339'                       => '4.4.0',
	'number_format_i18n'                     => '2.3.0',
	'size_format'                            => '2.3.0',
	'_deprecated_argument'                   => '3.0.0',
	'_doing_it_wrong'                        => '3.1.0',
	'wp_trigger_error'                       => '6.4.0 mockable',
	'smilies_init'                           => '2.2.0',
	'force_ssl_admin'                        => '2.6.0 mockable',
	'wp_suspend_cache_addition'              => '3.3.0 mockable',
	'wp_allowed_protocols'                   => '3.3.0',
	'wp_date'                                => '5.3.0',
	'_deprecated_hook'                       => '4.6.0', // note: need as deps
];

/*
Not suitable in isolated PHPUnit env (DB/filesystem/request/bootstrap runtime coupling):
is_php_version_compatible                    // why: better to leave mockable.
is_wp_version_compatible                     // why: better to leave mockable.
wp_fast_hash                                 // why: requires sodium.
wp_get_wp_version                            // why: requires full WP file tree via ABSPATH/WPINC.
wp_verify_fast_hash                          // why: not used in this project (legacy branch requires full WP file tree).
_ajax_wp_die_handler                         // why: requires full HTTP output/wp_die runtime flow.
_config_wp_home                              // why: mutates global config/runtime state.
_config_wp_siteurl                           // why: mutates global config/runtime state.
_default_wp_die_handler                      // why: requires full WP request context.
_delete_option_fresh_site                    // why: depends on options lifecycle/full install flow.
_deprecated_class                            // why: tied to legacy runtime notices/bootstrapping.
_deprecated_constructor                      // why: tied to legacy runtime notices/bootstrapping.
_deprecated_file                             // why: tied to include/runtime loading flow.
_device_can_upload                           // why: depends on request headers/client runtime.
_get_non_cached_ids                          // why: requires object-cache/runtime context.
_json_wp_die_handler                         // why: requires full HTTP output/wp_die runtime flow.
_jsonp_wp_die_handler                        // why: requires full HTTP output/wp_die runtime flow.
_mce_set_direction                           // why: editor/admin runtime dependency.
_scalar_wp_die_handler                       // why: requires full HTTP output/wp_die runtime flow.
_validate_cache_id                           // why: requires object-cache/runtime context.
_wp_check_alternate_file_names               // why: filesystem/media upload dependency.
_wp_check_existing_file_names                // why: filesystem/media upload dependency.
_wp_die_process_input                        // why: requires wp_die request lifecycle.
_wp_mysql_week                               // why: tied to DB date query behavior.
_wp_timezone_choice_usort_callback           // why: used only with full timezone list UI flow.
_wp_upload_dir                               // why: filesystem + uploads runtime dependency.
_xml_wp_die_handler                          // why: requires full HTTP output/wp_die runtime flow.
_xmlrpc_wp_die_handler                       // why: requires full XML-RPC runtime flow.
add_magic_quotes                             // why: request bootstrap mutation.
apache_mod_loaded                            // why: server-environment specific runtime behavior.
cache_javascript_headers                     // why: HTTP headers/output side effects.
clean_dirsize_cache                          // why: depends on object-cache/filesystem runtime.
dead_db                                      // why: DB bootstrap failure handler.
do_enclose                                   // why: post/media runtime dependency.
do_favicon                                   // why: request routing/output side effects.
do_feed                                      // why: request routing/output side effects.
do_feed_atom                                 // why: request routing/output side effects.
do_feed_rdf                                  // why: request routing/output side effects.
do_feed_rss                                  // why: request routing/output side effects.
do_feed_rss2                                 // why: request routing/output side effects.
do_robots                                    // why: request routing/output side effects.
get_dirsize                                  // why: filesystem runtime dependency.
get_main_network_id                          // why: multisite DB/runtime dependency.
get_main_site_id                             // why: multisite DB/runtime dependency.
get_num_queries                              // why: DB/runtime dependency.
get_temp_dir                                 // why: filesystem/environment dependency.
get_weekstartend                             // why: option + locale runtime dependency.
iis7_supports_permalinks                     // why: server-environment specific runtime behavior.
is_blog_installed                            // why: DB/runtime dependency.
is_lighttpd_before_150                       // why: server-environment specific runtime behavior.
is_main_network                              // why: multisite DB/runtime dependency.
is_main_site                                 // why: multisite DB/runtime dependency.
is_new_day                                   // why: relies on full post loop/global runtime.
is_site_meta_supported                       // why: DB/runtime dependency.
nocache_headers                              // why: HTTP headers/output side effects.
recurse_dirsize                              // why: filesystem runtime dependency.
send_frame_options_header                    // why: HTTP headers/output side effects.
send_nosniff_header                          // why: HTTP headers/output side effects.
status_header                                // why: HTTP headers/output side effects.
win_is_writable                              // why: filesystem/environment dependency.
wp                                           // why: full WP bootstrap entrypoint.
wp_admin_headers                             // why: admin HTTP output/runtime dependency.
wp_admin_notice                              // why: admin notice/runtime dependency.
wp_auth_check                                // why: auth/cookie/session runtime dependency.
wp_auth_check_html                           // why: auth/cookie/session runtime dependency.
wp_auth_check_load                           // why: auth/cookie/session runtime dependency.
wp_cache_get_last_changed                    // why: object-cache runtime dependency.
wp_cache_set_last_changed                    // why: object-cache runtime dependency.
wp_check_filetype_and_ext                    // why: filesystem/media validation dependency.
wp_delete_file                               // why: filesystem side effects.
wp_delete_file_from_directory                // why: filesystem side effects.
wp_die                                       // why: full request/output lifecycle.
wp_direct_php_update_button                  // why: admin UI/runtime dependency.
wp_extract_urls                              // why: currently not used by this package.
wp_filesize                                  // why: filesystem runtime dependency.
wp_get_admin_notice                          // why: admin notice/runtime dependency.
wp_get_default_update_https_url              // why: admin/update runtime dependency.
wp_get_default_update_php_url                // why: admin/update runtime dependency.
wp_get_direct_php_update_url                 // why: admin/update runtime dependency.
wp_get_direct_update_https_url               // why: admin/update runtime dependency.
wp_get_http_headers                          // why: remote HTTP/network dependency.
wp_get_original_referer                      // why: request globals/runtime dependency.
wp_get_raw_referer                           // why: request globals/runtime dependency.
wp_get_referer                               // why: request globals/runtime dependency.
wp_get_update_https_url                      // why: admin/update runtime dependency.
wp_get_update_php_annotation                 // why: admin/update runtime dependency.
wp_get_update_php_url                        // why: admin/update runtime dependency.
wp_get_upload_dir                            // why: filesystem/uploads runtime dependency.
wp_guess_url                                 // why: server/request runtime dependency.
wp_is_serving_rest_request                   // why: REST request runtime dependency.
wp_is_writable                               // why: filesystem/environment dependency.
wp_maybe_decline_date                        // why: locale/i18n runtime dependency chain.
wp_maybe_load_widgets                        // why: widgets runtime bootstrap dependency.
wp_mkdir_p                                   // why: filesystem side effects.
wp_nonce_ays                                 // why: nonce/auth request lifecycle dependency.
wp_nonce_field                               // why: nonce/auth request lifecycle dependency.
wp_nonce_url                                 // why: nonce/auth request lifecycle dependency.
wp_ob_end_flush_all                          // why: output buffering side effects.
wp_original_referer_field                    // why: request/auth runtime dependency.
wp_post_preview_js                           // why: editor/admin runtime dependency.
wp_privacy_delete_old_export_files           // why: cron/filesystem runtime dependency.
wp_privacy_exports_dir                       // why: filesystem/uploads runtime dependency.
wp_privacy_exports_url                       // why: URL + uploads runtime dependency.
wp_raise_memory_limit                        // why: runtime ini mutation.
wp_referer_field                             // why: request/auth runtime dependency.
wp_remote_fopen                              // why: remote HTTP/network dependency.
wp_removable_query_args                      // why: admin/request runtime dependency.
wp_schedule_delete_old_privacy_export_files  // why: cron runtime dependency.
wp_scheduled_delete                          // why: cron/runtime dependency.
wp_send_json                                 // why: HTTP output side effects.
wp_send_json_error                           // why: HTTP output side effects.
wp_send_json_success                         // why: HTTP output side effects.
wp_site_admin_email_change_notification      // why: option/mail runtime dependency.
wp_suspend_cache_invalidation                // why: object-cache runtime dependency.
wp_timezone_choice                           // why: admin UI/runtime dependency.
wp_timezone_override_offset                  // why: option/timezone runtime dependency.
wp_unique_filename                           // why: filesystem/uploads runtime dependency.
wp_update_php_annotation                     // why: admin/update runtime dependency.
wp_upload_bits                               // why: filesystem/uploads side effects.
wp_upload_dir                                // why: filesystem/uploads runtime dependency.
wp_widgets_add_menu                          // why: admin/widgets runtime dependency.
xmlrpc_getpostcategory                       // why: XML-RPC post runtime dependency.
xmlrpc_getposttitle                          // why: XML-RPC post runtime dependency.
xmlrpc_removepostdata                        // why: XML-RPC post runtime dependency.
*/
