<?php

return [
	'validate_username'                         => '2.0.1',
	'wp_get_password_hint'                      => '4.1.0',
	'_wp_privacy_action_request_types'          => '4.9.6',
	'wp_register_user_personal_data_exporter'   => '4.9.6',
	'wp_user_request_action_description'        => '4.9.6',
	'wp_is_application_passwords_supported'     => '5.9.0 mockable',
	'wp_is_application_passwords_available'     => '5.6.0',
	'wp_cache_set_users_last_changed'            => '6.3.0',
	'wp_get_session_token'                       => '4.0.0 mockable',
	'sanitize_user_field'                        => '2.3.0',
];

/*
Not suitable in isolated PHPUnit env:

wp_signon // why: directly queries or mutates the database via $wpdb
wp_authenticate_username_password // why: depends on WP_User runtime
wp_authenticate_email_password // why: depends on WP_User runtime
wp_authenticate_cookie // why: depends on WP_User runtime
wp_authenticate_application_password // why: depends on WP_User runtime
wp_validate_application_password // why: depends on WP_User runtime
wp_authenticate_spam_check // why: depends on WP_User runtime
wp_validate_logged_in_cookie // why: depends on wp_validate_auth_cookie()
count_user_posts // why: directly queries or mutates the database via $wpdb
count_many_users_posts // why: directly queries or mutates the database via $wpdb
get_current_user_id // why: depends on wp_get_current_user()
get_user_option // why: directly queries or mutates the database via $wpdb
update_user_option // why: directly queries or mutates the database via $wpdb
delete_user_option // why: directly queries or mutates the database via $wpdb
get_user // why: depends on get_user_by()
get_users // why: depends on WP_User_Query
wp_list_users // why: depends on get_users()
get_blogs_of_user // why: directly queries or mutates the database via $wpdb
is_user_member_of_blog // why: directly queries or mutates the database via $wpdb
add_user_meta // why: depends on add_metadata()
delete_user_meta // why: depends on delete_metadata()
get_user_meta // why: depends on get_metadata()
update_user_meta // why: depends on update_metadata()
count_users // why: directly queries or mutates the database via $wpdb
get_user_count // why: depends on get_network_option()
wp_maybe_update_user_counts // why: depends on wp_is_large_user_count()
wp_update_user_counts // why: directly queries or mutates the database via $wpdb
wp_schedule_update_user_counts // why: depends on is_main_site()
wp_is_large_user_count // why: depends on get_user_count()
setup_userdata // why: depends on the global WP_User runtime state
wp_dropdown_users // why: depends on is_author()
update_user_caches // why: depends on WP_User runtime
clean_user_cache // why: depends on WP_User runtime
username_exists // why: depends on get_user_by()
email_exists // why: depends on get_user_by()
wp_insert_user // why: directly queries or mutates the database via $wpdb
wp_update_user // why: reads unresolved option `blogname`
wp_create_user // why: depends on wp_insert_user()
_get_additional_user_keys // why: depends on wp_get_user_contact_methods()
wp_get_user_contact_methods // why: depends on WP_User runtime
_wp_get_user_contactmethods // why: depends on wp_get_user_contact_methods()
get_password_reset_key // why: depends on WP_User runtime
check_password_reset_key // why: depends on WP_User runtime
retrieve_password // why: reads unresolved option `blogname`
reset_password // why: depends on WP_User runtime
register_new_user // why: reads unresolved option `admin_email`
wp_send_new_user_notifications // why: depends on wp_new_user_notification()
wp_get_all_sessions // why: depends on WP_Session_Tokens runtime
wp_destroy_current_session // why: depends on WP_Session_Tokens runtime
wp_destroy_other_sessions // why: depends on WP_Session_Tokens runtime
wp_destroy_all_sessions // why: depends on WP_Session_Tokens runtime
wp_get_users_with_no_role // why: directly queries or mutates the database via $wpdb
_wp_get_current_user // why: depends on the global WP_User runtime state
send_confirmation_on_profile_email // why: reads unresolved option `blogname`
new_user_email_admin_notice // why: depends on get_user_meta()
wp_user_personal_data_exporter // why: depends on WP_User runtime
_wp_privacy_account_request_confirmed // why: depends on wp_get_user_request()
_wp_privacy_send_request_confirmation_notification // why: reads unresolved site option `admin_email`
_wp_privacy_send_erasure_fulfillment_notification // why: reads unresolved option `blogname`
_wp_privacy_account_request_confirmed_message // why: depends on wp_get_user_request()
wp_create_user_request // why: depends on WP_Query runtime
wp_send_user_request // why: reads unresolved option `blogname`
wp_generate_user_request_key // why: depends on wp_update_post()
wp_validate_user_request_key // why: depends on wp_get_user_request()
wp_get_user_request // why: depends on get_post()
wp_is_application_passwords_available_for_user // why: depends on WP_User runtime
wp_register_persisted_preferences_meta // why: directly queries or mutates the database via $wpdb
wp_is_password_reset_allowed_for_user // why: depends on get_userdata()
*/
