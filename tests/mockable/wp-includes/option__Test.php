<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class option__mockable__Test extends \PHPUnit\Framework\TestCase {
	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_registered_settings__mockable_handler() {
		\WP_Mock::userFunction( 'get_registered_settings', [ 'return' => [ 'mocked' => [] ] ] );
		$this->assertSame( [ 'mocked' => [] ], get_registered_settings() );
	}
}
