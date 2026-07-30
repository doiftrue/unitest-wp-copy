<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class http__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_http_origin(): void {
		$origin = $_SERVER['HTTP_ORIGIN'] ?? null;

		unset( $_SERVER['HTTP_ORIGIN'] );
		$this->assertSame( '', get_http_origin() );

		$_SERVER['HTTP_ORIGIN'] = 'https://example.com';
		$this->assertSame( 'https://example.com', get_http_origin() );

		if ( null === $origin ) {
			unset( $_SERVER['HTTP_ORIGIN'] );
		} else {
			$_SERVER['HTTP_ORIGIN'] = $origin;
		}
	}

	public function test__get_http_origin__mockable_handler(): void {
		\WP_Mock::userFunction( 'get_http_origin', [ 'return' => 'https://mock.test' ] );
		$this->assertSame( 'https://mock.test', get_http_origin() );
	}
}
