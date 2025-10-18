<?php

use PHPUnit\Framework\TestCase;

class formatting__Test extends TestCase {

	public function test__zeroise(): void {
		$this->assertEquals( '05', zeroise( 5, 2 ) );
	}

	public function test__trailingslashit(): void {
		$this->assertSame( 'path/', trailingslashit( 'path' ) );
	}

	public function test__untrailingslashit(): void {
		$this->assertSame( 'path', untrailingslashit( 'path/' ) );
	}

	public function test__sanitize_key(): void {
		$this->assertSame( 'foobar', sanitize_key( 'Foo Bar' ) );
	}

	public function test__wp_slash(): void {
		$this->assertSame( "O\\'Reilly", wp_slash( "O'Reilly" ) );
	}

	public function test__wp_parse_str(): void {
		wp_parse_str( 'a=1&b=2', $out );
		$this->assertSame( ['a' => '1', 'b' => '2'], $out );
	}

	public function test__sanitize_text_field(): void {
		$this->assertSame( 'Hello world', sanitize_text_field( "  <b>Hello</b>\nworld " ) );
	}

	public function test__sanitize_text_fields(): void {
		$this->assertSame( 'foo', _sanitize_text_fields( " foo\r\n", false ) );
	}

	public function test__wp_pre_kses_less_than(): void {
		$this->assertSame( 'a &lt; b', wp_pre_kses_less_than( 'a < b' ) );
	}

	public function test__wp_strip_all_tags(): void {
		$this->assertSame( 'Hello', wp_strip_all_tags( '<p>Hello</p>' ) );
	}

	public function test__wpautop(): void {
		$this->assertStringContainsString( '<p>Hi</p>', wpautop( "Hi" ) );
	}

	public function test__wptexturize(): void {
		$this->assertSame( '&#8220;a&#8221;', wptexturize( '"a"' ) );
	}

	public function test__wptexturize_primes(): void {
		$this->assertSame( '5&#8242; 7&#8243;', wptexturize( "5' 7\"" ) );
	}

	public function test__is_email(): void {
		$this->assertSame( 'a@b.com', is_email( 'a@b.com' ) );
		$this->assertFalse( is_email( 'bad@@' ) );
	}

	public function test__wp_check_invalid_utf8(): void {
		$this->assertSame( 'abc', wp_check_invalid_utf8( "abc" ) );
		$this->assertSame( '', wp_check_invalid_utf8( "abc\x80" ) );
	}

	public function test__wptexturize_pushpop_element(): void {
		$this->assertIsArray( (function() { $stack = []; _wptexturize_pushpop_element( '<code>', $stack, ['code'] ); return $stack; })() );
	}

	public function test__wp_html_split(): void {
		$this->assertIsArray( wp_html_split( 'a<b>c</b>d' ) );
	}

	public function test__get_html_split_regex(): void {
		$this->assertIsString( get_html_split_regex() );
	}

	public function test__get_wptexturize_split_regex(): void {
		$this->assertIsString( _get_wptexturize_split_regex() );
	}

	public function test__get_wptexturize_shortcode_regex(): void {
		$this->assertIsString( _get_wptexturize_shortcode_regex( ['foo', 'bar'] ) );
	}

	public function test__wp_replace_in_html_tags(): void {
		$this->assertSame( '<X title="X">a</X>', wp_replace_in_html_tags( '<a title="a">a</a>', ['a' => 'X'] ) );
	}

	public function test__autop_newline_preservation_helper(): void {
		$this->assertStringContainsString( 'a<WPPreserveNewline />b', _autop_newline_preservation_helper( ["a\nb"] ) );
	}

	public function test__shortcode_unautop(): void {
		global $shortcode_tags;
		$was = $shortcode_tags;

		$shortcode_tags['x'] = '__return_empty_string';
		$this->assertSame( "[x]\n", shortcode_unautop( "<p>[x]</p>\n" ) );

		$shortcode_tags = $was;
	}

	public function test__seems_utf8(): void {
		$this->assertTrue( seems_utf8( 'Привет' ) );
	}

