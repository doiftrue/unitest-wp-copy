<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class ai_client__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__wp_supports_ai(): void {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "wp_supports_ai() not exists on WP $wp_ver" );
		}

		$this->assertTrue( wp_supports_ai() );
	}

	public function test__wp_supports_ai__mockable_handler(): void {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "wp_supports_ai() not exists on WP $wp_ver" );
		}

		\WP_Mock::userFunction( 'wp_supports_ai', [ 'return' => false ] );
		$this->assertFalse( wp_supports_ai() );
	}
}
