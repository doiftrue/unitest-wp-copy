<?php

class http__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_parse_url() {
		$parts = wp_parse_url( 'https://example.com/a?b=1#c' );
		$this->assertSame( 'example.com', $parts['host'] );
		$this->assertSame( '/a', $parts['path'] );
	}

	public function test___get_component_from_parsed_url_array() {
		$parts = [ 'host' => 'example.com', 'path' => '/x' ];
		$this->assertSame( 'example.com', _get_component_from_parsed_url_array( $parts, PHP_URL_HOST ) );
		$this->assertNull( _get_component_from_parsed_url_array( $parts, PHP_URL_QUERY ) );
	}

	public function test___wp_translate_php_url_constant_to_key() {
		$this->assertSame( 'query', _wp_translate_php_url_constant_to_key( PHP_URL_QUERY ) );
		$this->assertFalse( _wp_translate_php_url_constant_to_key( 999 ) );
	}

	public function test__wp_http_validate_url() {
		$this->assertSame(
			'https://unitest-wp-copy.loc/path?a=1',
			wp_http_validate_url( 'https://unitest-wp-copy.loc/path?a=1' )
		);

		$this->assertFalse( wp_http_validate_url( 'ftp://unitest-wp-copy.loc/path' ) );
		$this->assertFalse( wp_http_validate_url( 'https://user:pass@unitest-wp-copy.loc/path' ) );
		$this->assertFalse( wp_http_validate_url( 'http://192.168.0.1/path' ) );

		$allow_local_host = static function () {
			return true;
		};
		add_filter( 'http_request_host_is_external', $allow_local_host, 10, 3 );
		$this->assertSame( 'http://192.168.0.1/path', wp_http_validate_url( 'http://192.168.0.1/path' ) );
		remove_filter( 'http_request_host_is_external', $allow_local_host, 10 );
	}

}
