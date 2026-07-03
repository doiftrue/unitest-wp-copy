<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class ms_functions__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_current_site() {
		// Default behavior: returns $current_site global
		global $current_site;
		$current_site = (object) [ 'id' => 1, 'domain' => 'example.com', 'path' => '/' ];

		$result = get_current_site();
		$this->assertSame( $current_site, $result );

		$current_site = null;
		$this->assertNull( get_current_site() );
	}

	public function test__get_current_site__mockable_handler() {
		$fake_site = (object) [ 'id' => 5, 'domain' => 'mock.test', 'path' => '/blog/' ];

		\WP_Mock::userFunction( 'get_current_site', [ 'return' => $fake_site ] );

		$result = get_current_site();
		$this->assertSame( $fake_site, $result );
		$this->assertSame( 5, $result->id );
	}
}
