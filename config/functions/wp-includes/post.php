<?php

return [
	'get_extended'                        => '1.0.0',
	'get_post_statuses'                   => '2.5.0',
	'get_page_statuses'                   => '2.5.0',
	'_wp_privacy_statuses'                => '4.9.6',
	'register_post_status'                => '3.0.0',
	'get_post_status_object'              => '3.0.0',
	'get_post_stati'                      => '3.0.0',
	'is_post_type_hierarchical'           => '3.0.0 mockable',
	'post_type_exists'                    => '3.0.0 mockable',
	'get_post_type_object'                => '3.0.0 mockable',
	'get_post_types'                      => '2.9.0 mockable',
	'get_post_type_capabilities'          => '3.0.0',
	'_post_type_meta_capabilities'        => '3.1.0',
	'_get_custom_object_labels'           => '3.0.0',
	'add_post_type_support'               => '3.0.0',
	'remove_post_type_support'            => '3.0.0',
	'get_all_post_type_supports'          => '3.4.0',
	'post_type_supports'                  => '3.0.0',
	'get_post_types_by_support'           => '4.5.0',
	'is_post_type_viewable'               => '4.4.0 mockable',
	'is_post_status_viewable'             => '5.7.0 mockable',
	'get_post_mime_types'                 => '2.9.0',
	'wp_match_mime_types'                 => '2.5.0',
	'wp_post_mime_type_where'             => '2.5.0',
	'wp_resolve_post_date'                => '5.7.0',
	'_truncate_post_slug'                 => '3.6.0',
	'get_page_children'                   => '1.5.1',
	'get_page_hierarchy'                  => '2.0.0',
	'_page_traverse_name'                 => '2.9.0',
	'wp_untrash_post_set_previous_status' => '5.6.0',
	'use_block_editor_for_post_type'      => '5.0.0',
];