	public function test__wp_specialchars(): void {
		$this->assertSame( '&lt;a&gt;', _wp_specialchars( '<a>', ENT_NOQUOTES, false, true ) );
	}

	public function test__wp_specialchars_decode(): void {
		$this->assertSame( '<a>', wp_specialchars_decode( '&lt;a&gt;' ) );
	}

	public function test__utf8_uri_encode(): void {
		$this->assertSame( '', utf8_uri_encode( 'п', 1 ) );
		$this->assertSame( '', utf8_uri_encode( 'п', 1, true ) );
	}

	public function test__remove_accents(): void {
		$this->assertSame( 'Francois', remove_accents( 'François' ) );
	}

	public function test__sanitize_file_name(): void {
		$this->assertSame( 'A-b.JPG', sanitize_file_name( 'A b!!.JPG' ) );
	}

	public function test__sanitize_user(): void {
		$this->assertSame( 'John Doe', sanitize_user( 'John Doe', true ) );
	}

	public function test__sanitize_title(): void {
		$this->assertIsString( sanitize_title( 'Hello World!' ) );
	}

	public function test__sanitize_title_for_query(): void {
		$this->assertIsString( sanitize_title_for_query( 'Hello World!' ) );
	}

	public function test__sanitize_title_with_dashes(): void {
		$this->assertSame( 'hello-world', sanitize_title_with_dashes( 'Hello - World!' ) );
	}

	public function test__sanitize_sql_orderby(): void {
		$this->assertSame( 'post_date DESC', sanitize_sql_orderby( 'post_date DESC' ) );
	}

	public function test__sanitize_html_class(): void {
		$this->assertSame( 'Ab', sanitize_html_class( 'A b' ) );
	}

	public function test__sanitize_locale_name(): void {
		$this->assertSame( 'de-AT', sanitize_locale_name( 'de-AT' ) );
	}

	public function test__convert_chars(): void {
		$this->assertIsString( convert_chars( "Fran\xC3\xA7ois" ) );
	}

	public function test__convert_invalid_entities(): void {
		$this->assertSame( '&#8225;', convert_invalid_entities( '&#135;' ) );
	}

	public function test__balanceTags(): void {
		$this->assertSame( '<b>a</b>', balanceTags( '<b>a' ) );
	}

	public function test__force_balance_tags(): void {
		$this->assertSame( '<i>x</i>', force_balance_tags( '<i>x' ) );
	}

	public function test__format_to_edit(): void {
		$this->assertIsString( format_to_edit( "a\r\nb" ) );
	}

	public function test__backslashit(): void {
		$this->assertSame( '\a\b', backslashit( 'ab' ) );
	}

	public function test__addslashes_gpc(): void {
		$this->assertSame( "O\\'R", addslashes_gpc( "O'R" ) );
	}

	public function test__stripslashes_deep(): void {
		$this->assertSame( ['a' => "O'R"], stripslashes_deep( ['a' => "O\\'R"] ) );
	}

	public function test__stripslashes_from_strings_only(): void {
		$this->assertSame( "O'R", stripslashes_from_strings_only( "O\\'R" ) );
	}

	public function test__urlencode_deep(): void {
		$this->assertSame( ['a+b'], urlencode_deep( ['a b'] ) );
	}

	public function test__rawurlencode_deep(): void {
		$this->assertSame( ['a%2Fb'], rawurlencode_deep( ['a/b'] ) );
	}

	public function test__urldecode_deep(): void {
		$this->assertSame( ['a b'], urldecode_deep( ['a%20b'] ) );
	}

	public function test__antispambot(): void {
		$this->assertIsString( antispambot( 'test@example.com' ) );
	}

//	public function test___make_url_clickable_cb(): void {
//		$this->assertStringContainsString(
//		    '<a href="http://example.com">http://example.com</a>',
//		    _make_url_clickable_cb(['', '', 'http://example.com', '', ''])
//		);
//	}

