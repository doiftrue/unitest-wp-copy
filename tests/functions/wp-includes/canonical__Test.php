<?php

class canonical__Test extends \PHPUnit\Framework\TestCase {

	public function test___remove_qs_args_if_not_in_url() {
		$this->assertSame(
			'foo=1&bar=2',
			_remove_qs_args_if_not_in_url( 'foo=1&bar=2&baz=3', [ 'bar', 'baz' ], 'https://wp.test/?bar=9' )
		);
		$this->assertSame(
			'foo=1',
			_remove_qs_args_if_not_in_url( 'foo=1&bar=2&baz=3', [ 'bar', 'baz' ], 'https://wp.test/' )
		);
	}

	public function test__strip_fragment_from_url() {
		$this->assertSame(
			'https://wp.test:8080/path?foo=bar',
			strip_fragment_from_url( 'https://wp.test:8080/path?foo=bar#section' )
		);
		$this->assertSame( '/relative/path#section', strip_fragment_from_url( '/relative/path#section' ) );
	}
}
