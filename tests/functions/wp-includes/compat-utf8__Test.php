<?php

class compat_utf8__Test extends \PHPUnit\Framework\TestCase {

	public function test___wp_scan_utf8() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "_wp_scan_utf8() not exists on WP $wp_ver" );
		}

		$at = 0;
		$invalid_length = 0;
		$this->assertSame( 4, _wp_scan_utf8( 'test', $at, $invalid_length ) );
		$this->assertSame( 4, $at );
		$this->assertSame( 0, $invalid_length );
	}

	public function test___wp_is_valid_utf8_fallback() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "_wp_is_valid_utf8_fallback() not exists on WP $wp_ver" );
		}

		$this->assertTrue( _wp_is_valid_utf8_fallback( 'text' ) );
		$this->assertFalse( _wp_is_valid_utf8_fallback( "bad\xC0" ) );
	}

	public function test___wp_scrub_utf8_fallback() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "_wp_scrub_utf8_fallback() not exists on WP $wp_ver" );
		}

		$this->assertSame( 'text', _wp_scrub_utf8_fallback( 'text' ) );
		$this->assertSame( "A\u{FFFD}B", _wp_scrub_utf8_fallback( "A\xC0B" ) );
	}

	public function test___wp_utf8_codepoint_count() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "_wp_utf8_codepoint_count() not exists on WP $wp_ver" );
		}

		$this->assertSame( 4, _wp_utf8_codepoint_count( 'test' ) );
		$this->assertSame( 3, _wp_utf8_codepoint_count( "A\xC0B" ) );
	}

	public function test___wp_utf8_codepoint_span() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "_wp_utf8_codepoint_span() not exists on WP $wp_ver" );
		}

		$found = 0;
		$this->assertSame( 3, _wp_utf8_codepoint_span( 'hello', 1, 3, $found ) );
		$this->assertSame( 3, $found );
	}

	public function test___wp_has_noncharacters_fallback() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "_wp_has_noncharacters_fallback() not exists on WP $wp_ver" );
		}

		$this->assertFalse( _wp_has_noncharacters_fallback( 'ok' ) );
		$this->assertTrue( _wp_has_noncharacters_fallback( "\u{FDD0}" ) );
	}

	public function test___wp_utf8_encode_fallback() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "_wp_utf8_encode_fallback() not exists on WP $wp_ver" );
		}

		$this->assertSame( "\u{00F1}", _wp_utf8_encode_fallback( "\xF1" ) );
	}

	public function test___wp_utf8_decode_fallback() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "_wp_utf8_decode_fallback() not exists on WP $wp_ver" );
		}

		$this->assertSame( "\xF1", _wp_utf8_decode_fallback( "\u{00F1}" ) );
	}

}

