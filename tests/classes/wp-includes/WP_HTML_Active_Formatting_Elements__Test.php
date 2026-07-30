<?php

class WP_HTML_Active_Formatting_Elements__Test extends \PHPUnit\Framework\TestCase {

	public function test__stack_operations() {
		$elements = new WP_HTML_Active_Formatting_Elements();
		$token = new WP_HTML_Token( null, 'B', false );

		$elements->push( $token );

		$this->assertTrue( $elements->contains_node( $token ) );
		$this->assertSame( 1, $elements->count() );
		$this->assertSame( $token, $elements->current_node() );
		$this->assertTrue( $elements->remove_node( $token ) );
		$this->assertSame( 0, $elements->count() );
	}
}
