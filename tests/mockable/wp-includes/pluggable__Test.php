<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class pluggable__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__wp_nonce_tick() {
		$tick = wp_nonce_tick();
		$expected = ceil( time() / ( DAY_IN_SECONDS / 2 ) );
		$this->assertSame( $expected, $tick );

		\WP_Mock::userFunction( 'wp_nonce_tick', [ 'return' => 42 ] );
		$this->assertSame( 42, wp_nonce_tick() );
	}
}
