<?php

class block_editor__Test extends \PHPUnit\Framework\TestCase {

	public function test__get_default_block_categories() {
		$categories = get_default_block_categories();
		$this->assertSame( 'text', $categories[0]['slug'] );
		$this->assertSame( 'reusable', $categories[6]['slug'] );
	}

	public function test__get_allowed_block_types() {
		$context = (object) [ 'post' => null ];
		$this->assertTrue( get_allowed_block_types( $context ) );

		add_filter( 'allowed_block_types_all', static fn() => [ 'core/paragraph' ] );
		$this->assertSame( [ 'core/paragraph' ], get_allowed_block_types( $context ) );
		remove_all_filters( 'allowed_block_types_all' );
	}

	public function test__wp_get_first_block() {
		if( $wp_ver = wp_version_compare( '< 6.3.0' ) ){
			$this->markTestSkipped( "wp_get_first_block() not exists on WP $wp_ver" );
		}

		$target = [ 'blockName' => 'core/image', 'innerBlocks' => [] ];
		$blocks = [ [ 'blockName' => 'core/group', 'innerBlocks' => [ $target ] ] ];
		$this->assertSame( $target, wp_get_first_block( $blocks, 'core/image' ) );
		$this->assertSame( [], wp_get_first_block( $blocks, 'core/video' ) );
	}
}
