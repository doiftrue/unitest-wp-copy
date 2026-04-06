<?php

use PHPUnit\Framework\TestCase;

class WP_Block_Type__Test extends TestCase {

	public function test__basic_runtime() {
		$type = new WP_Block_Type( 'core/test', [
			'render_callback' => static fn( $attrs, $content ) => $content . '|ok',
			'attributes'      => [],
			'variation_callback' => static fn() => [ [ 'name' => 'v1' ] ],
		] );

		$this->assertTrue( $type->is_dynamic() );
		$this->assertSame( 'core/test', $type->name );
		$this->assertIsArray( $type->get_attributes() );
		$this->assertIsArray( $type->get_variations() );
		$this->assertIsArray( $type->get_uses_context() );
		$this->assertTrue( isset( $type->variations ) );
	}

	public function test__not_independent_prepare_attributes_for_render() {
		$type = new WP_Block_Type( 'core/test', [
			'attributes' => [
				'k' => [ 'type' => 'string' ],
			],
		] );

		$this->expectException( Error::class );
		$type->prepare_attributes_for_render( [ 'k' => 'v' ] );
	}
}

