<?php

class screen__Test extends \PHPUnit\Framework\TestCase {

	public static function screen_columns_filter() {
		return [
			'title' => 'Title',
		];
	}

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['current_screen'] = null;
		$GLOBALS['wp_post_types'] = [];
		$GLOBALS['wp_taxonomies'] = [];
	}

	protected function tearDown(): void {
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
		$GLOBALS['wp_actions'] = [];

		parent::tearDown();
	}

	public function test__get_column_headers() {
		add_filter( 'manage_screen-columns_columns', [ self::class, 'screen_columns_filter' ] );

		$screen = WP_Screen::get( 'screen-columns' );

		$this->assertSame(
			[ 'title' => 'Title' ],
			get_column_headers( $screen )
		);
	}

	public function test__add_screen_option() {
		$screen = WP_Screen::get( 'screen-option' );
		$screen->set_current_screen();

		add_screen_option( 'per_page', [ 'default' => 25 ] );

		$this->assertSame( [ 'default' => 25 ], $screen->get_option( 'per_page' ) );
	}

	public function test__get_current_screen() {
		$this->assertNull( get_current_screen() );

		$screen = WP_Screen::get( 'screen-current' );
		$screen->set_current_screen();

		$this->assertSame( $screen, get_current_screen() );
	}

	public function test__set_current_screen() {
		set_current_screen( 'screen-function' );

		$this->assertInstanceOf( WP_Screen::class, $GLOBALS['current_screen'] );
		$this->assertSame( 'screen-function', $GLOBALS['current_screen']->id );
	}
}
