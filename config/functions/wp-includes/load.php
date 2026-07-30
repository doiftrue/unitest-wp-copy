<?php

return [
	'wp_is_development_mode'     => '6.3.0',
	'wp_get_development_mode'    => '6.3.0 mockable',
	'is_login'                   => '6.1.0 mockable',
	'timer_float'                => '5.8.0 mockable',
	'wp_is_json_media_type'      => '5.6.0',
	'wp_is_jsonp_request'        => '5.2.0 mockable',
	'wp_is_xml_request'          => '5.2.0 mockable',
	'wp_is_json_request'         => '5.0.0 mockable',
	'wp_using_themes'            => '5.1.0 mockable',
	'wp_get_environment_type'    => '5.5.0 mockable',
	'wp_doing_cron'              => '4.8.0 mockable',
	'wp_is_file_mod_allowed'     => '4.8.0 mockable',
	'wp_doing_ajax'              => '4.7.0 mockable',
	'get_current_network_id'     => '4.6.0 mockable',
	'wp_is_ini_value_changeable' => '4.6.0',
	'wp_get_server_protocol'     => '4.4.0 mockable',
	'wp_installing'              => '4.4.0 mockable', // note: in 99% is not installing, and it used as deps so add it
	'is_blog_admin'              => '3.1.0 mockable',
	'is_network_admin'           => '3.1.0 mockable',
	'is_user_admin'              => '3.1.0 mockable',
	'get_current_blog_id'        => '3.1.0 mockable',
	'is_multisite'               => '3.0.0 mockable',
	'is_ssl'                     => '2.6.0 mockable',
	'absint'                     => '2.5.0',
	'wp_convert_hr_to_bytes'     => '2.3.0',
	'is_wp_error'                => '2.1.0',
	'is_admin'                   => '1.5.1 mockable',
	'timer_start'                => '0.71',
	'timer_stop'                 => '0.71 mockable',
	// 'wp_load_translations_early' => '', // why: custom no-op mock for isolated runtime
];

/*
Not suitable in isolated PHPUnit env:

is_protected_ajax_action                          // why: protected-endpoint runtime dependency.
is_protected_endpoint                             // why: protected-endpoint runtime dependency.
require_wp_db                                     // why: hard DB bootstrap dependency.
shutdown_action_hook                              // why: shutdown hook/runtime side effects.
wp_check_php_mysql_versions                       // why: environment + DB capability checks.
wp_clone                                          // why: full bootstrap clone lifecycle.
wp_debug_mode                                     // why: global runtime/error-handler mutation.
wp_favicon_request                                // why: request routing/output dependency.
wp_finalize_scraping_edited_file_errors           // why: admin/editor error-scraping runtime.
wp_fix_server_vars                                // why: mutates global server runtime.
wp_get_active_and_valid_plugins                   // why: plugin bootstrap/filesystem dependency.
wp_get_active_and_valid_themes                    // why: theme bootstrap/filesystem dependency.
wp_get_mu_plugins                                 // why: mu-plugin filesystem bootstrap dependency.
wp_is_maintenance_mode                            // why: filesystem + bootstrap dependency.
wp_is_recovery_mode                               // why: recovery bootstrap/session dependency.
wp_is_site_protected_by_basic_auth                // why: server/auth runtime dependency.
wp_magic_quotes                                   // why: mutates request globals.
wp_maintenance                                    // why: maintenance bootstrap/output dependency.
wp_not_installed                                  // why: install/bootstrap + output dependency.
wp_populate_basic_auth_from_authorization_header  // why: request/auth header mutation.
wp_set_internal_encoding                          // why: global runtime encoding mutation.
wp_set_lang_dir                                   // why: translation/bootstrap path mutation.
wp_set_wpdb_vars                                  // why: hard $wpdb dependency.
wp_skip_paused_plugins                            // why: recovery mode/plugin runtime dependency.
wp_skip_paused_themes                             // why: recovery mode/theme runtime dependency.
wp_start_object_cache                             // why: object-cache bootstrap dependency.
wp_start_scraping_edited_file_errors              // why: admin/editor runtime dependency.
wp_using_ext_object_cache                         // why: object-cache runtime dependency.
*/
