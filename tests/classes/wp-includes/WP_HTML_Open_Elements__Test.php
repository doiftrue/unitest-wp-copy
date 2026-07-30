<?php

class WP_HTML_Open_Elements__Test extends \PHPUnit\Framework\TestCase {

	public function test__stack_operations() {
		$elements = new WP_HTML_Open_Elements();
		$token = new WP_HTML_Token( null, 'DIV', false );

		$elements->push( $token );

		$this->assertTrue( $elements->contains_node( $token ) );
		$this->assertSame( $token, $elements->current_node() );
		$this->assertSame( 1, $elements->count() );
		$this->assertTrue( $elements->pop() );
		$this->assertSame( 0, $elements->count() );

		if( wp_version_compare( '>= 6.7.0' ) ){
			$this->assertTrue( $elements->contains( 'DIV' ) );
		}
	}
}
