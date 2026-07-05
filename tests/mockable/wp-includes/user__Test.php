<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class user__mockable__Test extends \PHPUnit\Framework\TestCase {
	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__wp_get_session_token__mockable_handler() {
		\WP_Mock::userFunction( 'wp_get_session_token', [ 'return' => 'mocked-token' ] );
		$this->assertSame( 'mocked-token', wp_get_session_token() );
	}

	public function test__wp_is_application_passwords_supported__mockable_handler() {
		\WP_Mock::userFunction( 'wp_is_application_passwords_supported', [ 'return' => false ] );
		$this->assertFalse( wp_is_application_passwords_supported() );
	}
}
