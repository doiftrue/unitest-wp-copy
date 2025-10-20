<?php

use PHPUnit\Framework\TestCase;

class functions__Test extends TestCase {

	public function test__wp_timezone_string() {
		$this->assertSame( 'UTC', wp_timezone_string() );
	}

	public function test__wp_timezone() {
		$this->assertInstanceOf( DateTimeZone::class, wp_timezone() );
	}

	public function test__number_format_i18n() {
		unset( $GLOBALS['wp_locale'] );
		$this->assertSame( '1,234', number_format_i18n( 1234, 0 ) );
		$GLOBALS['wp_locale'] = (object) [ 'number_format' => [ 'decimal_point' => ',', 'thousands_sep' => ' ' ] ];
		$this->assertSame( '1 234,50', number_format_i18n( 1234.5, 2 ) );
	}

	public function test__size_format() {
		$this->assertSame( '0 B', size_format( 0 ) );
		$this->assertSame( '1 KB', size_format( 1024 ) );
	}

	public function test__maybe_serialize__and__maybe_unserialize() {
		$a = [ 'x' => 1 ];
		$s = maybe_serialize( $a );
		$this->assertIsString( $s );
		$this->assertSame( $a, maybe_unserialize( $s ) );
	}

	public function test__is_serialized__and__is_serialized_string() {
		$this->assertTrue( is_serialized( 'a:1:{s:1:"x";i:1;}' ) );
		$this->assertFalse( is_serialized( 'nope' ) );
		$this->assertTrue( is_serialized_string( 's:3:"abc";' ) );
		$this->assertFalse( is_serialized_string( 'i:3;' ) );
	}

	public function test__build_query__and____http_build_query() {
		$q = build_query( [ 'a' => 1, 'b' => [ 'c' => 2 ] ] );
		$this->assertStringContainsString( 'a=1', $q );
		$this->assertStringContainsString( 'b%5Bc%5D=2', $q );

		$q2 = _http_build_query( [ 'x' => false, 'y' => null, 'z' => 'ok' ] );
		$this->assertSame( 'x=0&z=ok', $q2 );
	}

	public function test__add_query_arg__and__remove_query_arg() {
		$_SERVER['REQUEST_URI'] = '/p?foo=1';
		$this->assertSame( '/p?foo=1&bar=2', add_query_arg( 'bar', 2 ) );
		$this->assertSame( '/p?foo=1', remove_query_arg( 'bar', '/p?foo=1&bar=2' ) );
	}

	public function test__wp_get_nocache_headers() {
		$h = wp_get_nocache_headers();
		$this->assertSame( 'no-cache, must-revalidate, max-age=0, no-store, private', $h['Cache-Control'] );
		$this->assertArrayHasKey( 'Last-Modified', $h );
		$this->assertFalse( $h['Last-Modified'] );
	}

	public function test__bool_from_yn() {
		$this->assertTrue( bool_from_yn( 'Y' ) );
		$this->assertFalse( bool_from_yn( 'n' ) );
	}

	public function test__path_is_absolute_join_normalize() {
		$this->assertTrue( path_is_absolute( '/var/www' ) );
		$this->assertFalse( path_is_absolute( 'rel/path' ) );
		$this->assertSame( '/base/rel', path_join( '/base/', 'rel' ) );
		$this->assertSame( 'phar://a/b', wp_normalize_path( 'phar://a\\b' ) );
	}

	public function test__wp_ext2type() {
		$this->assertSame( 'image', wp_ext2type( 'jpg' ) );
	}

	public function test__wp_get_default_extension_for_mime_type() {
		$this->assertSame( 'jpg', wp_get_default_extension_for_mime_type( 'image/jpeg' ) );
	}

	public function test__wp_check_filetype() {
		$r = wp_check_filetype( 'photo.jpeg' );
		$this->assertSame( 'jpeg', $r['ext'] );
		$this->assertSame( 'image/jpeg', $r['type'] );
	}

	public function test__wp_get_mime_types() {
		$this->assertSame( 'image/jpeg', wp_get_mime_types()['jpg|jpeg|jpe'] );
	}

	public function test__wp_get_ext_types() {
		$this->assertContains( 'jpg', wp_get_ext_types()['image'] );
	}

