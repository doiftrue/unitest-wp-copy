<?php
return [];
/*
Not suitable in isolated PHPUnit env:

query_posts            // why: depends on WP_Query runtime
wp_reset_query         // why: depends on wp_reset_postdata()
wp_reset_postdata      // why: depends on the global WP_Query/post-loop state
get_query_var          // why: depends on the global WP_Query request state
get_queried_object     // why: depends on the global WP_Query request state
get_queried_object_id  // why: depends on the global WP_Query request state
set_query_var          // why: depends on the global WP_Query request state
is_archive             // why: depends on the global WP_Query request state
is_post_type_archive   // why: depends on the global WP_Query request state
is_attachment          // why: depends on the global WP_Query request state
is_author              // why: depends on the global WP_Query request state
is_category            // why: depends on the global WP_Query request state
is_tag                 // why: depends on the global WP_Query request state
is_tax                 // why: depends on the global WP_Query request state
is_date                // why: depends on the global WP_Query request state
is_day                 // why: depends on the global WP_Query request state
is_feed                // why: depends on the global WP_Query request state
is_comment_feed        // why: depends on the global WP_Query request state
is_front_page          // why: depends on the global WP_Query request state
is_home                // why: depends on the global WP_Query request state
is_privacy_policy      // why: depends on the global WP_Query request state
is_month               // why: depends on the global WP_Query request state
is_page                // why: depends on the global WP_Query request state
is_paged               // why: depends on the global WP_Query request state
is_preview             // why: depends on the global WP_Query request state
is_robots              // why: depends on the global WP_Query request state
is_favicon             // why: depends on the global WP_Query request state
is_search              // why: depends on the global WP_Query request state
is_single              // why: depends on the global WP_Query request state
is_singular            // why: depends on the global WP_Query request state
is_time                // why: depends on the global WP_Query request state
is_trackback           // why: depends on the global WP_Query request state
is_year                // why: depends on the global WP_Query request state
is_404                 // why: depends on the global WP_Query request state
is_embed               // why: depends on the global WP_Query request state
is_main_query          // why: depends on the global WP_Query request state
have_posts             // why: depends on the global WP_Query request state
in_the_loop            // why: depends on the global WP_Query request state
rewind_posts           // why: depends on the global WP_Query request state
the_post               // why: depends on the global WP_Query request state
have_comments          // why: depends on the global WP_Query request state
the_comment            // why: depends on the global WP_Query request state
wp_old_slug_redirect   // why: depends on is_404()
_find_post_by_old_slug // why: directly queries or mutates the database via $wpdb
_find_post_by_old_date // why: directly queries or mutates the database via $wpdb
setup_postdata         // why: depends on the global WP_Query request state
generate_postdata      // why: depends on the global WP_Query request state
*/
