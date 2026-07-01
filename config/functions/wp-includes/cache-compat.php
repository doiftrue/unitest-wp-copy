<?php

return [
	'wp_cache_get_salted'          => '6.9.0',
	'wp_cache_set_salted'          => '6.9.0',
	'wp_cache_get_multiple_salted' => '6.9.0',
	'wp_cache_set_multiple_salted' => '6.9.0',
];

/*
Not suitable in isolated PHPUnit env:

wp_cache_add_multiple (compat)       // why: redundant with cache.php (same function defined there without compat guard)
wp_cache_set_multiple (compat)       // why: redundant with cache.php
wp_cache_get_multiple (compat)       // why: redundant with cache.php
wp_cache_delete_multiple (compat)    // why: redundant with cache.php
wp_cache_flush_runtime (compat)      // why: redundant with cache.php; compat version calls _doing_it_wrong + wp_cache_supports
wp_cache_flush_group (compat)        // why: redundant with cache.php
wp_cache_supports (compat)           // why: redundant with cache.php (compat always returns false)
wp_cache_switch_to_blog (compat)     // why: redundant with cache.php; calls wp_cache_switch_to_blog_fallback (undefined)
*/

