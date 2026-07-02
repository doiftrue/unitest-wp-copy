<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class pluggable_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__wp_salt() {
		$salt = wp_salt( 'auth' );
		$this->assertIsString( $salt );
		$this->assertNotEmpty( $salt );
		$this->assertStringContainsString( AUTH_KEY, $salt );
		$this->assertStringContainsString( AUTH_SALT, $salt );

		$auth   = wp_salt( 'auth' );
		$secure = wp_salt( 'secure_auth' );
		$nonce  = wp_salt( 'nonce' );

		$this->assertNotSame( $auth, $secure );
		$this->assertNotSame( $auth, $nonce );
		$this->assertNotSame( $secure, $nonce );

		$scheme = 'custom';
		$this->assertSame(
			SECRET_KEY . hash_hmac( 'md5', $scheme, SECRET_KEY ),
			wp_salt( $scheme )
		);

		\WP_Mock::userFunction( 'wp_salt', [ 'return' => 'mocked-salt-value' ] );
		$this->assertSame( 'mocked-salt-value', wp_salt( 'auth' ) );
	}

	public function test__wp_nonce_tick() {
		$tick = wp_nonce_tick();
		$expected = ceil( time() / ( DAY_IN_SECONDS / 2 ) );
		$this->assertSame( $expected, $tick );

		\WP_Mock::userFunction( 'wp_nonce_tick', [ 'return' => 42 ] );
		$this->assertSame( 42, wp_nonce_tick() );
	}
}
