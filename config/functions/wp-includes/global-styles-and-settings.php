<?php

return [
	'wp_get_block_name_from_theme_json_path' => '6.3.0',
];

/*
Not suitable in isolated PHPUnit env:

wp_get_global_settings              // why: requires WP_Theme_JSON_Resolver and theme.json runtime.
wp_get_global_styles                // why: requires WP_Theme_JSON_Resolver and theme.json runtime.
wp_get_global_stylesheet            // why: requires WP_Theme_JSON and style engine runtime.
wp_add_global_styles_for_blocks     // why: depends on style enqueue and block registry lifecycle.
wp_theme_has_theme_json             // why: depends on theme filesystem paths and development-mode state.
wp_clean_theme_json_cache           // why: requires WP_Theme_JSON_Resolver.
wp_get_theme_directory_pattern_slugs // why: requires WP_Theme_JSON_Resolver.
wp_get_theme_data_custom_templates  // why: requires WP_Theme_JSON_Resolver.
wp_get_theme_data_template_parts    // why: requires WP_Theme_JSON_Resolver.
wp_get_block_css_selector           // why: requires WP_Theme_JSON selector utilities for non-root public behavior.
*/
