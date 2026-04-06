<?php

use PHPUnit\Framework\TestCase;

class WP_Block_Parser_Frame__Test extends TestCase {

	public function test__construct() {
		$block = new WP_Block_Parser_Block( 'core/paragraph', [], [], '', [] );
		$frame = new WP_Block_Parser_Frame( $block, 10, 5 );

		$this->assertSame( $block, $frame->block );
		$this->assertSame( 10, $frame->token_start );
		$this->assertSame( 5, $frame->token_length );
		$this->assertSame( 15, $frame->prev_offset );
	}
}

