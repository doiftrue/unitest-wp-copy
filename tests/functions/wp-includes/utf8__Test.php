<?php

class utf8__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_is_valid_utf8() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_is_valid_utf8() not exists on WP $wp_ver" );
		}

		$this->assertTrue( wp_is_valid_utf8( 'plain ascii' ) );
		$this->assertTrue( wp_is_valid_utf8( 'Привет' ) );
		$this->assertFalse( wp_is_valid_utf8( "bad\xC0" ) );
	}

	public function test__wp_scrub_utf8() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_scrub_utf8() not exists on WP $wp_ver" );
		}

		$this->assertSame( 'test', wp_scrub_utf8( 'test' ) );
		$this->assertSame( "A\u{FFFD}B", wp_scrub_utf8( "A\xC0B" ) );
	}

	public function test__wp_has_noncharacters() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "wp_has_noncharacters() not exists on WP $wp_ver" );
		}

		$this->assertFalse( wp_has_noncharacters( 'hello' ) );
		$this->assertTrue( wp_has_noncharacters( "\u{FDD0}" ) );
	}

}

