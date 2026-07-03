<?php

return [
	'wp_robots'                         => '5.7.0',
	'wp_robots_noindex'                 => '5.7.0',
	'wp_robots_no_robots'               => '5.7.0',
	'wp_robots_sensitive_page'          => '5.7.0',
	'wp_robots_max_image_preview_large' => '5.7.0',
];

/*
Not suitable in isolated PHPUnit env:

wp_robots_noindex_embeds   // why: depends on is_embed() → WP_Query conditional
wp_robots_noindex_search   // why: depends on is_search() → WP_Query conditional
*/
