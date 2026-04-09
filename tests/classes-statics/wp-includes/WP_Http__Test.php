<?php

class WP_Http__Test extends \PHPUnit\Framework\TestCase {

	public function test__WP_Http__make_absolute_url() {
		$this->assertSame(
			'https://example.com/sub/assets/a.css',
			WP_Http__make_absolute_url( '../assets/a.css', 'https://example.com/sub/path/page.html' )
		);
	}

	public function test__WP_Http__is_ip_address() {
		$this->assertSame( 4, WP_Http__is_ip_address( '127.0.0.1' ) );
		$this->assertSame( 6, WP_Http__is_ip_address( '2001:db8::1' ) );
		$this->assertFalse( WP_Http__is_ip_address( 'example.com' ) );
	}

}
