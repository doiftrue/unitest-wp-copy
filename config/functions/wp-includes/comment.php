<?php

return [
	'wp_cache_set_comments_last_changed'                 => '5.0.0',
	'wp_register_comment_personal_data_exporter'         => '4.9.6',
	'wp_register_comment_personal_data_eraser'           => '4.9.6',
	'_clear_modified_cache_on_transition_comment_status' => '4.7.0',
	'get_comment_statuses'                               => '2.7.0',
	'separate_comments'                                  => '2.7.0',
	'clean_comment_cache'                                => '2.3.0',
	'wp_throttle_comment_flood'                          => '2.1.0',
	'wp_filter_comment'                                  => '1.5.0',
];

/*
Not suitable in isolated PHPUnit env:

check_comment                               // why: directly queries or mutates the database via $wpdb
get_approved_comments                       // why: depends on WP_Comment_Query
get_comment                                 // why: depends on WP_Comment runtime
get_comments                                // why: depends on WP_Comment_Query
get_default_comment_status                  // why: reads unresolved option `default_{$option}_status`
get_lastcommentmodified                     // why: directly queries or mutates the database via $wpdb
get_comment_count                           // why: depends on get_comments()
add_comment_meta                            // why: depends on add_metadata()
delete_comment_meta                         // why: depends on delete_metadata()
get_comment_meta                            // why: depends on get_metadata()
wp_lazyload_comment_meta                    // why: depends on wp_metadata_lazyloader()
update_comment_meta                         // why: depends on update_metadata()
wp_set_comment_cookies                      // why: writes HTTP cookies/headers
sanitize_comment_cookies                    // why: depends on wp_filter_comment()
wp_allow_comment                            // why: directly queries or mutates the database via $wpdb
check_comment_flood_db                      // why: registers wp_check_comment_flood(), whose fallback directly queries $wpdb
wp_check_comment_flood                      // why: directly queries or mutates the database via $wpdb
get_comment_pages_count                     // why: depends on the global WP_Query request state
get_page_of_comment                         // why: directly queries or mutates the database via $wpdb
wp_get_comment_fields_max_lengths           // why: directly queries or mutates the database via $wpdb
wp_check_comment_data_max_lengths           // why: depends on wp_get_comment_fields_max_lengths()
wp_check_comment_data                       // why: directly queries or mutates the database via $wpdb
wp_check_comment_disallowed_list            // why: reads unresolved option `disallowed_keys`
wp_count_comments                           // why: depends on get_comment_count()
wp_delete_comment                           // why: directly queries or mutates the database via $wpdb
wp_trash_comment                            // why: depends on WP_Comment runtime
wp_untrash_comment                          // why: depends on WP_Comment runtime
wp_spam_comment                             // why: depends on WP_Comment runtime
wp_unspam_comment                           // why: depends on WP_Comment runtime
wp_get_comment_status                       // why: depends on get_comment()
wp_transition_comment_status                // why: depends on WP_Comment runtime
wp_get_current_commenter                    // why: reads commenter identity from request cookies
wp_get_unapproved_comment_author_email      // why: depends on get_comment()
wp_insert_comment                           // why: directly queries or mutates the database via $wpdb
wp_new_comment                              // why: directly queries or mutates the database via $wpdb
wp_new_comment_notify_moderator             // why: depends on get_comment()
wp_new_comment_notify_postauthor            // why: reads unresolved option `wp_notes_notify`
wp_new_comment_via_rest_notify_postauthor   // why: depends on WP_Comment runtime
wp_set_comment_status                       // why: directly queries or mutates the database via $wpdb
wp_update_comment                           // why: directly queries or mutates the database via $wpdb
wp_defer_comment_counting                   // why: depends on wp_update_comment_count()
wp_update_comment_count                     // why: depends on wp_update_comment_count_now()
wp_update_comment_count_now                 // why: directly queries or mutates the database via $wpdb
discover_pingback_server_uri                // why: performs remote HTTP/XML-RPC I/O
do_all_pings                                // why: is only a cron lifecycle action dispatcher
do_all_pingbacks                            // why: depends on get_posts()
do_all_enclosures                           // why: depends on get_posts()
do_all_trackbacks                           // why: depends on get_posts()
do_trackbacks                               // why: directly queries or mutates the database via $wpdb
generic_ping                                // why: reads unresolved option `ping_sites`
pingback                                    // why: performs remote HTTP/XML-RPC I/O
privacy_ping_filter                         // why: reads unresolved option `blog_public`
trackback                                   // why: directly queries or mutates the database via $wpdb
weblog_ping                                 // why: reads unresolved option `blogname`
pingback_ping_source_uri                    // why: depends on wp_http_validate_url() host/DNS validation
xmlrpc_pingback_error                       // why: depends on IXR_Error()
update_comment_cache                        // why: depends on update_meta_cache()
_prime_comment_caches                       // why: directly queries or mutates the database via $wpdb
_close_comments_for_old_posts               // why: reads unresolved option `close_comments_for_old_posts`
_close_comments_for_old_post                // why: reads unresolved option `close_comments_for_old_posts`
wp_handle_comment_submission                // why: reads unresolved option `comment_registration`
wp_comments_personal_data_exporter          // why: depends on get_comments()
wp_comments_personal_data_eraser            // why: directly queries or mutates the database via $wpdb
_wp_batch_update_comment_type               // why: directly queries or mutates the database via $wpdb
_wp_check_for_scheduled_update_comment_type // why: reads unresolved option `finished_updating_comment_type`
wp_create_initial_comment_meta              // why: depends on register_meta()

*/
