<?php

// ------------------auto-generated---------------------

// wp-includes/nav-menu.php (WP 7.0.2)
if( ! function_exists( '_wp_reset_invalid_menu_item_parent' ) ) :
	function _wp_reset_invalid_menu_item_parent( $menu_item_data ) {
		if ( ! is_array( $menu_item_data ) ) {
			return $menu_item_data;
		}
	
		if (
			! empty( $menu_item_data['ID'] ) &&
			! empty( $menu_item_data['menu_item_parent'] ) &&
			(int) $menu_item_data['ID'] === (int) $menu_item_data['menu_item_parent']
		) {
			$menu_item_data['menu_item_parent'] = 0;
		}
	
		return $menu_item_data;
	}
endif;

// wp-includes/nav-menu.php (WP 7.0.2)
if( ! function_exists( 'wp_map_nav_menu_locations' ) ) :
	function wp_map_nav_menu_locations( $new_nav_menu_locations, $old_nav_menu_locations ) {
		$registered_nav_menus   = get_registered_nav_menus();
		$new_nav_menu_locations = array_intersect_key( $new_nav_menu_locations, $registered_nav_menus );
	
		// Short-circuit if there are no old nav menu location assignments to map.
		if ( empty( $old_nav_menu_locations ) ) {
			return $new_nav_menu_locations;
		}
	
		// If old and new theme have just one location, map it and we're done.
		if ( 1 === count( $old_nav_menu_locations ) && 1 === count( $registered_nav_menus ) ) {
			$new_nav_menu_locations[ key( $registered_nav_menus ) ] = array_pop( $old_nav_menu_locations );
			return $new_nav_menu_locations;
		}
	
		$old_locations = array_keys( $old_nav_menu_locations );
	
		// Map locations with the same slug.
		foreach ( $registered_nav_menus as $location => $name ) {
			if ( in_array( $location, $old_locations, true ) ) {
				$new_nav_menu_locations[ $location ] = $old_nav_menu_locations[ $location ];
				unset( $old_nav_menu_locations[ $location ] );
			}
		}
	
		// If there are no old nav menu locations left, then we're done.
		if ( empty( $old_nav_menu_locations ) ) {
			return $new_nav_menu_locations;
		}
	
		/*
		 * If old and new theme both have locations that contain phrases
		 * from within the same group, make an educated guess and map it.
		 */
		$common_slug_groups = array(
			array( 'primary', 'menu-1', 'main', 'header', 'navigation', 'top' ),
			array( 'secondary', 'menu-2', 'footer', 'subsidiary', 'bottom' ),
			array( 'social' ),
		);
	
		// Go through each group...
		foreach ( $common_slug_groups as $slug_group ) {
	
			// ...and see if any of these slugs...
			foreach ( $slug_group as $slug ) {
	
				// ...and any of the new menu locations...
				foreach ( $registered_nav_menus as $new_location => $name ) {
	
					// ...actually match!
					if ( is_string( $new_location ) && false === stripos( $new_location, $slug ) && false === stripos( $slug, $new_location ) ) {
						continue;
					} elseif ( is_numeric( $new_location ) && $new_location !== $slug ) {
						continue;
					}
	
					// Then see if any of the old locations...
					foreach ( $old_nav_menu_locations as $location => $menu_id ) {
	
						// ...and any slug in the same group...
						foreach ( $slug_group as $slug ) {
	
							// ... have a match as well.
							if ( is_string( $location ) && false === stripos( $location, $slug ) && false === stripos( $slug, $location ) ) {
								continue;
							} elseif ( is_numeric( $location ) && $location !== $slug ) {
								continue;
							}
	
							// Make sure this location wasn't mapped and removed previously.
							if ( ! empty( $old_nav_menu_locations[ $location ] ) ) {
	
								// We have a match that can be mapped!
								$new_nav_menu_locations[ $new_location ] = $old_nav_menu_locations[ $location ];
	
								// Remove the mapped location so it can't be mapped again.
								unset( $old_nav_menu_locations[ $location ] );
	
								// Go back and check the next new menu location.
								continue 3;
							}
						} // End foreach ( $slug_group as $slug ).
					} // End foreach ( $old_nav_menu_locations as $location => $menu_id ).
				} // End foreach foreach ( $registered_nav_menus as $new_location => $name ).
			} // End foreach ( $slug_group as $slug ).
		} // End foreach ( $common_slug_groups as $slug_group ).
	
		return $new_nav_menu_locations;
	}
endif;

// wp-includes/nav-menu.php (WP 7.0.2)
if( ! function_exists( '_is_valid_nav_menu_item' ) ) :
	function _is_valid_nav_menu_item( $item ) {
		return empty( $item->_invalid );
	}
endif;

// wp-includes/nav-menu.php (WP 7.0.2)
if( ! function_exists( 'unregister_nav_menu' ) ) :
	function unregister_nav_menu( $location ) {
		global $_wp_registered_nav_menus;
	
		if ( is_array( $_wp_registered_nav_menus ) && isset( $_wp_registered_nav_menus[ $location ] ) ) {
			unset( $_wp_registered_nav_menus[ $location ] );
			if ( empty( $_wp_registered_nav_menus ) ) {
				_remove_theme_support( 'menus' );
			}
			return true;
		}
		return false;
	}
endif;

// wp-includes/nav-menu.php (WP 7.0.2)
if( ! function_exists( 'register_nav_menus' ) ) :
	function register_nav_menus( $locations = array() ) {
		global $_wp_registered_nav_menus;
	
		add_theme_support( 'menus' );
	
		foreach ( $locations as $key => $value ) {
			if ( is_int( $key ) ) {
				_doing_it_wrong( __FUNCTION__, __( 'Nav menu locations must be strings.' ), '5.3.0' );
				break;
			}
		}
	
		$_wp_registered_nav_menus = array_merge( (array) $_wp_registered_nav_menus, $locations );
	}
endif;

// wp-includes/nav-menu.php (WP 7.0.2)
if( ! function_exists( 'register_nav_menu' ) ) :
	function register_nav_menu( $location, $description ) {
		register_nav_menus( array( $location => $description ) );
	}
endif;