	public function test__get_allowed_mime_types() {
		$allowed = get_allowed_mime_types();
		$this->assertArrayNotHasKey( 'htm|html', $allowed );
		$this->assertArrayNotHasKey( 'js', $allowed );
	}

	public function test__wp_json_encode__and__helpers() {
		$this->assertSame( '{"a":1}', wp_json_encode( [ 'a' => 1 ] ) );
		$this->assertSame( 'ok', _wp_json_sanity_check( 'ok', 1 ) );
		$this->assertSame( [ 'a' => 'x' ], _wp_json_sanity_check( [ 'a' => 'x' ], 2 ) );
		$this->assertSame( 'x', _wp_json_convert_string( 'x' ) );
	}

	public function test___wp_json_prepare_data() {
		$this->assertSame( 'v', _wp_json_prepare_data( 'v' ) );
	}

	public function test__wp_check_jsonp_callback() {
		$this->assertTrue( wp_check_jsonp_callback( 'a1.b2_c3' ) );
		$this->assertFalse( wp_check_jsonp_callback( 'bad-cb!' ) );
	}

	public function test__wp_json_file_decode() {
		$tmp = tempnam( sys_get_temp_dir(), 'j' );
		file_put_contents( $tmp, '{"a":1}' );
		$decoded = wp_json_file_decode( $tmp, [ 'associative' => true ] );
		$this->assertSame( [ 'a' => 1 ], $decoded );
		unlink( $tmp );
	}

	public function test__smilies_init() {
		global $wp_smiliessearch;
		smilies_init();
		$this->assertNotEmpty( $wp_smiliessearch );
	}

	public function test__wp_parse_args_list_id_slug_slice() {
		$this->assertSame( [ 'a' => 1, 'b' => 2 ], wp_parse_args( [ 'a' => 1, 'b' => 2 ], [ 'a' => 0 ] ) );
		$this->assertSame( [ '1', '2' ], wp_parse_list( '1, 2' ) );
		$this->assertSame( [ 1, 2 ], wp_parse_id_list( [ '1', '2', '2' ] ) );
		$this->assertSame( [ 'AA', 'bb' ], wp_parse_slug_list( [ 'AA', 'bb' ] ) );
		$in  = [ 'a' => 1, 'b' => 2, 'c' => 3 ];
		$sl  = wp_array_slice_assoc( $in, [ 'a', 'c', 'x' ] );
		$this->assertSame( [ 'a' => 1, 'c' => 3 ], $sl );
	}

	public function test__wp_get_image_mime() {
		$tmp = tempnam( sys_get_temp_dir(), 'img' );
		file_put_contents( $tmp, 'not-an-image' );
		$this->assertFalse( wp_get_image_mime( $tmp ) );
		unlink( $tmp );
	}

	public function test__wp_recursive_ksort() {
		$in = [ 'b' => [ 'd' => 1, 'c' => 1 ], 'a' => [ 'b' => 2, 'a' => 2 ] ];
		wp_recursive_ksort( $in );
		$this->assertSame( [ 'a', 'b' ], array_keys( $in ) );
		$this->assertSame( [ 'a', 'b' ], array_keys( $in['a'] ) );
	}

	public function test___wp_array_get() {
		$a = [ 'x' => [ 'y' => null, 'z' => 3 ] ];
		$this->assertSame( 3, _wp_array_get( $a, [ 'x', 'z' ], 'def' ) );
		$this->assertNull( _wp_array_get( $a, [ 'x', 'y' ], 'def' ) );
		$this->assertSame( 'def', _wp_array_get( $a, [ 'x', 'no' ], 'def' ) );
	}

	public function test___wp_array_set() {
		$a = [];
		_wp_array_set( $a, [ 'k', 'm' ], 7 );
		$this->assertSame( 7, $a['k']['m'] );
	}

	public function test___wp_to_kebab_case() {
		$this->assertSame( 'foo-bar-baz', _wp_to_kebab_case( 'FooBar_baz' ) );
		$this->assertSame( 'version-2nd-part-3', _wp_to_kebab_case( 'version 2nd part 3' ) );
	}

	public function test__wp_is_numeric_array() {
		$this->assertTrue( wp_is_numeric_array( [ 10 => 'a', 11 => 'b' ] ) );
		$this->assertFalse( wp_is_numeric_array( [ 'a' => 1, 2 => 2 ] ) );
	}

