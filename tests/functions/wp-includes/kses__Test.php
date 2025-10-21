<?php

use PHPUnit\Framework\TestCase;

class kses__Test extends TestCase {

	public function test__wp_kses(): void {
		$html = '<a href="http://e.com" onclick="x">ok</a><script>x</script>';
		$allowed = [ 'a' => [ 'href' => true ] ];
		$this->assertSame( '<a href="http://e.com">ok</a>x', wp_kses( $html, $allowed ) );
	}

	public function test__wp_kses_one_attr(): void {
		$this->assertIsString( wp_kses_one_attr( 'href="http://e.com"', 'a' ) );
	}

	public function test__wp_kses_allowed_html(): void {
		$this->assertIsArray( wp_kses_allowed_html( 'post' ) );
	}

	public function test__wp_kses_hook(): void {
		$this->assertIsString( wp_kses_hook( '<b>x</b>', [], [] ) );
	}

	public function test__wp_kses_version(): void {
		$this->assertMatchesRegularExpression( '/^\d+\.\d+/', wp_kses_version() );
	}

	public function test__wp_kses_split(): void {
		$this->assertIsString( wp_kses_split( '<b>x</b>', [ 'b' => [] ], [] ) );
	}

	public function test__wp_kses_uri_attributes(): void {
		$this->assertContains( 'href', wp_kses_uri_attributes() );
	}

	public function test___wp_kses_split_callback(): void {
		$this->assertIsString( _wp_kses_split_callback( '<b>', [ 'b' => [] ], [] ) );
	}

	public function test__wp_kses_split2(): void {
		$this->assertIsString( wp_kses_split2( '<b>', [ 'b' => [] ], [] ) );
	}

	public function test__wp_kses_attr(): void {
		$this->assertIsString( wp_kses_attr( ' class="x" onclick="y"', 'a', [ 'class' => true ], [] ) );
	}

	public function test__wp_kses_attr_check(): void {
		$name = 'href'; $value = '#'; $whole = ' href="#"';
		$this->assertFalse( wp_kses_attr_check( $name, $value, $whole, '', 'unknown', [] ) );
		$this->assertSame( '', $name );
		$this->assertSame( '', $value );
		$this->assertSame( '', $whole );

		$name = 'data-foo'; $value = '1'; $whole = ' data-foo="1"';
		$allowed = [ 'a' => [ 'data-*' => true ] ];
		$this->assertTrue( wp_kses_attr_check( $name, $value, $whole, '', 'a', $allowed ) );
		$this->assertSame( 'data-foo', $name );
		$this->assertSame( '1', $value );
	}

	public function test__wp_kses_hair(): void {
		$this->assertIsArray( wp_kses_hair( 'href="x" rel=noopener', [ 'href' => true, 'rel' => true ], [] ) );
	}

	public function test__wp_kses_attr_parse(): void {
		$this->assertIsArray( wp_kses_attr_parse( '<a href="x" target=_blank>' ) );
	}

	public function test__wp_kses_hair_parse(): void {
		$this->assertIsArray( wp_kses_hair_parse( 'href="x" target=_blank' ) );
	}

	public function test__wp_kses_check_attr_val(): void {
		$this->assertTrue( wp_kses_check_attr_val( 'abc', '', 'maxlen', 3 ) );
		$this->assertFalse( wp_kses_check_attr_val( 'abcd', '', 'maxlen', 3 ) );

		$this->assertTrue( wp_kses_check_attr_val( 'ab', '', 'minlen', 2 ) );
		$this->assertFalse( wp_kses_check_attr_val( 'a', '', 'minlen', 2 ) );

		$this->assertTrue( wp_kses_check_attr_val( '10', '', 'maxval', 10 ) );
		$this->assertFalse( wp_kses_check_attr_val( '11', '', 'maxval', 10 ) );
		$this->assertFalse( wp_kses_check_attr_val( '10x', '', 'maxval', 10 ) );

		$this->assertTrue( wp_kses_check_attr_val( '5', '', 'minval', 5 ) );
		$this->assertFalse( wp_kses_check_attr_val( '4', '', 'minval', 5 ) );
		$this->assertFalse( wp_kses_check_attr_val( '  3x', '', 'minval', 3 ) );

		$this->assertTrue( wp_kses_check_attr_val( 'any', 'y', 'valueless', 'Y' ) );
		$this->assertFalse( wp_kses_check_attr_val( 'any', 'y', 'valueless', 'N' ) );

		$this->assertTrue( wp_kses_check_attr_val( 'on', '', 'values', array( 'on', 'off' ) ) );
		$this->assertFalse( wp_kses_check_attr_val( 'maybe', '', 'values', array( 'on', 'off' ) ) );

		$cb = static function( $v ) { return $v === 'ok'; };
		$this->assertTrue( wp_kses_check_attr_val( 'ok', '', 'value_callback', $cb ) );
		$this->assertFalse( wp_kses_check_attr_val( 'no', '', 'value_callback', $cb ) );
	}

