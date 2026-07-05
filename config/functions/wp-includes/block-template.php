<?php

return [
	'_block_template_add_skip_link'                     => '7.0.0',
	'_block_template_viewport_meta_tag'                 => '5.8.0',
	'_strip_template_file_suffix'                       => '5.8.0',
	'_block_template_render_without_post_block_context' => '5.8.0',
];

/*
Not suitable in isolated PHPUnit env:

_add_template_loader_filters       // why: mutates hooks for the template request lifecycle.
wp_render_empty_block_template_warning // why: depends on current-user capabilities and template rendering context.
locate_block_template              // why: depends on theme filesystem, query state, and template resolution runtime.
resolve_block_template             // why: depends on query conditionals, posts, and block template storage.
_block_template_render_title_tag   // why: depends on document-title and theme support request state.
get_the_block_template_html        // why: depends on global query/post state and dynamic block rendering.
_resolve_template_for_new_post     // why: mutates WP_Query for the post request lifecycle.
register_block_template            // why: requires WP_Block_Templates_Registry.
unregister_block_template          // why: requires WP_Block_Templates_Registry.
*/
