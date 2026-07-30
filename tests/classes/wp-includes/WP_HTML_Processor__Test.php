<?php

class WP_HTML_Processor__Test extends \PHPUnit\Framework\TestCase {

	public function test__fragment_parsing() {
		$processor = WP_HTML_Processor::create_fragment( '<div><span>Text</span></div>' );

		$this->assertInstanceOf( WP_HTML_Processor::class, $processor );
		$this->assertTrue( $processor->next_tag( 'SPAN' ) );
		$this->assertSame( 'SPAN', $processor->get_tag() );
		$this->assertSame( [ 'HTML', 'BODY', 'DIV', 'SPAN' ], $processor->get_breadcrumbs() );
		$this->assertTrue( WP_HTML_Processor::is_void( 'IMG' ) );
	}
}
