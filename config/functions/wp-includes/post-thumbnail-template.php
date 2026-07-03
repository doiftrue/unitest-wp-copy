<?php

return [];

/*
Not suitable in isolated PHPUnit env:

All functions in this file depend on get_post() + get_post_meta() / wp_get_attachment_image() / DB:

has_post_thumbnail            // why: depends on get_post_thumbnail_id() → get_post() + get_post_meta() → DB
get_post_thumbnail_id         // why: depends on get_post() + get_post_meta() → DB
the_post_thumbnail            // why: depends on get_the_post_thumbnail() → DB chain
update_post_thumbnail_cache   // why: depends on $wp_query + _prime_post_caches() → DB
get_the_post_thumbnail        // why: depends on get_post() + wp_get_attachment_image() → DB
get_the_post_thumbnail_url    // why: depends on get_post_thumbnail_id() + wp_get_attachment_image_url() → DB
the_post_thumbnail_url        // why: depends on get_the_post_thumbnail_url() → DB
get_the_post_thumbnail_caption // why: depends on get_post_thumbnail_id() + wp_get_attachment_caption() → DB
the_post_thumbnail_caption    // why: depends on get_the_post_thumbnail_caption() → DB
*/