	public function test__split_str_by_whitespace(): void {
		$this->assertSame( ['a ', " \n", ' ', 'b'], _split_str_by_whitespace( "a  \n b", 1 ) );
	}

	public function wp_rel_callback(): void {
		$this->assertStringContainsString( 'rel="nofollow"', wp_rel_callback( '<a href="x">x</a>' ) );
	}

	public function test__wp_targeted_link_rel(): void {
		$html = '<a href="x" target="_blank">x</a>';
		$this->assertStringContainsString( 'rel="noopener', wp_targeted_link_rel( $html ) );
	}

	public function test__translate_smiley(): void {
		$this->assertIsString( translate_smiley( [':)'] ) );
	}

	public function test__convert_smilies(): void {
		$this->assertIsString( convert_smilies( ':)' ) );
	}

	public function test__wp_iso_descrambler(): void {
		$this->assertIsString( wp_iso_descrambler( '&#x48;&#x69;' ) );
	}

	public function test__wp_iso_convert(): void {
		$this->assertIsString( _wp_iso_convert( [1 => 'a0'] ) );
	}

	public function test__get_gmt_from_date(): void {
		$this->assertSame( '2020-01-01 00:00:00', get_gmt_from_date( '2020-01-01 00:00:00' ) );
	}

	public function test__get_date_from_gmt(): void {
		$this->assertIsString( get_date_from_gmt( '2020-01-01 00:00:00' ) );
	}

	public function test__iso8601_timezone_to_offset(): void {
		$this->assertIsInt( iso8601_timezone_to_offset( '+02:00' ) );
	}

	public function test__iso8601_to_datetime(): void {
		$this->assertIsString( iso8601_to_datetime( '2020-01-01T00:00:00+00:00' ) );
	}

	public function test__sanitize_email(): void {
		$this->assertSame( 'a@b.com', sanitize_email( ' a@b.com ' ) );
	}

	public function test__human_time_diff(): void {
		$this->assertIsString( human_time_diff( time() - HOUR_IN_SECONDS ) );
	}

	public function test__wp_trim_excerpt(): void {
		$this->assertIsString( wp_trim_excerpt( str_repeat( 'a ', 80 ) ) );
	}

	public function test__wp_trim_words(): void {
		$this->assertStringContainsString( '&hellip;', wp_trim_words( str_repeat( 'a ', 50 ), 10 ) );
	}

	public function test__ent2ncr(): void {
		$this->assertSame( '&#169;', ent2ncr( '&copy;' ) );
	}

	public function test__format_for_editor(): void {
		$this->assertIsString( format_for_editor( "a\nb", 'text' ) );
	}

	public function test__deep_replace(): void {
		$this->assertSame( 'ab', _deep_replace( ['/'], 'a/b' ) );
	}

	public function test__esc_url(): void {
		$this->assertIsString( esc_url( 'http://example.com/?a=1&b=2' ) );
	}

	public function test__esc_url_raw(): void {
		$this->assertIsString( esc_url_raw( 'http://example.com/?a=1&b=2' ) );
	}

	public function test__sanitize_url(): void {
		$this->assertIsString( sanitize_url( 'http://example.com' ) );
	}

	public function test__htmlentities2(): void {
		$this->assertSame( '&#169;', htmlentities2( '&#169;' ) );
	}

	public function test__esc_js(): void {
		$this->assertIsString( esc_js( "alert('x')" ) );
	}

	public function test__esc_html(): void {
		$this->assertSame( '&lt;b&gt;', esc_html( '<b>' ) );
	}

	public function test__esc_attr(): void {
		$this->assertIsString( esc_attr( '"x"' ) );
	}

	public function test__esc_textarea(): void {
		$this->assertIsString( esc_textarea( "<b>\n" ) );
	}

	public function test__esc_xml(): void {
		$this->assertSame( '&lt;a/&gt;', esc_xml( '<a/>' ) );
	}

	public function test__tag_escape(): void {
		$this->assertSame( 'div', tag_escape( 'div' ) );
	}

