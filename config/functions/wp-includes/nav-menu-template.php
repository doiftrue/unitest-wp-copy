<?php

return [
	'wp_nav_menu_remove_menu_item_has_children_class' => '6.2.0',
	'_nav_menu_item_id_use_once'                      => '3.0.1',
];

/*
Not suitable in isolated PHPUnit env:

wp_nav_menu                       // why: depends on wp_get_nav_menu_items() → DB + Walker_Nav_Menu
_wp_menu_item_classes_by_context  // why: depends on get_queried_object() → WP_Query + DB
walk_nav_menu_tree                // why: depends on Walker_Nav_Menu class (not available)
*/
