<?php

class WP_Block_Type_Registry__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$registry = WP_Block_Type_Registry::get_instance();
		$block = $registry->register( 'unitest/registry' );

		$this->assertInstanceOf( WP_Block_Type::class, $block );
		$this->assertTrue( $registry->is_registered( 'unitest/registry' ) );
		$this->assertSame( $block, $registry->get_registered( 'unitest/registry' ) );
		$this->assertArrayHasKey( 'unitest/registry', $registry->get_all_registered() );
		$this->assertSame( $block, $registry->unregister( 'unitest/registry' ) );
	}
}
