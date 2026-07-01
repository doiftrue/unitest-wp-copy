<?php

return [
	'remove_block_asset_path_prefix'                              => '5.5.0',
	'generate_block_asset_handle'                                 => '5.5.0',
	'unregister_block_type'                                       => '5.0.0',
	'get_dynamic_block_names'                                     => '5.0.0 mockable',
	'get_hooked_blocks'                                           => '6.4.0 mockable',
	'insert_hooked_blocks'                                        => '6.5.0',
	'set_ignored_hooked_blocks_metadata'                          => '6.5.0',
	'remove_serialized_parent_block'                              => '6.6.0',
	'extract_serialized_parent_block'                             => '6.7.0',
	'insert_hooked_blocks_and_set_ignored_hooked_blocks_metadata' => '6.6.0',
	'make_after_block_visitor'                                    => '6.4.0',
	'serialize_block_attributes'                                  => '5.3.1',
	'strip_core_block_namespace'                                  => '5.3.1',
	'get_comment_delimited_block_content'                         => '5.3.1',
	'serialize_block'                                             => '5.3.1',
	'serialize_blocks'                                            => '5.3.1',
	'traverse_and_serialize_block'                                => '6.4.0',
	'traverse_and_serialize_blocks'                               => '6.4.0',
	'filter_block_content'                                        => '5.3.1',
	'_filter_block_content_callback'                              => '6.2.1',
	'filter_block_kses'                                           => '5.3.1',
	'filter_block_kses_value'                                     => '5.3.1',
	'filter_block_core_template_part_attributes'                  => '6.5.5',
	'excerpt_remove_footnotes'                                    => '6.3.0',
	'parse_blocks'                                                => '5.0.0',
	'_restore_wpautop_hook'                                       => '5.0.0',
	'register_block_style'                                        => '5.3.0',
	'unregister_block_style'                                      => '5.3.0',
	'block_has_support'                                           => '5.8.0',
	'wp_migrate_old_typography_shape'                             => '5.8.0',
	'get_query_pagination_arrow'                                  => '5.9.0',
	'get_comments_pagination_arrow'                               => '6.0.0',
	'_wp_filter_post_meta_footnotes'                              => '6.3.2',
	'_wp_footnotes_kses_init_filters'                             => '6.3.2',
	'_wp_footnotes_remove_filters'                                => '6.3.2',
	'_wp_footnotes_force_filtered_html_on_import_filter'          => '6.3.2',
];

/*
Not suitable in isolated PHPUnit env:

get_block_asset_url                              // why: depends on WordPress/theme/plugin filesystem paths and URL bootstrap.
register_block_script_module_id                  // why: reads asset files and depends on script modules and Interactivity API runtime.
register_block_script_handle                     // why: reads asset files and depends on the script registration runtime.
register_block_style_handle                      // why: depends on core/theme filesystem paths and the style registration runtime.
get_block_metadata_i18n_schema                   // why: reads block-i18n.json relative to the WordPress source directory.
wp_register_block_types_from_metadata_collection // why: depends on WP_Block_Metadata_Registry and metadata filesystem collections.
wp_register_block_metadata_collection            // why: depends on WP_Block_Metadata_Registry and metadata filesystem collections.
register_block_type_from_metadata                // why: depends on block metadata files, asset registration, theme support, and translation runtime.
register_block_type                              // why: its documented path input requires register_block_type_from_metadata() and filesystem metadata runtime.
has_blocks                                       // why: non-string inputs depend on get_post() and the post runtime.
has_block                                        // why: non-string inputs depend on get_post() and the post runtime.
apply_block_hooks_to_content                     // why: depends on get_post() and template-part theme injection runtime.
apply_block_hooks_to_content_from_post_object    // why: depends on WP_Post, post meta, and block hooks post runtime.
update_ignored_hooked_blocks_postmeta            // why: depends on WP_Post, get_post(), and post meta persistence.
insert_hooked_blocks_into_rest_response          // why: depends on WP_REST_Response and the REST/post lifecycle.
make_before_block_visitor                        // why: invokes unavailable template-part theme injection logic.
resolve_pattern_blocks                           // why: depends on WP_Block_Patterns_Registry and pattern runtime.
excerpt_remove_blocks                            // why: depends on render_block() and the dynamic block rendering runtime.
_excerpt_render_inner_blocks                     // why: depends on render_block() and the dynamic block rendering runtime.
render_block                                     // why: depends on WP_Block and the registered dynamic block rendering runtime.
do_blocks                                        // why: depends on render_block() and the content filter lifecycle.
block_version                                    // why: depends on has_blocks(), whose post-object branch requires the post runtime.
build_query_vars_from_query_block                // why: depends on post type, taxonomy, options, and query runtime.
build_comment_query_vars_from_block              // why: depends on users, options, query vars, and WP_Comment_Query.
_wp_footnotes_kses_init                          // why: depends on the current-user capability runtime.
_wp_enqueue_auto_register_blocks                 // why: depends on editor script enqueue lifecycle.
*/
