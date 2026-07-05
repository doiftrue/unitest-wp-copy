<?php

return [
	'_wp_reset_invalid_menu_item_parent' => '6.2.0',
	'wp_map_nav_menu_locations'          => '4.9.0',
	'_is_valid_nav_menu_item'            => '3.2.0',
	'unregister_nav_menu'                => '3.1.0',
	'register_nav_menus'                 => '3.0.0',
	'register_nav_menu'                  => '3.0.0',
	'get_registered_nav_menus'           => '3.0.0 mockable',
];

/*
Not suitable in isolated PHPUnit env:

wp_get_nav_menu_object                               // why: depends on get_term()
is_nav_menu                                          // why: depends on wp_get_nav_menu_object()
get_nav_menu_locations                               // why: depends on get_theme_mod()
has_nav_menu                                         // why: depends on get_nav_menu_locations()
wp_get_nav_menu_name                                 // why: depends on get_nav_menu_locations()
is_nav_menu_item                                     // why: depends on get_post_type()
wp_create_nav_menu                                   // why: depends on wp_insert_term() taxonomy DB chain
wp_delete_nav_menu                                   // why: depends on wp_get_nav_menu_object()
wp_update_nav_menu_object                            // why: depends on wp_update_term() taxonomy DB chain
wp_update_nav_menu_item                              // why: depends on WP_Post runtime
wp_get_nav_menus                                     // why: depends on get_terms()
wp_get_nav_menu_items                                // why: depends on wp_get_nav_menu_object()
update_menu_item_cache                               // why: depends on get_post_meta()
wp_setup_nav_menu_item                               // why: depends on WP_Post runtime
wp_get_associated_nav_menu_items                     // why: depends on WP_Query runtime
_wp_delete_post_menu_item                            // why: depends on wp_get_associated_nav_menu_items()
_wp_delete_tax_menu_item                             // why: depends on wp_get_associated_nav_menu_items()
_wp_delete_customize_changeset_dependent_auto_drafts // why: depends on get_post()
_wp_auto_add_pages_to_menu                           // why: reads unresolved option `nav_menu_options`
_wp_menus_changed                                    // why: reads unresolved option `theme_switch_menu_locations`
*/
