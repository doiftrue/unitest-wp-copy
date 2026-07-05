<?php

return [
	'wp_autoload_values_to_autoload'                   => '6.6.0',
	'wp_determine_option_autoload_value'               => '6.6.0',
	'wp_filter_default_autoload_value_via_option_size' => '6.6.0',
	'get_registered_settings'                          => '4.7.0 mockable',
	'filter_default_option'                            => '4.7.0',
	'register_setting'                                 => '2.7.0',
	'unregister_setting'                               => '2.7.0',
	// 'get_option'      => '', // why: custom mock without DB fallback
	// 'get_site_option' => '', // why: custom mock without DB fallback
];

/*
Not suitable in isolated PHPUnit env:

wp_prime_option_caches          // why: directly queries or mutates the database via $wpdb
wp_prime_option_caches_by_group // why: depends on wp_prime_option_caches()
get_options                     // why: depends on wp_prime_option_caches()
wp_set_option_autoload_values   // why: directly queries or mutates the database via $wpdb
wp_set_options_autoload         // why: depends on wp_set_option_autoload_values()
wp_set_option_autoload          // why: depends on wp_set_option_autoload_values()
wp_protect_special_option       // why: depends on wp_die()
form_option                     // why: depends on get_option()
wp_load_alloptions              // why: directly queries or mutates the database via $wpdb
wp_prime_site_option_caches     // why: depends on wp_prime_network_option_caches()
wp_prime_network_option_caches  // why: directly queries or mutates the database via $wpdb
wp_load_core_site_options       // why: depends on wp_prime_network_option_caches()
update_option                   // why: directly queries or mutates the database via $wpdb
add_option                      // why: directly queries or mutates the database via $wpdb
delete_option                   // why: directly queries or mutates the database via $wpdb
delete_transient                // why: depends on wp_using_ext_object_cache()
get_transient                   // why: depends on wp_using_ext_object_cache()
set_transient                   // why: depends on wp_using_ext_object_cache()
delete_expired_transients       // why: directly queries or mutates the database via $wpdb
wp_user_settings                // why: writes HTTP cookies/headers
get_user_setting                // why: depends on get_all_user_settings()
set_user_setting                // why: depends on get_all_user_settings()
delete_user_setting             // why: depends on get_all_user_settings()
get_all_user_settings           // why: depends on get_current_user_id()
wp_set_all_user_settings        // why: depends on get_current_user_id()
delete_all_user_settings        // why: writes HTTP cookies/headers
add_site_option                 // why: depends on add_network_option()
delete_site_option              // why: depends on delete_network_option()
update_site_option              // why: depends on update_network_option()
get_network_option              // why: directly queries or mutates the database via $wpdb
add_network_option              // why: directly queries or mutates the database via $wpdb
delete_network_option           // why: directly queries or mutates the database via $wpdb
update_network_option           // why: directly queries or mutates the database via $wpdb
delete_site_transient           // why: depends on wp_using_ext_object_cache()
get_site_transient              // why: depends on wp_using_ext_object_cache()
set_site_transient              // why: depends on wp_using_ext_object_cache()
register_initial_settings       // why: depends on register_setting()
*/
