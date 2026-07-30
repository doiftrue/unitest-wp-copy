<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class block_bindings__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		unregister_block_bindings_source( 'unitest/mockable-all' );
		unregister_block_bindings_source( 'unitest/mockable-get' );
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_all_registered_block_bindings_sources(): void {
		$source = register_block_bindings_source( 'unitest/mockable-all', [
			'label'              => 'Mockable source',
			'get_value_callback' => static fn() => 'value',
		] );

		$this->assertSame( $source, get_all_registered_block_bindings_sources()['unitest/mockable-all'] );
	}

	public function test__get_all_registered_block_bindings_sources__mockable_handler(): void {
		$source = register_block_bindings_source( 'unitest/mockable-all', [
			'label'              => 'Mockable source',
			'get_value_callback' => static fn() => 'value',
		] );
		\WP_Mock::userFunction( 'get_all_registered_block_bindings_sources', [
			'return' => [ 'mocked/source' => $source ],
		] );

		$this->assertSame( [ 'mocked/source' => $source ], get_all_registered_block_bindings_sources() );
	}

	public function test__get_block_bindings_source(): void {
		$source = register_block_bindings_source( 'unitest/mockable-get', [
			'label'              => 'Mockable source',
			'get_value_callback' => static fn() => 'value',
		] );

		$this->assertSame( $source, get_block_bindings_source( 'unitest/mockable-get' ) );
		$this->assertNull( get_block_bindings_source( 'unitest/missing' ) );
	}

	public function test__get_block_bindings_source__mockable_handler(): void {
		$source = register_block_bindings_source( 'unitest/mockable-get', [
			'label'              => 'Mockable source',
			'get_value_callback' => static fn() => 'value',
		] );
		\WP_Mock::userFunction( 'get_block_bindings_source', [ 'return' => $source ] );

		$this->assertSame( $source, get_block_bindings_source( 'mocked/source' ) );
	}
}
