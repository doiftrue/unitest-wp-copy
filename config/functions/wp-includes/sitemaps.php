<?php

return [
	// Filter-only utility for sitemap page size.
	'wp_sitemaps_get_max_urls' => '5.5.0',
];

/*
Not suitable in isolated PHPUnit env:

wp_sitemaps_get_server       // why: bootstraps WP_Sitemaps::init() rewrite/request lifecycle.
wp_get_sitemap_providers     // why: depends on wp_sitemaps_get_server() bootstrap chain.
wp_register_sitemap_provider // why: depends on wp_sitemaps_get_server() bootstrap chain.
get_sitemap_url              // why: depends on wp_sitemaps_get_server() + provider registry runtime chain.
*/
