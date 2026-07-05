<?php

class meta__Test extends \PHPUnit\Framework\TestCase {
	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['wp_meta_keys'] = [];
	}

	public function test__get_metadata_default() {
		$this->assertSame( '', get_metadata_default( 'post', 1, 'key', true ) );
		$this->assertSame( [], get_metadata_default( 'post', 1, 'key', false ) );
	}

	public function test__get_registered_meta_keys() {
		$this->assertSame( [], get_registered_meta_keys( 'post' ) );
	}

	public function test__register_meta() {
		$this->assertTrue( register_meta( 'post', 'key', [ 'type' => 'string' ] ) );
		$this->assertArrayHasKey( 'key', get_registered_meta_keys( 'post' ) );
	}

	public function test__registered_meta_key_exists() {
		register_meta( 'post', 'key', [] );
		$this->assertTrue( registered_meta_key_exists( 'post', 'key' ) );
	}

	public function test__unregister_meta_key() {
		register_meta( 'post', 'key', [] );
		$this->assertTrue( unregister_meta_key( 'post', 'key' ) );
		$this->assertFalse( registered_meta_key_exists( 'post', 'key' ) );
	}

	public function test__is_protected_meta() {
		$this->assertTrue( is_protected_meta( '_private' ) );
		$this->assertFalse( is_protected_meta( 'public' ) );
	}

	public function test__sanitize_meta() {
		$this->assertSame( 'value', sanitize_meta( 'key', 'value', 'post' ) );
	}

	public function test___wp_register_meta_args_allowed_list() {
		$this->assertSame( [ 'type' => 'string' ], _wp_register_meta_args_allowed_list(
			[ 'type' => 'string', 'unknown' => true ],
			[ 'type' => 'string' ]
		) );
	}
}
