<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class functions_wp_styles_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
		$GLOBALS['wp_styles'] = null;
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__wp_styles(): void {
		$this->assertInstanceOf( WP_Styles::class, wp_styles() );
	}

	public function test__wp_styles_wp_mock_handler(): void {
		$registry = new WP_Styles();
		\WP_Mock::userFunction( 'wp_styles', [ 'return' => $registry ] );
		$this->assertSame( $registry, wp_styles() );
	}
}
