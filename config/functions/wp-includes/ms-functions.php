<?php

return [
	'get_subdirectory_reserved_names' => '4.4.0',
	'get_current_site'                => '3.9.0 mockable',
	'force_ssl_content'               => '2.8.5',
	'filter_SSL'                      => '2.8.5',
];

/*
Not suitable in isolated PHPUnit env:

is_email_address_unsafe               // why: reads banned_email_domains site option (not in boot-wp-options.php)
check_upload_mimes                    // why: reads upload_filetypes site option (not in boot-wp-options.php)
upload_is_file_too_big                // why: reads upload_space_check_disabled, fileupload_maxk site options (not in boot-wp-options.php)
users_can_register_signup_filter      // why: reads registration site option (not in boot-wp-options.php)
get_space_allowed                     // why: reads blog_upload_space option/site option (not in boot-wp-options.php)
get_sitestats                         // why: depends on get_blog_count(), get_user_count() (network options/DB)
get_active_blog_for_user              // why: depends on get_blogs_of_user(), user meta (DB)
get_blog_count                        // why: depends on get_network_option() (not available)
get_blog_post                         // why: depends on get_post() (not available)
add_user_to_blog                      // why: depends on get_userdata(), user roles (DB)
remove_user_from_blog                 // why: depends on $wpdb, get_userdata()
get_blog_permalink                    // why: depends on get_permalink() (not available)
get_blog_id_from_url                  // why: depends on get_sites() (DB)
wpmu_validate_user_signup             // why: depends on $wpdb
wpmu_validate_blog_signup             // why: depends on $wpdb
wpmu_signup_blog                      // why: depends on $wpdb
wpmu_signup_user                      // why: depends on $wpdb
wpmu_signup_blog_notification         // why: depends on wp_mail(), $wpdb
wpmu_signup_user_notification         // why: depends on wp_mail(), $wpdb
wpmu_activate_signup                  // why: depends on $wpdb
wp_delete_signup_on_user_delete       // why: depends on $wpdb
wpmu_create_user                      // why: depends on wp_create_user() (DB)
wpmu_create_blog                      // why: depends on wp_insert_site() (DB)
newblog_notify_siteadmin              // why: depends on wp_mail(), $wpdb
newuser_notify_siteadmin              // why: depends on wp_mail(), get_userdata() (DB)
domain_exists                         // why: depends on get_sites() (DB)
wpmu_welcome_notification             // why: depends on wp_mail()
wpmu_new_site_admin_notification      // why: depends on wp_mail()
wpmu_welcome_user_notification        // why: depends on wp_mail()
get_most_recent_post_of_user          // why: depends on $wpdb
update_posts_count                    // why: depends on $wpdb
wpmu_log_new_registrations            // why: depends on $wpdb, get_userdata()
redirect_this_site                    // why: depends on get_network() (DB)
signup_nonce_fields                   // why: HTML output + wp_nonce_field()
signup_nonce_check                    // why: depends on wp_verify_nonce(), $_POST
maybe_redirect_404                    // why: depends on is_main_site() (DB), wp_redirect()
maybe_add_existing_user_to_blog       // why: depends on user meta, add_existing_user_to_blog() (DB)
add_existing_user_to_blog             // why: depends on add_user_to_blog() (DB)
add_new_user_to_blog                  // why: depends on add_user_to_blog() (DB)
fix_phpmailer_messageid               // why: depends on PHPMailer instance
is_user_spammy                        // why: depends on get_user_by(), wp_get_current_user() (DB)
update_blog_public                    // why: depends on update_blog_status() (DB)
welcome_user_msg_filter               // why: depends on update_site_option() (not available)
wp_schedule_update_network_counts     // why: depends on is_main_site() (DB), wp_next_scheduled()
wp_update_network_counts              // why: depends on wp_update_network_site_counts() (DB)
wp_maybe_update_network_site_counts   // why: depends on wp_update_network_site_counts() (DB)
wp_maybe_update_network_user_counts   // why: depends on wp_update_network_user_counts() (DB)
wp_update_network_site_counts         // why: depends on get_sites() (DB), update_network_option()
wp_update_network_user_counts         // why: depends on get_user_count() (DB), update_network_option()
get_space_used                        // why: depends on wp_upload_dir(), get_dirsize() (filesystem)
get_upload_space_available            // why: depends on get_space_used() (filesystem)
is_upload_space_available             // why: depends on get_upload_space_available() (filesystem)
upload_size_limit_filter              // why: depends on get_upload_space_available() (filesystem)
wp_is_large_network                   // why: depends on get_user_count(), get_blog_count() (DB)
update_network_option_new_admin_email // why: depends on wp_mail(), wp_get_current_user()
wp_network_admin_email_change_notification // why: depends on wp_mail()
*/
