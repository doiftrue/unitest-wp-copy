<?php

return [
	'_wp_filter_taxonomy_base' => '2.6.0',
];

/*
Not suitable in isolated PHPUnit env:

add_rewrite_rule                  // why: requires the WP_Rewrite runtime global.
add_rewrite_tag                   // why: requires WP and WP_Rewrite runtime globals.
remove_rewrite_tag                // why: requires the WP_Rewrite runtime global.
add_permastruct                   // why: requires the WP_Rewrite runtime global.
remove_permastruct                // why: requires the WP_Rewrite runtime global.
add_feed                          // why: mutates WP_Rewrite and request hook lifecycle.
flush_rewrite_rules               // why: requires WP_Rewrite and persistent rewrite state.
add_rewrite_endpoint              // why: requires the WP_Rewrite runtime global.
wp_resolve_numeric_slug_conflicts // why: depends on permalink options and post DB lookup.
url_to_postid                     // why: depends on WP_Rewrite, WP_Query, and post DB runtime.
*/
