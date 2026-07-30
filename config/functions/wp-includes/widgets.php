<?php

return [
	'wp_parse_widget_id'    => '5.8.0',
	'is_registered_sidebar' => '4.4.0',
	'_get_widget_id_base'   => '2.8.0',
	'register_sidebars'     => '2.2.0',
	'register_sidebar'      => '2.2.0',
	'unregister_sidebar'    => '2.2.0',
];

/*
Not suitable in isolated PHPUnit env:

register_widget                              // why: delegates to WP_Widget_Factory global
unregister_widget                            // why: delegates to WP_Widget_Factory global
wp_register_sidebar_widget                   // why: mutates widget registries and inspects WP_Widget object callbacks
wp_widget_description                        // why: reads the live registered-widget registry
wp_sidebar_description                       // why: reads the live registered-sidebar registry
wp_unregister_sidebar_widget                 // why: mutates the live registered-widget registry
wp_register_widget_control                    // why: mutates widget-control registries and inspects WP_Widget object callbacks
_register_widget_update_callback             // why: registers admin request callbacks against live widget state
_register_widget_form_callback               // why: registers admin form callbacks against live widget state
wp_unregister_widget_control                 // why: mutates the live widget-control registry
dynamic_sidebar                              // why: renders callbacks from live sidebar/widget registries and query context
is_active_widget                             // why: depends on registered widget callbacks and persisted sidebar assignments
is_dynamic_sidebar                           // why: depends on registered sidebars and persisted widget assignments
is_active_sidebar                            // why: depends on persisted widget assignments through wp_get_sidebars_widgets()
wp_get_sidebars_widgets                      // why: reads the unresolved sidebars_widgets option and admin request state
wp_get_sidebar                               // why: reads the live sidebar registry populated by the widget lifecycle
wp_set_sidebars_widgets                      // why: writes the persistent sidebars_widgets option
wp_get_widget_defaults                       // why: derives defaults from the live registered-sidebar registry
wp_convert_widget_settings                   // why: reads and writes caller-defined widget options and depends on admin request state
the_widget                                   // why: renders instances from the unavailable WP_Widget_Factory global
_wp_sidebars_changed                         // why: triggers theme-change widget remapping and persistent option updates
retrieve_widgets                             // why: depends on theme mods, live widget registries, and persistent option writes
wp_map_sidebars_widgets                      // why: depends on theme mods and the live registered-sidebar/widget registries
_wp_remove_unregistered_widgets              // why: defaults to the live registered-widget registry
wp_widget_rss_output                         // why: depends on remote feed I/O and SimplePie item objects
wp_widget_rss_form                           // why: emits admin form markup and belongs to the widget admin lifecycle
wp_widget_rss_process                        // why: optionally performs remote feed I/O through fetch_feed()
wp_widgets_init                              // why: requires all core WP_Widget classes and the widget startup lifecycle
wp_setup_widgets_block_editor                // why: mutates live theme-support state during theme setup
wp_use_widgets_block_editor                  // why: depends on live theme-support state
wp_find_widgets_sidebar                      // why: depends on persisted sidebar assignments through wp_get_sidebars_widgets()
wp_assign_widget_to_sidebar                  // why: reads and writes persistent sidebar assignments
wp_render_widget                             // why: renders callbacks from live sidebar/widget registries
wp_render_widget_control                     // why: renders callbacks from the live widget-control registry
wp_check_widget_editor_deps                  // why: depends on live WP_Scripts and WP_Styles queue objects
_wp_block_theme_register_classic_sidebars    // why: depends on block-theme detection, theme mods, and the live sidebar registry
*/
