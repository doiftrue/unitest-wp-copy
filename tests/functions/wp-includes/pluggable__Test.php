<?php

class pluggable__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_rand() {
		$val = wp_rand( 1, 100 );
		$this->assertIsInt( $val );
		$this->assertGreaterThanOrEqual( 1, $val );
		$this->assertLessThanOrEqual( 100, $val );
		$this->assertSame( 5, wp_rand( 5, 5 ) );

		$val = wp_rand( 100, 1 );
		$this->assertGreaterThanOrEqual( 1, $val );
		$this->assertLessThanOrEqual( 100, $val );
	}

	public function test__wp_generate_password() {
		$this->assertSame( 12, strlen( wp_generate_password() ) );
		$this->assertSame( 24, strlen( wp_generate_password( 24 ) ) );
		$this->assertMatchesRegularExpression( '/^[a-zA-Z0-9]+$/', wp_generate_password( 50, false ) );
		$this->assertSame( 64, strlen( wp_generate_password( 64, true, true ) ) );
	}

	public function test__wp_hash() {
		$hash = wp_hash( 'test data' );
		$this->assertIsString( $hash );
		$this->assertSame( 32, strlen( $hash ) );
		$this->assertSame( $hash, wp_hash( 'test data' ) );
		$this->assertNotSame( $hash, wp_hash( 'other data' ) );

		if( wp_version_compare( '>= 6.8.0' ) ){
			$this->assertSame( 64, strlen( wp_hash( 'test', 'auth', 'sha256' ) ) );

			try {
				wp_hash( 'test', 'auth', 'nonexistent_algo_xyz' );
				$this->fail( 'Unsupported hash algorithm must throw an exception.' );
			}
			catch ( InvalidArgumentException $e ) {
				$this->assertNotSame( '', $e->getMessage() );
			}
		}
	}

	public function test__wp_hash_password() {
		$hash = wp_hash_password( 'my_password' );
		$this->assertIsString( $hash );

		if( wp_version_compare( '< 6.8.0' ) ){
			$this->assertStringStartsWith( '$P$', $hash );
		}
		else{
			$this->assertStringStartsWith( '$wp', $hash );
		}

		$this->assertSame( '*', wp_hash_password( str_repeat( 'a', 4097 ) ) );

		global $wp_hasher;
		$wp_hasher = new PasswordHash( 8, true );
		try {
			$this->assertStringStartsWith( '$P$', wp_hash_password( 'test_pass' ) );
		}
		finally {
			$wp_hasher = null;
		}
	}

	public function test__wp_password_needs_rehash() {
		if( $wp_ver = wp_version_compare( '< 6.8.0' ) ){
			$this->markTestSkipped( "wp_password_needs_rehash() not exists on WP $wp_ver" );
		}

		$this->assertFalse( wp_password_needs_rehash( wp_hash_password( 'some_password' ) ) );
		$this->assertTrue( wp_password_needs_rehash( password_hash( 'test', PASSWORD_BCRYPT ) ) );

		$hasher = new PasswordHash( 8, true );
		$this->assertTrue( wp_password_needs_rehash( $hasher->HashPassword( 'test' ) ) );

		global $wp_hasher;
		$wp_hasher = $hasher;
		try {
			$this->assertFalse( wp_password_needs_rehash( 'anything' ) );
		}
		finally {
			$wp_hasher = null;
		}
	}

	public function test__wp_sanitize_redirect() {
		$this->assertSame( 'https://example.com/path?q=1', wp_sanitize_redirect( 'https://example.com/path?q=1' ) );
		$this->assertStringNotContainsString( "\0", wp_sanitize_redirect( "http://ex.com/\0path" ) );

		$result = wp_sanitize_redirect( 'http://ex.com/path name' );
		$this->assertStringContainsString( '%20', $result );
		$this->assertStringNotContainsString( ' ', $result );

		$result = wp_sanitize_redirect( "http://ex.com/path%0d%0a" );
		$this->assertStringNotContainsString( '%0d', $result );
		$this->assertStringNotContainsString( '%0a', $result );
	}

	public function test___wp_sanitize_utf8_in_redirect() {
		$this->assertStringContainsString( '%', _wp_sanitize_utf8_in_redirect( [ 'привет' ] ) );
	}

	public function test__wp_validate_redirect() {
		$home_host = parse_url( home_url(), PHP_URL_HOST );
		$this->assertSame(
			"https://$home_host/some-page",
			wp_validate_redirect( "https://$home_host/some-page" )
		);
		$this->assertSame( '/fallback', wp_validate_redirect( 'https://evil.com/hack', '/fallback' ) );
		$this->assertStringContainsString( '/local-path', wp_validate_redirect( '/local-path?q=1' ) );
		$this->assertSame( '/safe', wp_validate_redirect( 'data:text/html,<script>alert(1)</script>', '/safe' ) );
		$this->assertSame( '/safe', wp_validate_redirect( 'javascript:alert(1)', '/safe' ) );
	}

	public function test__wp_nonce_tick() {
		$tick = wp_nonce_tick();
		$this->assertIsFloat( $tick );
		$this->assertGreaterThan( 0, $tick );
		$this->assertSame( ceil( time() / ( DAY_IN_SECONDS / 2 ) ), $tick );
	}

	public function test__wp_parse_auth_cookie() {
		$result = wp_parse_auth_cookie( 'admin|1700000000|token123|hmac456', 'auth' );
		$this->assertIsArray( $result );
		$this->assertSame( 'admin', $result['username'] );
		$this->assertSame( '1700000000', $result['expiration'] );
		$this->assertSame( 'token123', $result['token'] );
		$this->assertSame( 'hmac456', $result['hmac'] );
		$this->assertSame( 'auth', $result['scheme'] );
		$this->assertFalse( wp_parse_auth_cookie( 'a|b|c', 'auth' ) );

		$saved_cookie = $_COOKIE;
		try {
			$_COOKIE = [];
			$this->assertFalse( wp_parse_auth_cookie( '', 'auth' ) );

			$_COOKIE[ AUTH_COOKIE ] = 'user|999|tok|mac';
			$result = wp_parse_auth_cookie( '', 'auth' );
			$this->assertIsArray( $result );
			$this->assertSame( 'user', $result['username'] );
			$this->assertSame( 'auth', $result['scheme'] );
		}
		finally {
			$_COOKIE = $saved_cookie;
		}
	}
}
