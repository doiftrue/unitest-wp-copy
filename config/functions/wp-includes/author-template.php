<?php

return [];

/*
Not suitable in isolated PHPUnit env:

get_the_author             // why: depends on $authordata global (set by WP_Query loop)
the_author                 // why: depends on get_the_author() → $authordata
get_the_modified_author    // why: depends on get_post() → DB
the_modified_author        // why: depends on get_the_modified_author() → DB
get_the_author_meta        // why: depends on get_userdata() → DB
the_author_meta            // why: depends on get_the_author_meta() → DB
get_the_author_link        // why: depends on get_the_author_meta() → DB
the_author_link            // why: depends on get_the_author_link() → DB
get_the_author_posts       // why: depends on $authordata → WP_Query loop
the_author_posts           // why: depends on get_the_author_posts()
get_the_author_posts_link  // why: depends on get_the_author_meta() → DB
the_author_posts_link      // why: depends on get_the_author_posts_link() → DB
get_author_posts_url       // why: depends on $wp_rewrite + get_userdata() → DB
wp_list_authors            // why: depends on $wpdb → DB queries
is_multi_author            // why: depends on $wpdb → DB queries
__clear_multi_author_cache // why: utility for is_multi_author (wp_cache dependency without useful standalone behavior)
*/
