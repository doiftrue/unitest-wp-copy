<?php

class WP_HTML_Span__Test extends \PHPUnit\Framework\TestCase {

	public function test__construct() {
		$span = new WP_HTML_Span( 3, 7 );

		$this->assertSame( 3, $span->start );
		$this->assertSame( 7, $span->length );
	}
}

