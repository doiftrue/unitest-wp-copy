<?php

class WP_HTML_Decoder__Test extends \PHPUnit\Framework\TestCase {

	public function test__independent_part() {
		$this->assertSame( 'A', WP_HTML_Decoder::code_point_to_utf8_bytes( 65 ) );
	}

	public function test__not_independent_entity_parsing_path() {
		$this->expectException( Error::class );
		WP_HTML_Decoder::attribute_starts_with( 'http&colon;//example.com', 'http:', 'ascii-case-insensitive' );
	}
}
