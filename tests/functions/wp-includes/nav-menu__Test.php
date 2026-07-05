<?php

class nav_menu__Test extends \PHPUnit\Framework\TestCase {
	public function test___is_valid_nav_menu_item() {
		$this->assertTrue( _is_valid_nav_menu_item( (object) [] ) );
		$this->assertFalse( _is_valid_nav_menu_item( (object) [ '_invalid' => true ] ) );
	}

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_wp_registered_nav_menus'] = [];
	}

	public function test__register_nav_menus() {
		register_nav_menus( [ 'primary' => 'Primary' ] );
		$this->assertSame( [ 'primary' => 'Primary' ], get_registered_nav_menus() );
	}

	public function test__unregister_nav_menu() {
		register_nav_menu( 'primary', 'Primary' );
		$this->assertTrue( unregister_nav_menu( 'primary' ) );
		$this->assertSame( [], get_registered_nav_menus() );
	}

	public function test__register_nav_menu() {
		register_nav_menu( 'footer', 'Footer' );
		$this->assertSame( 'Footer', get_registered_nav_menus()['footer'] );
	}

	public function test__get_registered_nav_menus() {
		$this->assertSame( [], get_registered_nav_menus() );
	}

	public function test__wp_map_nav_menu_locations() {
		register_nav_menu( 'primary', 'Primary' );
		$this->assertSame( [ 'primary' => 7 ], wp_map_nav_menu_locations( [], [ 'primary' => 7 ] ) );
	}

	public function test___wp_reset_invalid_menu_item_parent() {
		$this->assertSame( 0, _wp_reset_invalid_menu_item_parent( [ 'ID' => 5, 'menu_item_parent' => 5 ] )['menu_item_parent'] );
		$this->assertSame( 'invalid', _wp_reset_invalid_menu_item_parent( 'invalid' ) );
	}
}
