<?php

return [
	'feed_content_type'        => '2.8.0',
	'prep_atom_text_construct' => '2.5.0',
	'get_default_feed'         => '2.5.0',
	'html_type_rss'            => '2.2.0',
	'get_bloginfo_rss'         => '1.5.1',
	'bloginfo_rss'             => '0.71',
];

/*
Not suitable in isolated PHPUnit env:

get_wp_title_rss          // why: depends on live query title state through wp_get_document_title()
wp_title_rss              // why: output wrapper for get_wp_title_rss(), whose query-title state is unavailable
get_the_title_rss         // why: depends on post lookup and current post state through get_the_title()
the_title_rss             // why: output wrapper for get_the_title_rss(), whose post state is unavailable
get_the_content_feed      // why: depends on the current post loop, content rendering, and feed query state
the_content_feed          // why: output wrapper for get_the_content_feed(), whose post/feed state is unavailable
the_excerpt_rss           // why: depends on the current post excerpt and feed query state
the_permalink_rss         // why: depends on current post permalink and feed query state
comments_link_feed        // why: depends on current post comments-link and feed query state
comment_guid              // why: output wrapper for get_comment_guid(), whose comment lookup is unavailable
get_comment_guid          // why: depends on comment and post lookup state
comment_link              // why: depends on comment lookup and permalink generation state
get_comment_author_rss    // why: depends on the current comment author state
comment_author_rss        // why: output wrapper for get_comment_author_rss(), whose comment state is unavailable
comment_text_rss          // why: depends on the current comment text state
get_the_category_rss      // why: depends on current post terms and taxonomy lookup state
the_category_rss          // why: output wrapper for get_the_category_rss(), whose taxonomy state is unavailable
rss_enclosure             // why: depends on current post enclosure metadata
atom_enclosure            // why: depends on current post enclosure metadata
atom_site_icon            // why: depends on site-icon attachment state and image lookup
rss2_site_icon            // why: depends on site-icon attachment state and image lookup
get_self_link             // why: derives its value from live request globals and home URL state
self_link                 // why: output wrapper for get_self_link(), whose request state is unavailable
get_feed_build_date       // why: depends on the live query result set or last-post database lookup
fetch_feed                // why: loads SimplePie and performs remote HTTP/cache I/O
*/
