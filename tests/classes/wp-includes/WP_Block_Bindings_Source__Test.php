<?php

class WP_Block_Bindings_Source__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$source = new WP_Block_Bindings_Source( 'unitest/source', [
			'label'              => 'Unitest source',
			'get_value_callback' => static fn( $args, $block, $attribute ) => "$attribute:{$args['value']}:{$block->marker}",
			'uses_context'       => [ 'postId' ],
		] );

		$this->assertSame( 'unitest/source', $source->name );
		$this->assertSame( 'Unitest source', $source->label );
		$this->assertSame( [ 'postId' ], $source->uses_context );
		$this->assertSame(
			'content:test:block',
			$source->get_value( [ 'value' => 'test' ], (object) [ 'marker' => 'block' ], 'content' )
		);
	}
}
