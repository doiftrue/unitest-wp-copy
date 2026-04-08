<?php

class WP_Http_Cookie__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$cookie = new WP_Http_Cookie( [
			'name'   => 'token',
			'value'  => 'abc',
			'domain' => 'example.com',
			'path'   => '/',
		] );

		$this->assertSame( 'token=abc', $cookie->getHeaderValue() );
		$this->assertSame( 'Cookie: token=abc', $cookie->getFullHeader() );
		$this->assertArrayHasKey( 'domain', $cookie->get_attributes() );
		$this->assertTrue( $cookie->test( 'https://example.com/path' ) );
		$this->assertFalse( $cookie->test( 'https://other.test/path' ) );
	}
}

