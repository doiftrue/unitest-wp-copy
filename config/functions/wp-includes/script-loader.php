<?php

return [
	// Pure in-memory utility for handle reordering.
	'wp_prototype_before_jquery' => '',
	// Minimal helper functions required by WP_Scripts when rendering tags.
	'wp_sanitize_script_attributes' => '',
	'wp_get_script_tag'             => '',
	'wp_print_script_tag'           => '',
	'wp_get_inline_script_tag'      => '',
	'wp_print_inline_script_tag'    => '',
	'_print_scripts'                => '',
	// Pure string utility for stripping outer <script>...</script> literals.
	'wp_remove_surrounding_empty_script_tags' => '',
	// Pure block-node filter helper.
	'wp_filter_out_block_nodes' => '',
	// Pure CSS URL normalization helper.
	'_wp_normalize_relative_css_links' => '',
];

/*
Not suitable in isolated PHPUnit env (script/style lifecycle + runtime context dependency):

wp_scripts_get_suffix                           // why: require ABSPATH . WPINC . '/version.php' (filesystem/runtime path dependency).
wp_default_scripts                              // why: massive bootstrap registry + admin/theme runtime coupling.
wp_default_styles                               // why: massive bootstrap registry + admin/theme runtime coupling.
wp_default_packages_vendor                      // why: package/bootstrap runtime setup.
wp_default_packages_scripts                     // why: package/bootstrap runtime setup.
wp_default_packages_inline_scripts              // why: package/bootstrap runtime setup.
wp_default_packages                             // why: package/bootstrap runtime setup.
print_head_scripts                              // why: full script concat/output lifecycle dependency.
print_footer_scripts                            // why: full script concat/output lifecycle dependency.
wp_print_head_scripts                           // why: full script lifecycle dependency.
wp_print_footer_scripts                         // why: full script lifecycle dependency.
wp_enqueue_scripts                              // why: event bridge only, meaningful in full runtime.
print_admin_styles                              // why: admin runtime + style queue lifecycle.
print_late_styles                               // why: style queue lifecycle in full runtime.
_print_styles                                   // why: concatenation/output lifecycle dependency.
script_concat_settings                          // why: depends on admin/request runtime flags/constants.
wp_common_block_scripts_and_styles              // why: block editor/runtime registries.
wp_enqueue_global_styles                        // why: block runtime + registries + additional APIs.
wp_enqueue_registered_block_scripts_and_styles  // why: block runtime + registries + additional APIs.
enqueue_block_styles_assets                     // why: block runtime + registries + additional APIs.
enqueue_editor_block_styles_assets              // why: block runtime + registries + additional APIs.
wp_enqueue_editor_block_directory_assets        // why: block runtime + registries + additional APIs.
wp_enqueue_editor_format_library_assets         // why: block runtime + registries + additional APIs.
wp_enqueue_global_styles_css_custom_properties  // why: block runtime + registries + additional APIs.
wp_enqueue_block_support_styles                 // why: block runtime + registries + additional APIs.
wp_enqueue_stored_styles                        // why: block runtime + registries + additional APIs.
wp_enqueue_block_style                          // why: block runtime + registries + additional APIs.
wp_should_load_block_editor_scripts_and_styles  // why: request/admin/runtime context dependency.
wp_should_load_separate_core_block_assets       // why: request/admin/runtime context dependency.
wp_should_load_block_assets_on_demand           // why: request/admin/runtime context dependency.
wp_localize_jquery_ui_datepicker                // why: editor/admin/context-sensitive localization runtime.
wp_localize_community_events                    // why: editor/admin/context-sensitive localization runtime.
wp_maybe_inline_styles                          // why: reads CSS files by absolute path (filesystem runtime dependency).
wp_enqueue_stored_styles                        // why: depends on global-styles storage/runtime.
wp_enqueue_block_style                          // why: depends on block registration + filesystem-aware URL/path args.
wp_enqueue_classic_theme_styles                 // why: classic-theme runtime flow.
*/
