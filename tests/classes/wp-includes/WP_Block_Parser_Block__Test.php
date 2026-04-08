<?php

class WP_Block_Parser_Block__Test extends \PHPUnit\Framework\TestCase {

	public function test__construct() {
		$block = new WP_Block_Parser_Block( 'core/paragraph', [ 'k' => 'v' ], [], '<p>x</p>', [ '<p>x</p>' ] );

		$this->assertSame( 'core/paragraph', $block->blockName );
		$this->assertSame( [ 'k' => 'v' ], $block->attrs );
		$this->assertSame( '<p>x</p>', $block->innerHTML );
	}
}

