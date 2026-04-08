<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class load_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__is_multisite() {
		$this->assertFalse( is_multisite() );
	}

	public function test__is_multisite_wp_mock_handler() {
		\WP_Mock::userFunction( 'is_multisite', [ 'return' => true ] );
		$this->assertTrue( is_multisite() );
	}

}
