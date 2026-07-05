<?php

class WP_Block_Bindings_Registry__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$registry = WP_Block_Bindings_Registry::get_instance();

		$source = $registry->register( 'unitest/registry', [
			'label'              => 'Registry source',
			'get_value_callback' => static fn() => 'value',
		] );

		$this->assertSame( $registry, WP_Block_Bindings_Registry::get_instance() );
		$this->assertInstanceOf( WP_Block_Bindings_Source::class, $source );
		$this->assertTrue( $registry->is_registered( 'unitest/registry' ) );
		$this->assertSame( $source, $registry->get_registered( 'unitest/registry' ) );
		$this->assertArrayHasKey( 'unitest/registry', $registry->get_all_registered() );
		$this->assertSame( $source, $registry->unregister( 'unitest/registry' ) );
		$this->assertFalse( $registry->is_registered( 'unitest/registry' ) );
	}
}
