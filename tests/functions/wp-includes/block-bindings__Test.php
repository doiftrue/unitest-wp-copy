<?php

class block_bindings__Test extends \PHPUnit\Framework\TestCase {

	public function test__register_block_bindings_source() {
		$source = register_block_bindings_source( 'unitest/function-register', [
			'label'              => 'Function source',
			'get_value_callback' => static fn() => 'value',
		] );

		$this->assertInstanceOf( WP_Block_Bindings_Source::class, $source );
		$this->assertSame( 'unitest/function-register', $source->name );
		unregister_block_bindings_source( 'unitest/function-register' );
	}

	public function test__unregister_block_bindings_source() {
		$source = register_block_bindings_source( 'unitest/function-unregister', [
			'label'              => 'Function source',
			'get_value_callback' => static fn() => 'value',
		] );

		$this->assertSame( $source, unregister_block_bindings_source( 'unitest/function-unregister' ) );
		$this->assertNull( get_block_bindings_source( 'unitest/function-unregister' ) );
	}

	public function test__get_all_registered_block_bindings_sources() {
		$source = register_block_bindings_source( 'unitest/function-all', [
			'label'              => 'Function source',
			'get_value_callback' => static fn() => 'value',
		] );

		$this->assertSame( $source, get_all_registered_block_bindings_sources()['unitest/function-all'] );
		unregister_block_bindings_source( 'unitest/function-all' );
	}

	public function test__get_block_bindings_source() {
		$source = register_block_bindings_source( 'unitest/function-get', [
			'label'              => 'Function source',
			'get_value_callback' => static fn() => 'value',
		] );

		$this->assertSame( $source, get_block_bindings_source( 'unitest/function-get' ) );
		$this->assertNull( get_block_bindings_source( 'unitest/missing' ) );
		unregister_block_bindings_source( 'unitest/function-get' );
	}

	public function test__get_block_bindings_supported_attributes() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "get_block_bindings_supported_attributes() not exists on WP $wp_ver" );
		}

		$this->assertSame( [ 'content' ], get_block_bindings_supported_attributes( 'core/paragraph' ) );
		$this->assertSame( [], get_block_bindings_supported_attributes( 'plugin/unknown' ) );
	}
}
