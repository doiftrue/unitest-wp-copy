<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class ms_load__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__is_subdomain_install() {
		// Default: SUBDOMAIN_INSTALL is not defined in test env, VHOST is not defined
		// The function checks constants. In our test env, neither is typically defined
		// so it falls through to VHOST check.
		$result = is_subdomain_install();
		$this->assertIsBool( $result );
	}

	public function test__is_subdomain_install__mockable_handler() {
		\WP_Mock::userFunction( 'is_subdomain_install', [ 'return' => true ] );
		$this->assertTrue( is_subdomain_install() );
	}
}
