<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class cache__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();

		$GLOBALS['wp_object_cache'] = new WP_Object_Cache();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__wp_cache_get_multiple(): void {
		wp_cache_set( 'a', 'alpha' );
		wp_cache_set( 'b', 'beta' );
		$result = wp_cache_get_multiple( [ 'a', 'b', 'missing' ] );

		$this->assertSame( 'alpha', $result['a'] );
		$this->assertSame( 'beta', $result['b'] );
		$this->assertFalse( $result['missing'] );
	}

	public function test__wp_cache_get_multiple__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_cache_get_multiple', [
			'return' => [ 'mocked' => 'value' ],
		] );

		$this->assertSame( [ 'mocked' => 'value' ], wp_cache_get_multiple( [ 'mocked' ], 'group', true ) );
	}
}
