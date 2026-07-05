<?php

return [
	'wp_get_first_block'           => '6.3.0',
	'get_default_block_categories' => '5.8.0',
	'get_allowed_block_types'      => '5.8.0',
];

/*
Not suitable in isolated PHPUnit env:

get_block_categories                                // why: requires WP_Block_Editor_Context and WP_Post editor context runtime.
get_default_block_editor_settings                   // why: depends on current-user capabilities, media options, and editor runtime state.
get_legacy_widget_block_editor_settings             // why: depends on widget, theme-support, and editor settings runtime.
_wp_get_iframed_editor_assets                       // why: depends on script/style enqueue and rendering lifecycle.
wp_get_post_content_block_attributes                // why: depends on posts, block templates, and current request state.
get_block_editor_settings                           // why: depends on posts, themes, assets, registries, and editor request state.
block_editor_rest_api_preload                       // why: depends on REST server dispatch and request lifecycle.
get_block_editor_theme_styles                       // why: depends on theme filesystem and global styles runtime.
get_classic_theme_supports_block_editor_settings    // why: depends on theme support and style registry runtime.
wp_initialize_site_preview_hooks                    // why: mutates hooks for the site-preview request lifecycle.
*/
