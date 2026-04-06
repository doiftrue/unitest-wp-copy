<?php

use PHPUnit\Framework\TestCase;

class WP_Block_Patterns_Registry__Test extends TestCase {

	public function test__register_flow() {
		$registry = WP_Block_Patterns_Registry::get_instance();
		$name = 'test/independent-pattern';

		if ( $registry->is_registered( $name ) ) {
			$registry->unregister( $name );
		}
		$this->assertTrue( $registry->register( $name, [
			'title'   => 'T',
			'content' => '<!-- wp:paragraph --><p>x</p><!-- /wp:paragraph -->',
		] ) );
		$this->assertTrue( $registry->is_registered( $name ) );
		$this->assertTrue( $registry->unregister( $name ) );
	}

	public function test__not_independent_block_hooks_runtime() {
		$registry = WP_Block_Patterns_Registry::get_instance();
		$name = 'test/pattern-with-hooks';

		if ( $registry->is_registered( $name ) ) {
			$registry->unregister( $name );
		}
		$registry->register( $name, [
			'title'   => 'T2',
			'content' => '<p>x</p>',
		] );

		$this->expectException( Error::class );
		$registry->get_registered( $name );
	}
}
