<?php

use PHPUnit\Framework\TestCase;

class WP_HTTP_Proxy__Test extends TestCase {

	public function test__public_methods() {
		$proxy = new WP_HTTP_Proxy();

		$this->assertFalse( $proxy->is_enabled() );
		$this->assertFalse( $proxy->use_authentication() );
		$this->assertSame( '', $proxy->host() );
		$this->assertSame( '', $proxy->port() );
		$this->assertSame( '', $proxy->username() );
		$this->assertSame( '', $proxy->password() );
		$this->assertSame( ':', $proxy->authentication() );
		$this->assertStringStartsWith( 'Proxy-Authorization: Basic ', $proxy->authentication_header() );
		$this->assertFalse( $proxy->send_through_proxy( 'https://localhost/path' ) );
		$this->assertTrue( $proxy->send_through_proxy( 'https://example.org/path' ) );
	}
}