	public function test__wp_kses_bad_protocol(): void {
		$this->assertSame( 'alert(1)', wp_kses_bad_protocol( 'javascript:alert(1)', [] ) );
	}

	public function test__wp_kses_no_null(): void {
		$this->assertSame( 'a', wp_kses_no_null( "a\0", [ 'slash_zero' => 'remove' ] ) );
	}

	public function test__wp_kses_stripslashes(): void {
		$this->assertSame( "O\'R", wp_kses_stripslashes( "O\\'R" ) );
	}

	public function test__wp_kses_array_lc(): void {
		$this->assertArrayHasKey( 'a', wp_kses_array_lc( [ 'A' => [ 'HREF' => true ] ] ) );
	}

	public function test__wp_kses_html_error(): void {
		$this->assertIsString( wp_kses_html_error( '< a' ) );
	}

	public function test__wp_kses_bad_protocol_once(): void {
		$allowed_protocols = [ 'http', 'https' ];
		$this->assertSame( 'http://e.com', wp_kses_bad_protocol_once( 'http://e.com', $allowed_protocols ) );
	}

	public function test__wp_kses_bad_protocol_once2(): void {
		$allowed_protocols = [ 'http', 'https', 'ftp' ];
		$this->assertSame( 'http:', wp_kses_bad_protocol_once2( 'http', $allowed_protocols ) );
	}

	public function test__wp_kses_normalize_entities(): void {
		$this->assertIsString( wp_kses_normalize_entities( '&copy;' ) );
	}

	public function test__wp_kses_named_entities(): void {
		$this->assertSame( '&amp;c;', wp_kses_named_entities( '&copy;' ) );
	}

	public function test__wp_kses_xml_named_entities(): void {
		$this->assertSame( '&amp;&#169;;', wp_kses_xml_named_entities( [ 1=>'&#169;'] ) );
	}

	public function test__wp_kses_normalize_entities2(): void {
		$this->assertIsString( wp_kses_normalize_entities2( '&#x3C;' ) );
	}

	public function test__wp_kses_normalize_entities3(): void {
		$this->assertIsString( wp_kses_normalize_entities3( '&#60;' ) );
	}

	public function test__valid_unicode(): void {
		$this->assertTrue( valid_unicode( 0x20AC ) );
	}

	public function test__wp_kses_decode_entities(): void {
		$this->assertSame( '<', wp_kses_decode_entities( '&#60;' ) );
	}

	public function test___wp_kses_decode_entities_chr(): void {
		$this->assertSame( '<', _wp_kses_decode_entities_chr( [ 1=>60 ] ) );
	}

	public function test___wp_kses_decode_entities_chr_hexdec(): void {
		$this->assertSame( '<', _wp_kses_decode_entities_chr_hexdec( [ 1=>'3C' ] ) );
	}

	public function test__wp_filter_post_kses(): void {
		$this->assertIsString( wp_filter_post_kses( '<a href="x" onclick="y">t</a>' ) );
	}

	public function test__wp_filter_global_styles_post(): void {
		$this->assertIsString( wp_filter_global_styles_post( 'body{color:red}' ) );
	}

	public function test__wp_kses_post(): void {
		$this->assertIsString( wp_kses_post( '<em>x</em><iframe></iframe>' ) );
	}

	public function test__wp_kses_post_deep(): void {
		$this->assertIsArray( wp_kses_post_deep( [ '<em>x</em>', '<script>x</script>' ] ) );
	}

	public function test__wp_filter_nohtml_kses(): void {
		$this->assertSame( 'ok', wp_filter_nohtml_kses( '<b>ok</b>' ) );
	}

	public function test__safecss_filter_attr(): void {
		$this->assertIsString( safecss_filter_attr( 'color:red; position:absolute' ) );
	}

	public function test___wp_add_global_attributes(): void {
		$r = _wp_add_global_attributes( true );
		$this->assertIsArray( $r );
		$this->assertTrue( $r['class'] );

		$out = _wp_add_global_attributes( [ 'foo' => 1, 'class' => false ] );
		$this->assertSame( 1, $out['foo'] );
		$this->assertTrue( $out['class'] );

		$this->assertSame( 'x', _wp_add_global_attributes( 'x' ) );
	}

	public function test__wp_filter_kses() {
		$this->assertSame( 'AB', wp_filter_kses( 'A<iframe src="x"></iframe>B' ) );
	}

	public function test__wp_kses_data() {
		$this->assertSame( 'XY', wp_kses_data( 'X<iframe src="y"></iframe>Y' ) );
	}

}
