<?php

return [
	'_inject_theme_attribute_in_template_part_block'    => '6.4.0',
	'_remove_theme_attribute_from_template_part_block'  => '6.4.0',
	'get_template_hierarchy'                            => '6.1.0',
	'get_allowed_block_template_part_areas'             => '5.9.0',
	'_filter_block_template_part_area'                  => '5.9.0',
	'get_default_block_template_types'                  => '5.9.0',
	'_flatten_blocks'                                   => '5.9.0',
];

/*
Not suitable in isolated PHPUnit env:

get_block_theme_folders                                             // why: depends on WP_Theme and theme filesystem state.
_get_block_templates_paths                                          // why: scans theme filesystem directories.
_get_block_template_file                                            // why: reads theme template files.
_get_block_templates_files                                          // why: scans and reads theme template files.
_add_block_template_info                                            // why: depends on theme.json resolver runtime.
_add_block_template_part_area_info                                  // why: depends on template-area constants and theme.json metadata.
_build_block_template_result_from_file                              // why: depends on WP_Block_Template and theme filesystem reads.
_wp_build_title_and_description_for_single_post_type_block_template // why: depends on post type objects and WP_Block_Template.
_wp_build_title_and_description_for_taxonomy_block_template         // why: depends on taxonomy objects and WP_Block_Template.
_build_block_template_object_from_post_object                       // why: depends on WP_Block_Template, posts, terms, and metadata.
_build_block_template_result_from_post                              // why: depends on posts, terms, metadata, and theme files.
get_block_templates                                                 // why: depends on post queries, theme filesystem, and template registries.
get_block_template                                                  // why: depends on posts and theme filesystem template storage.
get_block_file_template                                             // why: depends on theme filesystem and WP_Block_Template.
block_template_part                                                 // why: depends on dynamic block rendering.
block_header_area                                                   // why: depends on dynamic block rendering.
block_footer_area                                                   // why: depends on dynamic block rendering.
wp_is_theme_directory_ignored                                      // why: depends on theme root filesystem paths.
wp_generate_block_templates_export_file                             // why: depends on theme files, posts, ZIP filesystem, and export runtime.
inject_ignored_hooked_blocks_metadata_attributes                    // why: depends on template posts and block-hooks persistence runtime.
*/
