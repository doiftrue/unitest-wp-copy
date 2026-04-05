<?php

use PHPUnit\Framework\TestCase;

class http__Test extends TestCase {

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

}
