<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class functions__custom_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__wp_get_wp_version() {
		if( $wp_ver = wp_version_compare( '< 6.7.0' ) ){
			$this->markTestSkipped( "wp_version_compare() not exists on WP $wp_ver" );
		}

		$this->assertMatchesRegularExpression( '/^\d+\.\d+(?:\.\d+)?/', wp_get_wp_version() );
	}

	public function test__wp_get_wp_version__mockable_handler() {
		if( $wp_ver = wp_version_compare( '< 6.7.0' ) ){
			$this->markTestSkipped( "wp_version_compare() not exists on WP $wp_ver" );
		}

		\WP_Mock::userFunction( 'wp_get_wp_version', [ 'return' => 'mocked-version' ] );
		$this->assertSame( 'mocked-version', wp_get_wp_version() );
	}
}
