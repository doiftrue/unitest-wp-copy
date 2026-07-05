<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class meta__mockable__Test extends \PHPUnit\Framework\TestCase {
	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_registered_meta_keys__mockable_handler() {
		\WP_Mock::userFunction( 'get_registered_meta_keys', [ 'return' => [ 'mocked' => [] ] ] );
		$this->assertSame( [ 'mocked' => [] ], get_registered_meta_keys( 'post' ) );
	}

	public function test__is_protected_meta__mockable_handler() {
		\WP_Mock::userFunction( 'is_protected_meta', [ 'return' => true ] );
		$this->assertTrue( is_protected_meta( 'public_key', 'post' ) );
	}
}