	public function test__wp_filter_object_list() {
		$data = [
			[ 'id' => 2, 'type' => 'a' ],
			[ 'id' => 1, 'type' => 'b' ],
			[ 'id' => 3, 'type' => 'a' ],
		];
		$filtered = wp_filter_object_list( $data, [ 'type' => 'a' ] );
		$this->assertCount( 2, $filtered );
	}

	public function test__wp_list_pluck() {
		$data = [
			[ 'id' => 2, 'type' => 'a' ],
			[ 'id' => 1, 'type' => 'b' ],
			[ 'id' => 3, 'type' => 'a' ],
		];
		$this->assertSame( [ 2, 1, 3 ], wp_list_pluck( $data, 'id' ) );
	}

	public function test__wp_list_sort() {
		$data = [
			[ 'id' => 2, 'type' => 'a' ],
			[ 'id' => 1, 'type' => 'b' ],
			[ 'id' => 3, 'type' => 'a' ],
		];
		$sorted = wp_list_sort( $data, [ 'id' => 'id' ], 'DESC' );
		$this->assertSame( [ 1, 2, 3 ], array_column( $sorted, 'id' ) );
	}

	public function test__wp_list_filter() {
		$data = [
			[ 'id' => 2, 'type' => 'a' ],
			[ 'id' => 1, 'type' => 'b' ],
			[ 'id' => 3, 'type' => 'a' ],
		];
		$this->assertCount( 1, wp_list_filter( $data, [ 'id' => 1 ] ) );
	}

	public function test___deprecated_function_argument_doing_it_wrong() {
		set_error_handler( function () { /* absorb */ } );
		_deprecated_function( 'foo', '1.0.0', 'bar' );
		_deprecated_argument( 'foo', '1.0.0', 'x' );
		_doing_it_wrong( 'foo', 'Bad usage', '1.0.0' );
		wp_trigger_error( 'foo', 'message' );
		restore_error_handler();
		$this->assertTrue( true );
	}

	public function test__validate_file() {
		$this->assertSame( 1, validate_file( '../etc/passwd' ) );
		$this->assertSame( 0, validate_file( 'ok/file.php' ) );
		$this->assertSame( 3, validate_file( 'nope.php', [ 'allowed.php' ] ) );
	}

	public function test__force_ssl_admin() {
		$prev = force_ssl_admin( true );
		$this->assertIsBool( $prev );
		$this->assertTrue( force_ssl_admin() );
	}

	public function test___cleanup_header_comment() {
		$this->assertSame( 'Header', _cleanup_header_comment( "Header */ more" ) );
	}

	public function test__get_file_data() {
		if ( ! defined( 'YB_IN_BYTES' ) ) {
			define( 'YB_IN_BYTES', 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024 );
		}
		$tmp = tempnam( sys_get_temp_dir(), 'f' );
		file_put_contents( $tmp, "<?php\n/*\nPlugin Name: My Plugin\n*/" );
		$out = get_file_data( $tmp, [ 'Name' => 'Plugin Name' ] );
		unlink( $tmp );
		$this->assertSame( 'My Plugin', $out['Name'] );
	}

	public function test___return_true() {
		$this->assertTrue( __return_true() );
	}

	public function test___return_false() {
		$this->assertFalse( __return_false() );
	}

	public function test___return_zero() {
		$this->assertSame( 0, __return_zero() );
	}

	public function test___return_empty_array() {
		$this->assertSame( [], __return_empty_array() );
	}

	public function test___return_null() {
		$this->assertNull( __return_null() );
	}

	public function test___return_empty_string() {
		$this->assertSame( '', __return_empty_string() );
	}

	public function test__wp_find_hierarchy_loop() {
		$map = [ 1 => 2, 2 => 3, 3 => 1 ];
		$cb  = function ( $id ) use ( $map ) { return $map[ $id ] ?? null; };
		$loop = wp_find_hierarchy_loop( $cb, 1, null );
		$this->assertNotEmpty( $loop );
		$this->assertArrayHasKey( 1, $loop );
	}

	public function test__wp_debug_backtrace_summary() {
		$sum = wp_debug_backtrace_summary();
		$this->assertIsString( $sum );
		$this->assertNotSame( '', $sum );
	}

