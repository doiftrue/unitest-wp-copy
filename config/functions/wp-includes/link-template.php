<?php

return [
	'home_url'            => '3.0.0',
	'get_home_url'        => '3.0.0 mockable',
	'site_url'            => '3.0.0',
	'get_site_url'        => '3.0.0 mockable',
	'wp_internal_hosts'   => '6.2.0',
	'wp_is_internal_link' => '6.2.0',
	'set_url_scheme'      => '3.4.0',
	'plugins_url'         => '2.6.0',
	'content_url'         => '2.6.0',
	'includes_url'        => '2.6.0',
];

/*
Not suitable in isolated PHPUnit env (query/template/admin runtime coupling):

_get_page_link                   // why: requires WP query/post permalink runtime.
_navigation_markup               // why: depends on theme/template rendering context.
adjacent_post_link               // why: requires post loop/query runtime.
adjacent_posts_rel_link          // why: requires post loop/query runtime.
adjacent_posts_rel_link_wp_head  // why: wp_head/output hook dependency.
admin_url                        // why: admin bootstrap/runtime dependency.
edit_bookmark_link               // why: admin capability/bookmark runtime dependency.
edit_comment_link                // why: comment/capability runtime dependency.
edit_post_link                   // why: post/capability runtime dependency.
edit_tag_link                    // why: taxonomy/admin runtime dependency.
edit_term_link                   // why: taxonomy/admin runtime dependency.
get_adjacent_post                // why: DB/query runtime dependency.
get_adjacent_post_link           // why: DB/query runtime dependency.
get_adjacent_post_rel_link       // why: DB/query runtime dependency.
get_admin_url                    // why: admin bootstrap/runtime dependency.
get_attachment_link              // why: attachment/post runtime dependency.
get_author_feed_link             // why: author query runtime dependency.
get_avatar_data                  // why: user/avatar runtime dependency chain.
get_avatar_url                   // why: user/avatar runtime dependency chain.
get_boundary_post                // why: DB/query runtime dependency.
get_category_feed_link           // why: taxonomy/query runtime dependency.
get_comments_pagenum_link        // why: comment query/runtime dependency.
get_dashboard_url                // why: admin bootstrap/runtime dependency.
get_day_link                     // why: rewrite/query runtime dependency.
get_delete_post_link             // why: nonce/capability runtime dependency.
get_edit_bookmark_link           // why: admin capability/bookmark runtime dependency.
get_edit_comment_link            // why: comment/capability runtime dependency.
get_edit_post_link               // why: post/capability runtime dependency.
get_edit_profile_url             // why: user/admin runtime dependency.
get_edit_tag_link                // why: taxonomy/admin runtime dependency.
get_edit_term_link               // why: taxonomy/admin runtime dependency.
get_edit_user_link               // why: user/admin runtime dependency.
get_feed_link                    // why: rewrite/query runtime dependency.
get_month_link                   // why: rewrite/query runtime dependency.
get_next_comments_link           // why: comment query/runtime dependency.
get_next_post                    // why: post query/runtime dependency.
get_next_post_link               // why: post query/runtime dependency.
get_next_posts_link              // why: query pagination runtime dependency.
get_next_posts_page_link         // why: query pagination runtime dependency.
get_page_link                    // why: post/query permalink runtime dependency.
get_pagenum_link                 // why: query/request runtime dependency.
get_parent_theme_file_path       // why: theme filesystem/runtime dependency.
get_parent_theme_file_uri        // why: theme filesystem/runtime dependency.
get_permalink                    // why: post/query permalink runtime dependency.
get_post_comments_feed_link      // why: post/comment query runtime dependency.
get_post_permalink               // why: post/query permalink runtime dependency.
get_post_type_archive_feed_link  // why: post-type/rewrite runtime dependency.
get_post_type_archive_link       // why: post-type/rewrite runtime dependency.
get_posts_nav_link               // why: query pagination runtime dependency.
get_preview_post_link            // why: preview/nonces/runtime dependency.
get_previous_comments_link       // why: comment query/runtime dependency.
get_previous_post                // why: post query/runtime dependency.
get_previous_post_link           // why: post query/runtime dependency.
get_previous_posts_link          // why: query pagination runtime dependency.
get_previous_posts_page_link     // why: query pagination runtime dependency.
get_privacy_policy_url           // why: options/page runtime dependency.
get_search_comments_feed_link    // why: search query/runtime dependency.
get_search_feed_link             // why: search query/runtime dependency.
get_search_link                  // why: search query/runtime dependency.
get_tag_feed_link                // why: taxonomy/query runtime dependency.
get_term_feed_link               // why: taxonomy/query runtime dependency.
get_the_comments_navigation      // why: comments/template runtime dependency.
get_the_comments_pagination      // why: comments/template runtime dependency.
get_the_permalink                // why: post loop/runtime dependency.
get_the_post_navigation          // why: post loop/runtime dependency.
get_the_posts_navigation         // why: query/template runtime dependency.
get_the_posts_pagination         // why: query/template runtime dependency.
get_the_privacy_policy_link      // why: options/page/template runtime dependency.
get_theme_file_path              // why: theme filesystem/runtime dependency.
get_theme_file_uri               // why: theme filesystem/runtime dependency.
get_year_link                    // why: rewrite/query runtime dependency.
is_avatar_comment_type           // why: avatar/comment runtime dependency chain.
network_admin_url                // why: multisite admin runtime dependency.
network_home_url                 // why: multisite runtime dependency.
network_site_url                 // why: multisite runtime dependency.
next_comments_link               // why: comment query/runtime dependency.
next_post_link                   // why: post query/runtime dependency.
next_post_rel_link               // why: post query/runtime dependency.
next_posts                       // why: query pagination runtime dependency.
next_posts_link                  // why: query pagination runtime dependency.
paginate_comments_links          // why: comment pagination/template runtime dependency.
permalink_anchor                 // why: post permalink runtime dependency.
post_comments_feed_link          // why: post/comment query runtime dependency.
posts_nav_link                   // why: query pagination runtime dependency.
prev_post_rel_link               // why: post query/runtime dependency.
previous_comments_link           // why: comment query/runtime dependency.
previous_post_link               // why: post query/runtime dependency.
previous_posts                   // why: query pagination runtime dependency.
previous_posts_link              // why: query pagination runtime dependency.
rel_canonical                    // why: query/wp_head output runtime dependency.
self_admin_url                   // why: admin bootstrap/runtime dependency.
the_comments_navigation          // why: echoes template output (runtime side effects).
the_comments_pagination          // why: echoes template output (runtime side effects).
the_feed_link                    // why: echoes template output (runtime side effects).
the_permalink                    // why: echoes template output (runtime side effects).
the_post_navigation              // why: echoes template output (runtime side effects).
the_posts_navigation             // why: echoes template output (runtime side effects).
the_posts_pagination             // why: echoes template output (runtime side effects).
the_privacy_policy_link          // why: echoes template output (runtime side effects).
the_shortlink                    // why: echoes template output (runtime side effects).
user_admin_url                   // why: user-admin runtime dependency.
user_trailingslashit             // why: rewrite/runtime dependency chain.
wp_force_plain_post_permalink    // why: post/rewrite runtime dependency.
wp_get_canonical_url             // why: post/query runtime dependency.
wp_get_shortlink                 // why: post/query runtime dependency.
wp_shortlink_header              // why: HTTP header/output runtime dependency.
wp_shortlink_wp_head             // why: wp_head/output hook runtime dependency.
*/
