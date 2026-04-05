<?php

use PHPUnit\Framework\TestCase;

class link_template__Test extends TestCase {

	public function test__home_url() {
		$this->assertSame( 'https://unitest-wp-copy.loc/a', home_url( 'a' ) );
	}

	public function test__get_home_url() {
		$this->assertSame( 'http://unitest-wp-copy.loc/a', get_home_url( null, 'a', 'http' ) );
	}

	public function test__site_url() {
		$this->assertSame( 'http://unitest-wp-copy.loc/a', site_url( 'a' ) );
	}

	public function test__get_site_url() {
		$this->assertSame( 'http://unitest-wp-copy.loc/a', get_site_url( null, 'a', 'http' ) );
	}

	public function test__includes_url() {
		$this->assertStringContainsString( '/wp-includes/x.js', includes_url( 'x.js' ) );
	}

	public function test__content_url() {
		$this->assertStringContainsString( '/wp-content/a.css', content_url( 'a.css' ) );
	}

	public function test__plugins_url() {
		$url = plugins_url( 'asset.js', '/path/to/wp/wp-content/plugins/my-plugin/main.php' );
		$this->assertStringContainsString( '/plugins/my-plugin/asset.js', $url );
	}

	public function test__set_url_scheme() {
		$this->assertSame( '/a', set_url_scheme( 'https://example.com/a', 'relative' ) );
		$this->assertSame( 'http://example.com/a', set_url_scheme( 'https://example.com/a', 'http' ) );
	}

	public function test__wp_internal_hosts() {
		$this->assertContains( 'unitest-wp-copy.loc', wp_internal_hosts() );
	}

	public function test__wp_is_internal_link() {
		$this->assertTrue( wp_is_internal_link( 'https://unitest-wp-copy.loc/a' ) );
		$this->assertFalse( wp_is_internal_link( 'https://example.com/a' ) );
	}

}
