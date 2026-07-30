<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class widgets__mockable__Test extends \PHPUnit\Framework\TestCase {

	private array $registered_sidebars;

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();

		$this->registered_sidebars = $GLOBALS['wp_registered_sidebars'];
		$GLOBALS['wp_registered_sidebars'] = [];
	}

	protected function tearDown(): void {
		$GLOBALS['wp_registered_sidebars'] = $this->registered_sidebars;
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__is_registered_sidebar(): void {
		$this->assertFalse( is_registered_sidebar( 'unitest-sidebar' ) );
		$GLOBALS['wp_registered_sidebars']['unitest-sidebar'] = [ 'id' => 'unitest-sidebar' ];
		$this->assertTrue( is_registered_sidebar( 'unitest-sidebar' ) );
	}

	public function test__is_registered_sidebar__mockable_handler(): void {
		\WP_Mock::userFunction( 'is_registered_sidebar', [ 'return' => true ] );
		$this->assertTrue( is_registered_sidebar( 'mocked-sidebar' ) );
	}
}
