<?php

return [
	'wp_js_dataset_name'                      => '6.9.0', // Pure string dataset-name conversion helpers.
	'wp_html_custom_data_attribute_name'      => '6.9.0',
	'wp_remove_surrounding_empty_script_tags' => '6.4.0', // Pure string utility for stripping outer <script>...</script> literals.
	'wp_filter_out_block_nodes'               => '6.1.0', // Pure block-node filter helper.
	'_wp_normalize_relative_css_links'        => '5.9.0', // Pure CSS URL normalization helper.
	'wp_sanitize_script_attributes'           => '5.7.0', // Minimal helpers required by WP_Scripts when rendering tags.
	'wp_get_script_tag'                       => '5.7.0',
	'wp_print_script_tag'                     => '5.7.0',
	'wp_get_inline_script_tag'                => '5.7.0',
	'wp_print_inline_script_tag'              => '5.7.0',
	'wp_prototype_before_jquery'              => '2.3.1', // Pure in-memory utility for handle reordering.
	'_print_scripts'                          => '0.0.0',
];

/*
Not suitable in isolated PHPUnit env:

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
wp_register_tinymce_scripts                     // why: $scripts registration + script_concat_settings dependency.
wp_register_development_scripts                 // why: modifies $scripts->registered, dev-only lifecycle.
wp_get_script_polyfill                          // why: $scripts->registered access + apply_filters + output-oriented.
wp_tinymce_inline_scripts                       // why: multiple apply_filters, TinyMCE editor lifecycle.
wp_just_in_time_script_localization             // why: wp_localize_script + admin lifecycle.
wp_style_loader_src                             // why: get_user_option + wp_installing + admin colors.
_wp_footer_scripts                              // why: output: print_late_styles + print_footer_scripts.
wp_enqueue_command_palette_assets               // why: depends on $menu/$submenu globals + admin lifecycle.
wp_load_classic_theme_block_styles_on_demand    // why: wp_is_block_theme + output buffer lifecycle.
wp_hoist_late_printed_styles                    // why: complex output buffer lifecycle + footer rendering.
*/
