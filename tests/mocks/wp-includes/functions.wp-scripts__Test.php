<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class functions_wp_scripts_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
		$GLOBALS['wp_scripts'] = null;
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__wp_scripts(): void {
		$this->assertInstanceOf( WP_Scripts::class, wp_scripts() );
	}

	public function test__wp_scripts_wp_mock_handler(): void {
		$registry = new WP_Scripts();
		\WP_Mock::userFunction( 'wp_scripts', [ 'return' => $registry ] );
		$this->assertSame( $registry, wp_scripts() );
	}
}