	public function test__wp_make_link_relative(): void {
		$this->assertSame( '/a', wp_make_link_relative( 'https://example.com/a' ) );
	}

	public function test__map_deep(): void {
		$this->assertSame( ['X'], map_deep( ['x'], 'strtoupper' ) );
	}

	public function test__wp_sprintf(): void {
		$this->assertSame( 'Hello World', wp_sprintf( 'Hello %s', 'World' ) );
	}

	public function test__wp_sprintf_l(): void {
		$this->assertIsString( wp_sprintf_l( '%l', ['a','b','c'] ) );
	}

	public function test__wp_html_excerpt(): void {
		$this->assertIsString( wp_html_excerpt( '<b>hello world</b>', 5 ) );
	}

	public function test__links_add_base_url(): void {
		$this->assertStringContainsString( 'http://e.com', links_add_base_url( '<a href="/x">x</a>', 'http://e.com' ) );
	}

	public function test__links_add_base(): void {
		$this->assertStringContainsString( 'http://e.com', _links_add_base( '<a href="/x">x</a>', 'http://e.com', ['a'] ) );
	}

	/**
	 * @covers ::links_add_target()
	 * @covers ::_links_add_target()
	 */
	public function test__links_add_target(): void {
		$this->assertStringContainsString( 'target="_blank"', links_add_target( '<a href="x">x</a>', '_blank' ) );
	}

	public function test__normalize_whitespace(): void {
		$this->assertSame( "a \n b", normalize_whitespace( "a   \n b" ) );
	}

	public function test__sanitize_textarea_field(): void {
		$this->assertSame( "hello\nworld", sanitize_textarea_field( " <b>hello</b>\nworld " ) );
	}

	public function test__wp_basename(): void {
		$this->assertSame( 'a.txt', wp_basename( '/x/y/a.txt' ) );
	}

	public function test__capital_P_dangit(): void {
		$this->assertSame( '>WordPress', capital_P_dangit( '>Wordpress' ) );
	}

	public function test__sanitize_mime_type(): void {
		$this->assertSame( 'image/jpeg', sanitize_mime_type( 'image/jpeg' ) );
	}

	public function test__sanitize_trackback_urls(): void {
		$this->assertIsString( sanitize_trackback_urls( "http://a.com\nhttp://b.com" ) );
	}

	public function test__wp_unslash(): void {
		$this->assertSame( "O'R", wp_unslash( "O\\'R" ) );
	}

	public function test__get_url_in_content(): void {
		$this->assertSame( 'http://e.com', get_url_in_content( 'see <a href="http://e.com">baz</a> now' ) );
	}

	public function test__wp_spaces_regexp(): void {
		$this->assertIsString( wp_spaces_regexp() );
	}

	public function test__wp_encode_emoji(): void {
		$this->assertIsString( wp_encode_emoji( '🙂' ) );
	}

	public function test__wp_staticize_emoji(): void {
		$this->assertIsString( wp_staticize_emoji( '🙂' ) );
	}

	public function test__wp_staticize_emoji_for_email(): void {
		$this->assertIsString( wp_staticize_emoji_for_email( '🙂' ) );
	}

	public function test__wp_emoji_list(): void {
		$this->assertIsArray( _wp_emoji_list( 'people' ) );
	}

	public function test__url_shorten(): void {
		$this->assertSame( 'example.com/foo', url_shorten( 'https://example.com/foo' ) );
	}

	public function test__sanitize_hex_color(): void {
		$this->assertSame( '#ff00aa', sanitize_hex_color( '#ff00aa' ) );
		$this->assertNull( sanitize_hex_color( 'zzz' ) );
	}

	public function test__sanitize_hex_color_no_hash(): void {
		$this->assertSame( 'ff00aa', sanitize_hex_color_no_hash( 'ff00aa' ) );
		$this->assertNull( sanitize_hex_color_no_hash( 'x' ) );
	}

	public function test__maybe_hash_hex_color(): void {
		$this->assertSame( '#ff00aa', maybe_hash_hex_color( 'ff00aa' ) );
	}

}
