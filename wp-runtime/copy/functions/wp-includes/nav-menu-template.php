<?php

// ------------------auto-generated---------------------

// wp-includes/nav-menu-template.php (WP 6.9.4)
if( ! function_exists( 'wp_nav_menu_remove_menu_item_has_children_class' ) ) :
	function wp_nav_menu_remove_menu_item_has_children_class( $classes, $menu_item, $args = false, $depth = false ) {
		/*
		 * Account for the filter being called without the $args or $depth parameters.
		 *
		 * This occurs when a theme uses a custom walker calling the `nav_menu_css_class`
		 * filter using the legacy formats prior to the introduction of the $args and
		 * $depth parameters.
		 *
		 * As both of these parameters are required for this function to determine
		 * both the current and maximum depth of the menu tree, the function does not
		 * attempt to remove the `menu-item-has-children` class if these parameters
		 * are not set.
		 */
		if ( false === $depth || false === $args ) {
			return $classes;
		}
	
		// Max-depth is 1-based.
		$max_depth = isset( $args->depth ) ? (int) $args->depth : 0;
		// Depth is 0-based so needs to be increased by one.
		$depth = $depth + 1;
	
		// Complete menu tree is displayed.
		if ( 0 === $max_depth ) {
			return $classes;
		}
	
		/*
		 * Remove the `menu-item-has-children` class from bottom level menu items.
		 * -1 is used to display all menu items in one level so the class should
		 * be removed from all menu items.
		 */
		if ( -1 === $max_depth || $depth >= $max_depth ) {
			$classes = array_diff( $classes, array( 'menu-item-has-children' ) );
		}
	
		return $classes;
	}
endif;

// wp-includes/nav-menu-template.php (WP 6.9.4)
if( ! function_exists( '_nav_menu_item_id_use_once' ) ) :
	function _nav_menu_item_id_use_once( $id, $item ) {
		static $_used_ids = array();
	
		if ( in_array( $item->ID, $_used_ids, true ) ) {
			return '';
		}
	
		$_used_ids[] = $item->ID;
	
		return $id;
	}
endif;

