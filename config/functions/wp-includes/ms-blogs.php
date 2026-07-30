<?php

return [
	'ms_is_switched'           => '3.5.0 mockable',
	'clean_site_details_cache' => '4.7.0',
];

/*
Not suitable in isolated PHPUnit env:

get_blog_option                          // why: reads arbitrary option names via get_option() — cannot guarantee all option dependencies
wpmu_update_blogs_date                   // why: depends on update_blog_details() (DB)
get_blogaddress_by_id                    // why: depends on get_site() -> WP_Site::get_instance() (DB)
get_blogaddress_by_name                  // why: depends on network_home_url() (not available)
get_id_from_blogname                     // why: depends on get_network() (DB), get_sites() (DB)
get_blog_details                         // why: wraps get_site() -> WP_Site::get_instance() (DB)
refresh_blog_details                     // why: depends on clean_blog_cache() -> get_site() (DB)
update_blog_details                      // why: depends on wp_update_site() (DB)
add_blog_option                          // why: depends on add_option() (not available)
delete_blog_option                       // why: depends on delete_option() (not available)
update_blog_option                       // why: depends on update_option() (not available)
switch_to_blog                           // why: custom mock in wp-runtime/custom-mocks/
restore_current_blog                     // why: custom mock in wp-runtime/custom-mocks/
wp_cache_switch_to_blog_fallback         // why: bootstrap-level cache reinit, no direct test utility
wp_switch_roles_and_user                 // why: depends on wp_roles(), wp_get_current_user() (user runtime)
is_archived                              // why: depends on get_blog_status() -> get_site() (DB)
update_archived                          // why: depends on update_blog_status() -> wp_update_site() (DB)
update_blog_status                       // why: depends on wp_update_site() (DB)
get_blog_status                          // why: depends on get_site() (DB), $wpdb
get_last_updated                         // why: depends on $wpdb
_update_blog_date_on_post_publish        // why: depends on wpmu_update_blogs_date() (DB)
_update_blog_date_on_post_delete         // why: depends on get_post() (not available)
_update_posts_count_on_delete            // why: depends on update_posts_count() (DB)
_update_posts_count_on_transition_post_status // why: depends on update_posts_count() (DB)
wp_count_sites                           // why: depends on WP_Site_Query (DB)
*/
