<?php

return [
	'wp_password_needs_rehash'      => '6.8.0',
	'_wp_sanitize_utf8_in_redirect' => '4.2.0',
	'wp_validate_redirect'          => '2.8.1',
	'wp_rand'                       => '2.6.2 mockable',
	'wp_hash_password'              => '2.5.0',
	'wp_generate_password'          => '2.5.0',
	'wp_nonce_tick'                 => '2.5.0 mockable',
	'wp_parse_auth_cookie'          => '2.5.0',
	'wp_sanitize_redirect'          => '2.3.0',
	'wp_hash'                       => '2.0.3',
	// 'wp_salt'                     => '', // why: custom mock without DB fallback
];

/*
Not suitable in isolated PHPUnit env:

wp_set_current_user        // why: depends on WP_User + DB user lookup chain
wp_get_current_user        // why: depends on get_currentuserinfo / user DB session
get_userdata               // why: depends on WP_User DB query
get_user_by               // why: depends on WP_User DB query
cache_users               // why: depends on $wpdb user queries
wp_mail                   // why: sends email via phpmailer
wp_authenticate           // why: depends on DB user lookup + password check + auth chain
wp_logout                 // why: destroys user session, sets cookies
wp_validate_auth_cookie   // why: depends on get_user_by + session tokens (DB)
wp_generate_auth_cookie   // why: depends on wp_get_session_token + user sessions
wp_set_auth_cookie        // why: sets HTTP cookies via setcookie()
wp_clear_auth_cookie      // why: clears HTTP cookies via setcookie()
is_user_logged_in         // why: depends on wp_get_current_user (DB)
auth_redirect             // why: sends HTTP headers/redirect
check_admin_referer       // why: depends on wp_verify_nonce → wp_get_current_user (DB)
check_ajax_referer        // why: depends on wp_verify_nonce → wp_get_current_user (DB)
wp_redirect               // why: sends HTTP Location header
wp_safe_redirect          // why: sends HTTP redirect via wp_redirect
wp_verify_nonce           // why: depends on wp_get_current_user + wp_get_session_token (DB)
wp_create_nonce           // why: depends on wp_get_current_user + wp_get_session_token (DB)
wp_check_password         // why: $P$ branch does require_once ABSPATH.WPINC path — file doesn't exist in isolated runtime
wp_set_password           // why: depends on $wpdb
wp_notify_postauthor      // why: sends email, depends on DB comment/user lookup
wp_notify_moderator       // why: sends email, depends on DB comment lookup
wp_password_change_notification // why: sends email
wp_new_user_notification  // why: sends email, depends on DB user
get_avatar                // why: depends on comments/users + external gravatar
wp_text_diff              // why: depends on Text_Diff/WP_Text_Diff_Renderer_Table classes (not available)
*/
