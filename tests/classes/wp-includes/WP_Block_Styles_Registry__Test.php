<?php

use PHPUnit\Framework\TestCase;

class WP_Block_Styles_Registry__Test extends TestCase {

	public function test__public_methods() {
		$registry = WP_Block_Styles_Registry::get_instance();
		$block = 'core/paragraph';
		$style = 'test-style';

		if ( $registry->is_registered( $block, $style ) ) {
			$registry->unregister( $block, $style );
		}

		$this->assertTrue( $registry->register( $block, [
			'name'  => $style,
			'label' => 'Test',
		] ) );
		$this->assertTrue( $registry->is_registered( $block, $style ) );
		$this->assertNotNull( $registry->get_registered( $block, $style ) );
		$this->assertIsArray( $registry->get_registered_styles_for_block( $block ) );
		$this->assertIsArray( $registry->get_all_registered() );
		$this->assertTrue( $registry->unregister( $block, $style ) );
	}
}

