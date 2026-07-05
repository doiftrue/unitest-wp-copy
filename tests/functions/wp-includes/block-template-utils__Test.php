<?php

class block_template_utils__Test extends \PHPUnit\Framework\TestCase {

	public function test__get_default_block_template_types() {
		$types = get_default_block_template_types();
		$this->assertArrayHasKey( 'index', $types );
		$this->assertArrayHasKey( 'single', $types );
		$this->assertArrayHasKey( '404', $types );
	}

	public function test___flatten_blocks() {
		$blocks = [ [
			'blockName' => 'core/group',
			'innerBlocks' => [ [ 'blockName' => 'core/paragraph', 'innerBlocks' => [] ] ],
		] ];
		$flat = _flatten_blocks( $blocks );
		$this->assertSame( [ 'core/group', 'core/paragraph' ], array_column( $flat, 'blockName' ) );
		$flat[1]['attrs']['changed'] = true;
		$this->assertTrue( $blocks[0]['innerBlocks'][0]['attrs']['changed'] );
	}

	public function test___inject_theme_attribute_in_template_part_block() {
		$block = [ 'blockName' => 'core/template-part', 'attrs' => [] ];
		_inject_theme_attribute_in_template_part_block( $block );
		$this->assertSame( get_stylesheet(), $block['attrs']['theme'] );

		$block = [ 'blockName' => 'core/paragraph', 'attrs' => [] ];
		_inject_theme_attribute_in_template_part_block( $block );
		$this->assertArrayNotHasKey( 'theme', $block['attrs'] );
	}

	public function test___remove_theme_attribute_from_template_part_block() {
		$block = [ 'blockName' => 'core/template-part', 'attrs' => [ 'theme' => 'unitest' ] ];
		_remove_theme_attribute_from_template_part_block( $block );
		$this->assertArrayNotHasKey( 'theme', $block['attrs'] );

		$block = [ 'blockName' => 'core/paragraph', 'attrs' => [ 'theme' => 'unitest' ] ];
		_remove_theme_attribute_from_template_part_block( $block );
		$this->assertSame( 'unitest', $block['attrs']['theme'] );
	}

	public function test__get_template_hierarchy() {
		$this->assertSame( [ 'index' ], get_template_hierarchy( 'index' ) );
		$this->assertSame( [ 'front-page', 'home', 'index' ], get_template_hierarchy( 'front-page' ) );
		$this->assertSame( [ 'category-books', 'category', 'archive', 'index' ], get_template_hierarchy( 'category-books' ) );
		$this->assertSame( [ 'page', 'singular', 'index' ], get_template_hierarchy( 'custom', true ) );
	}
}
