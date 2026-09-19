<?php

class WP_HTML_Decoder__Test extends \PHPUnit\Framework\TestCase {

	public function test__independent_part() {
		$this->assertSame( 'A', WP_HTML_Decoder::code_point_to_utf8_bytes( 65 ) );
	}

	public function test__named_entity_parsing_path() {
		if ( $wp_ver = wp_version_compare( '< 6.6.0' ) ) {
			$this->markTestSkipped( "Named HTML entity support not available on WP $wp_ver" );
		}

		$this->assertTrue( WP_HTML_Decoder::attribute_starts_with( 'http&colon;//example.com', 'http:', 'ascii-case-insensitive' ) );
		$this->assertSame( '©', WP_HTML_Decoder::decode_attribute( '&copy;' ) );
	}
}
