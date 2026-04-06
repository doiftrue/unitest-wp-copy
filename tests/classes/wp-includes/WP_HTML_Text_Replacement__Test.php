<?php

use PHPUnit\Framework\TestCase;

class WP_HTML_Text_Replacement__Test extends TestCase {

	public function test__construct() {
		$replacement = new WP_HTML_Text_Replacement( 5, 2, 'ok' );

		$this->assertSame( 5, $replacement->start );
		$this->assertSame( 2, $replacement->length );
		$this->assertSame( 'ok', $replacement->text );
	}
}

