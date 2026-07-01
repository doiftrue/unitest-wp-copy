<?php

class screen_mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['current_screen'] = null;
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		unset( $GLOBALS['current_screen'] );

		parent::tearDown();
	}

	public function test__get_current_screen__fallback() {
		$this->assertNull( get_current_screen() );

		$screen = WP_Screen::get( 'screen-current' );
		$screen->set_current_screen();

		$this->assertSame( $screen, get_current_screen() );
	}

	public function test__get_current_screen__handler() {
		$expected = (object) [ 'id' => 'mocked-screen' ];

		\WP_Mock::userFunction( 'get_current_screen' )
			->once()
			->andReturn( $expected );

		$this->assertSame( $expected, get_current_screen() );
	}
}
