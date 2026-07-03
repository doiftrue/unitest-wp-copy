<?php

class nav_menu_template__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_nav_menu_remove_menu_item_has_children_class() {
		$classes   = [ 'menu-item', 'menu-item-has-children', 'current-menu-item' ];
		$menu_item = (object) [ 'ID' => 1 ];

		// Without $args or $depth — returns classes unchanged
		$result = wp_nav_menu_remove_menu_item_has_children_class( $classes, $menu_item );
		$this->assertSame( $classes, $result );

		$result = wp_nav_menu_remove_menu_item_has_children_class( $classes, $menu_item, false, false );
		$this->assertSame( $classes, $result );

		// max_depth = 0 (show all) — returns classes unchanged
		$args = (object) [ 'depth' => 0 ];
		$result = wp_nav_menu_remove_menu_item_has_children_class( $classes, $menu_item, $args, 0 );
		$this->assertSame( $classes, $result );

		// max_depth = 2, depth = 0 (level 1) — keeps class
		$args = (object) [ 'depth' => 2 ];
		$result = wp_nav_menu_remove_menu_item_has_children_class( $classes, $menu_item, $args, 0 );
		$this->assertContains( 'menu-item-has-children', $result );

		// max_depth = 2, depth = 1 (level 2, at max) — removes class
		$result = wp_nav_menu_remove_menu_item_has_children_class( $classes, $menu_item, $args, 1 );
		$this->assertNotContains( 'menu-item-has-children', $result );
		$this->assertContains( 'menu-item', $result );

		// max_depth = -1 (flat display) — always removes class
		$args = (object) [ 'depth' => -1 ];
		$result = wp_nav_menu_remove_menu_item_has_children_class( $classes, $menu_item, $args, 0 );
		$this->assertNotContains( 'menu-item-has-children', $result );
	}

	public function test___nav_menu_item_id_use_once() {
		$item1 = (object) [ 'ID' => 100 ];
		$item2 = (object) [ 'ID' => 200 ];

		// First use — returns the id
		$this->assertSame( 'menu-item-100', _nav_menu_item_id_use_once( 'menu-item-100', $item1 ) );

		// Second use of same item — returns empty
		$this->assertSame( '', _nav_menu_item_id_use_once( 'menu-item-100', $item1 ) );

		// Different item — returns the id
		$this->assertSame( 'menu-item-200', _nav_menu_item_id_use_once( 'menu-item-200', $item2 ) );
	}
}
