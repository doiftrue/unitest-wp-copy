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
			'https://wp.test/path?a=1',
			wp_http_validate_url( 'https://wp.test/path?a=1' )
		);

		$this->assertFalse( wp_http_validate_url( 'ftp://wp.test/path' ) );
		$this->assertFalse( wp_http_validate_url( 'https://user:pass@wp.test/path' ) );
		$this->assertFalse( wp_http_validate_url( 'http://192.168.0.1/path' ) );

		$allow_local_host = static function () {
			return true;
		};
		add_filter( 'http_request_host_is_external', $allow_local_host, 10, 3 );
		$this->assertSame( 'http://192.168.0.1/path', wp_http_validate_url( 'http://192.168.0.1/path' ) );
		remove_filter( 'http_request_host_is_external', $allow_local_host, 10 );
	}


	private function sample_response(): array {
		return [
			'headers'  => [
				'content-type' => 'application/json',
				'x-test'       => [ 'one', 'two' ],
			],
			'body'     => '{"ok":true}',
			'response' => [
				'code'    => 201,
				'message' => 'Created',
			],
			'cookies'  => [
				new WP_Http_Cookie( [ 'name' => 'session', 'value' => 'abc' ] ),
			],
		];
	}

	public function test__wp_remote_retrieve_headers() {
		$this->assertSame( $this->sample_response()['headers'], wp_remote_retrieve_headers( $this->sample_response() ) );
		$this->assertSame( [], wp_remote_retrieve_headers( new WP_Error( 'e' ) ) );
	}

	public function test__wp_remote_retrieve_header() {
		$response = $this->sample_response();
		$this->assertSame( 'application/json', wp_remote_retrieve_header( $response, 'content-type' ) );
		$this->assertSame( [ 'one', 'two' ], wp_remote_retrieve_header( $response, 'x-test' ) );
		$this->assertSame( '', wp_remote_retrieve_header( $response, 'missing' ) );
	}

	public function test__wp_remote_retrieve_response_code() {
		$this->assertSame( 201, wp_remote_retrieve_response_code( $this->sample_response() ) );
		$this->assertSame( '', wp_remote_retrieve_response_code( new WP_Error( 'e' ) ) );
	}

	public function test__wp_remote_retrieve_response_message() {
		$this->assertSame( 'Created', wp_remote_retrieve_response_message( $this->sample_response() ) );
		$this->assertSame( '', wp_remote_retrieve_response_message( [] ) );
	}

	public function test__wp_remote_retrieve_body() {
		$this->assertSame( '{"ok":true}', wp_remote_retrieve_body( $this->sample_response() ) );
		$this->assertSame( '', wp_remote_retrieve_body( [] ) );
	}

	public function test__wp_remote_retrieve_cookies() {
		$cookies = wp_remote_retrieve_cookies( $this->sample_response() );
		$this->assertCount( 1, $cookies );
		$this->assertInstanceOf( WP_Http_Cookie::class, $cookies[0] );
		$this->assertSame( [], wp_remote_retrieve_cookies( new WP_Error( 'e' ) ) );
	}

	public function test__wp_remote_retrieve_cookie() {
		$cookie = wp_remote_retrieve_cookie( $this->sample_response(), 'session' );
		$this->assertInstanceOf( WP_Http_Cookie::class, $cookie );
		$this->assertSame( 'session', $cookie->name );
		$this->assertSame( '', wp_remote_retrieve_cookie( $this->sample_response(), 'missing' ) );
	}

	public function test__wp_remote_retrieve_cookie_value() {
		$this->assertSame( 'abc', wp_remote_retrieve_cookie_value( $this->sample_response(), 'session' ) );
		$this->assertSame( '', wp_remote_retrieve_cookie_value( $this->sample_response(), 'missing' ) );
	}

}
