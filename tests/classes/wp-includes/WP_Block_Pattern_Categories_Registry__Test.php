<?php

class WP_Block_Pattern_Categories_Registry__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$registry = WP_Block_Pattern_Categories_Registry::get_instance();

		$this->assertTrue( $registry->register( 'unitest', [ 'label' => 'Unitest' ] ) );
		$this->assertTrue( $registry->is_registered( 'unitest' ) );
		$this->assertSame(
			[ 'name' => 'unitest', 'label' => 'Unitest' ],
			$registry->get_registered( 'unitest' )
		);
		$this->assertSame( [ [ 'name' => 'unitest', 'label' => 'Unitest' ] ], $registry->get_all_registered() );
		$this->assertTrue( $registry->unregister( 'unitest' ) );
		$this->assertFalse( $registry->is_registered( 'unitest' ) );
	}
}
