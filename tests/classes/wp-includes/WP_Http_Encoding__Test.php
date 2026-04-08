<?php

class WP_Http_Encoding__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$raw = 'Hello WP';
		$compressed = WP_Http_Encoding::compress( $raw );
		$decoded = WP_Http_Encoding::decompress( $compressed );

		$this->assertNotFalse( $compressed );
		$this->assertSame( $raw, $decoded );
		$this->assertFalse( WP_Http_Encoding::compatible_gzinflate( 'not-gzip' ) );
		$this->assertSame( 'deflate', WP_Http_Encoding::content_encoding() );
		$this->assertTrue( WP_Http_Encoding::should_decode( [ 'content-encoding' => 'gzip' ] ) );
		$this->assertTrue( WP_Http_Encoding::should_decode( "Content-Encoding: gzip\r\n" ) );
		$this->assertFalse( WP_Http_Encoding::should_decode( [] ) );
		$this->assertIsString( WP_Http_Encoding::accept_encoding( 'https://example.com', [ 'decompress' => true, 'stream' => false ] ) );
		$this->assertTrue( WP_Http_Encoding::is_available() );
	}
}