	public function test__wp_is_stream() {
		$this->assertTrue( wp_is_stream( 'php://memory' ) );
		$this->assertFalse( wp_is_stream( '/path/file' ) );
	}

	public function test__wp_checkdate() {
		$this->assertTrue( wp_checkdate( 1, 15, 2024, '2024-01-15' ) );
		$this->assertFalse( wp_checkdate( 2, 31, 2024, 'bad' ) );
	}

	public function test__is_utf8_charset() {
		$this->assertTrue( is_utf8_charset( 'utf-8' ) );
	}

	public function test___canonical_charset() {
		$this->assertSame( 'UTF-8', _canonical_charset( 'utf8' ) );
		$this->assertSame( 'ISO-8859-1', _canonical_charset( 'iso8859-1' ) );
	}

	public function test__mbstring_binary_safe_encoding_reset() {
		// Should not fatal, regardless of mbstring presence.
		mbstring_binary_safe_encoding();
		reset_mbstring_encoding();
		$this->assertTrue( true );
	}

	public function test__wp_validate_boolean() {
		$this->assertTrue( wp_validate_boolean( 'true' ) );
		$this->assertFalse( wp_validate_boolean( 'false' ) );
		$this->assertTrue( wp_validate_boolean( 1 ) );
	}

	public function test__wp_generate_uuid4() {
		$this->assertIsString( wp_generate_uuid4() );
	}

	public function test__wp_is_uuid() {
		$this->assertTrue( wp_is_uuid( '550e8400-e29b-41d4-a716-446655440000', 4 ) );
		$this->assertFalse( wp_is_uuid( 'not-a-uuid' ) );
	}

	public function test__wp_unique_id() {
		$this->assertSame( 'p1', wp_unique_id( 'p' ) );
		$this->assertSame( 'p2', wp_unique_id( 'p' ) );
	}

	public function test__wp_unique_prefixed_id() {
		$this->assertSame( 'x1', wp_unique_prefixed_id( 'x' ) );
		$this->assertSame( 'x2', wp_unique_prefixed_id( 'x' ) );
	}

	public function test__wp_privacy_anonymize_ip() {
		$this->assertSame( '0.0.0.0', wp_privacy_anonymize_ip( '' ) );
		$this->assertSame( '192.168.1.0', wp_privacy_anonymize_ip( '192.168.1.23' ) );
		$this->assertSame( 'fe80::', wp_privacy_anonymize_ip( 'fe80::1' ) );
		$this->assertSame( '::ffff:192.168.1.0', wp_privacy_anonymize_ip( '::ffff:192.168.1.5' ) );
	}

	public function test__wp_privacy_anonymize_data() {
		$this->assertSame( 'deleted@site.invalid', wp_privacy_anonymize_data( 'email', 'a@b.c' ) );
		$this->assertSame( '0000-00-00 00:00:00', wp_privacy_anonymize_data( 'date', '2020-01-01 00:00:00' ) );
	}

	public function test__wp_fuzzy_number_match() {
		$this->assertTrue( wp_fuzzy_number_match( 10, 10.5, 1 ) );
		$this->assertFalse( wp_fuzzy_number_match( 10, 12.2, 1 ) );
	}

	public function test__wp_is_heic_image_mime_type() {
		$this->assertTrue( wp_is_heic_image_mime_type( 'image/heic' ) );
		$this->assertFalse( wp_is_heic_image_mime_type( 'image/png' ) );
	}

	public function test__wp_fast_hash__and__verify_fast_hash() {
		if ( ! function_exists( 'sodium_crypto_generichash' ) ) {
			$this->markTestSkipped( 'libsodium not available' );
		}
		$hash = wp_fast_hash( 'secret' );
		$this->assertStringStartsWith( '$generic$', $hash );
		$this->assertTrue( wp_verify_fast_hash( 'secret', $hash ) );
		$this->assertFalse( wp_verify_fast_hash( 'nope', $hash ) );
	}

	public function test__wp_unique_id_from_values() {
		$id = wp_unique_id_from_values( [ 'a' => 1, 'b' => 2 ], 'p_' );
		$this->assertStringStartsWith( 'p_', $id );
		$this->assertSame( 10, strlen( $id ) ); // p_ + 8 hex
	}

}