/*
Not suitable in isolated PHPUnit env (full WP runtime / DB / request / filesystem coupling):

create_initial_post_types                      // why: requires WP_Post_Type + register_post_type initialization chain.
get_attached_file                              // why: attachment meta/uploads runtime dependency.
update_attached_file                           // why: attachment meta/uploads runtime dependency.
_wp_relative_upload_path                       // why: depends on uploads/filesystem runtime state.
get_children                                   // why: query runtime dependency via get_posts().
get_post                                       // why: requires WP_Post class runtime (not copied in this project).
get_post_ancestors                             // why: depends on get_post()/WP_Post runtime chain.
get_post_field                                 // why: depends on get_post()/sanitize_post_field() runtime chain.
get_post_mime_type                             // why: depends on get_post_field() runtime chain.
get_post_status                                // why: depends on get_post()/WP_Post runtime chain.
get_post_type                                  // why: depends on get_post().
register_post_type                             // why: requires WP_Post_Type class + full registration runtime.
unregister_post_type                           // why: requires WP_Post_Type class + full registration runtime.
get_post_type_labels                           // why: requires WP_Post_Type::get_default_labels().
_add_post_type_submenus                        // why: admin menu runtime dependency.
set_post_type                                  // why: writes to DB via $wpdb + cache invalidation.
is_post_publicly_viewable                      // why: depends on get_post()/get_post_status() runtime chain.
is_post_embeddable                             // why: depends on get_post()/WP_Post runtime chain.
get_posts                                      // why: WP_Query/query runtime dependency.
add_post_meta                                  // why: metadata API/DB runtime dependency.
delete_post_meta                               // why: metadata API/DB runtime dependency.
get_post_meta                                  // why: metadata API/DB runtime dependency.
update_post_meta                               // why: metadata API/DB runtime dependency.
delete_post_meta_by_key                        // why: metadata API/DB runtime dependency.
register_post_meta                             // why: metadata registration/runtime dependency.
unregister_post_meta                           // why: metadata registration/runtime dependency.
get_post_custom                                // why: metadata API runtime dependency.
get_post_custom_keys                           // why: metadata API runtime dependency.
get_post_custom_values                         // why: metadata API runtime dependency.
is_sticky                                      // why: options + post runtime dependency.
sanitize_post                                  // why: depends on WP_Post object runtime chain.
sanitize_post_field                            // why: depends on full post-field/filter runtime chain.
stick_post                                     // why: option mutation + post runtime hooks.
unstick_post                                   // why: option mutation + post runtime hooks.
_count_posts_cache_key                         // why: depends on auth/runtime functions (is_user_logged_in/current_user_can).
wp_count_posts                                 // why: DB query + auth/cache runtime dependency.
wp_count_attachments                           // why: DB query/runtime dependency.
wp_delete_post                                 // why: DB delete lifecycle + terms/meta/comments runtime dependency.
_reset_front_page_settings_for_post            // why: options/front-page runtime mutation.
wp_trash_post                                  // why: DB + post status transition runtime.
wp_untrash_post                                // why: DB + post status transition runtime.
wp_trash_post_comments                         // why: comments DB/runtime dependency.
wp_untrash_post_comments                       // why: comments DB/runtime dependency.
wp_get_post_categories                         // why: taxonomy API/runtime dependency.
wp_get_post_tags                               // why: taxonomy API/runtime dependency.
wp_get_post_terms                              // why: taxonomy API/runtime dependency.
wp_get_recent_posts                            // why: query runtime dependency.
wp_insert_post                                 // why: full post insert/update DB lifecycle.
wp_update_post                                 // why: depends on wp_insert_post() DB lifecycle.
wp_publish_post                                // why: DB + status transition + hooks runtime dependency.
check_and_publish_future_post                  // why: cron/scheduling + post DB lifecycle dependency.
wp_unique_post_slug                            // why: DB + rewrite runtime dependency.
wp_add_post_tags                               // why: taxonomy API/runtime dependency.
wp_set_post_tags                               // why: taxonomy API/runtime dependency.
wp_set_post_terms                              // why: taxonomy API/runtime dependency.
wp_set_post_categories                         // why: taxonomy API/runtime dependency.
wp_transition_post_status                      // why: full status transition hook runtime dependency.
wp_after_insert_post                           // why: revisions/meta/hooks runtime dependency.
add_ping                                       // why: post update + DB/runtime dependency.
get_enclosed                                   // why: depends on post custom/meta runtime chain.
get_pung                                       // why: depends on post runtime chain.
get_to_ping                                    // why: depends on post runtime chain.
trackback_url_list                             // why: output/network side-effects runtime dependency.
get_all_page_ids                               // why: DB query runtime dependency.
get_page                                       // why: depends on get_post()/WP_Post runtime chain.
get_page_by_path                               // why: DB/cache + get_post() runtime dependency.
get_page_uri                                   // why: depends on get_post()/ancestor runtime chain.
get_pages                                      // why: query runtime dependency.
is_local_attachment                            // why: depends on url_to_postid()/get_post() runtime chain.
wp_insert_attachment                           // why: depends on wp_insert_post() DB lifecycle.
wp_delete_attachment                           // why: attachment DB + filesystem + metadata runtime dependency.
wp_delete_attachment_files                     // why: filesystem side-effects dependency.
wp_get_attachment_metadata                     // why: attachment metadata runtime dependency.
wp_update_attachment_metadata                  // why: attachment metadata runtime dependency.
wp_get_attachment_url                          // why: uploads/url + attachment metadata runtime dependency.
wp_get_attachment_caption                      // why: depends on get_post()/attachment runtime chain.
wp_get_attachment_thumb_url                    // why: attachment metadata/url runtime dependency.
wp_attachment_is                               // why: depends on attachment post runtime chain.
wp_attachment_is_image                         // why: depends on attachment post runtime chain.
wp_mime_type_icon                              // why: media icon/filesystem runtime dependency.
wp_check_for_changed_slugs                     // why: DB/meta runtime dependency.
wp_check_for_changed_dates                     // why: DB/meta runtime dependency.
get_private_posts_cap_sql                      // why: depends on get_posts_by_author_sql() auth/runtime chain.
get_posts_by_author_sql                        // why: depends on auth/runtime functions and SQL context.
get_lastpostdate                               // why: DB/cache/runtime dependency.
get_lastpostmodified                           // why: DB/cache/runtime dependency.
_get_last_post_time                            // why: DB/cache/runtime dependency.
update_post_cache                              // why: object-cache + WP_Post runtime dependency.
clean_post_cache                               // why: object-cache + terms/meta runtime dependency.
update_post_caches                             // why: cache + taxonomy/meta runtime dependency.
update_post_author_caches                      // why: user cache/runtime dependency.
update_post_parent_caches                      // why: DB/cache runtime dependency.
update_postmeta_cache                          // why: metadata cache runtime dependency.
clean_attachment_cache                         // why: cache + taxonomy runtime dependency.
_transition_post_status                        // why: status transition hook runtime dependency.
_future_post_hook                              // why: scheduling + post DB lifecycle dependency.
_publish_post_hook                             // why: publish hook runtime chain.
wp_get_post_parent_id                          // why: depends on get_post()/WP_Post runtime chain.
wp_check_post_hierarchy_for_loops              // why: depends on wp_update_post() DB lifecycle.
set_post_thumbnail                             // why: attachment + metadata runtime dependency.
delete_post_thumbnail                          // why: metadata/runtime dependency.
wp_delete_auto_drafts                          // why: DB cleanup runtime dependency.
wp_queue_posts_for_term_meta_lazyload          // why: term-meta lazyload runtime dependency.
_update_term_count_on_transition_post_status   // why: taxonomy term-count runtime dependency.
_prime_post_caches                             // why: cache/query runtime dependency.
_prime_post_parent_id_caches                   // why: DB/cache runtime dependency.
wp_add_trashed_suffix_to_post_name_for_trashed_posts // why: depends on get_posts() query runtime.
wp_add_trashed_suffix_to_post_name_for_post    // why: depends on get_post()/meta/DB runtime chain.
wp_cache_set_posts_last_changed                // why: depends on object-cache runtime function not included.
get_available_post_mime_types                  // why: DB query runtime dependency.
wp_get_original_image_path                     // why: attachment metadata/filesystem runtime dependency.
wp_get_original_image_url                      // why: attachment metadata/url runtime dependency.
use_block_editor_for_post                      // why: depends on get_post() + admin request/nonce runtime.
wp_create_initial_post_meta                    // why: depends on register_post_meta() runtime chain.
*/
