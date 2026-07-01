<?php

return [
	'wp_cache_init'             => '2.0.0',
	'wp_cache_add'              => '2.0.0',
	'wp_cache_add_multiple'     => '6.0.0',
	'wp_cache_replace'          => '2.0.0',
	'wp_cache_set'              => '2.0.0',
	'wp_cache_set_multiple'     => '6.0.0',
	'wp_cache_get'              => '2.0.0',
	'wp_cache_get_multiple'     => '5.5.0',
	'wp_cache_delete'           => '2.0.0',
	'wp_cache_delete_multiple'  => '6.0.0',
	'wp_cache_incr'             => '3.3.0',
	'wp_cache_decr'             => '3.3.0',
	'wp_cache_flush'            => '2.0.0',
	'wp_cache_flush_runtime'    => '6.0.0',
	'wp_cache_flush_group'      => '6.1.0',
	'wp_cache_supports'         => '6.1.0',
	'wp_cache_close'            => '2.0.0',
	'wp_cache_add_global_groups'         => '2.6.0',
	'wp_cache_add_non_persistent_groups' => '2.6.0',
	'wp_cache_switch_to_blog'   => '3.5.0',
];

/*
Not suitable in isolated PHPUnit env:

wp_cache_reset  // why: deprecated since 3.5.0, calls _deprecated_function + $wp_object_cache->reset() which may not exist
*/
