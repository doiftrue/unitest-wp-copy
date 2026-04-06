<?php

use PHPUnit\Framework\TestCase;

class WP_HTML_Attribute_Token__Test extends TestCase {

	public function test__construct() {
		$token = new WP_HTML_Attribute_Token( 'class', 10, 3, 4, 9, false );

		$this->assertSame( 'class', $token->name );
		$this->assertSame( 10, $token->value_starts_at );
		$this->assertSame( 3, $token->value_length );
		$this->assertSame( 4, $token->start );
		$this->assertSame( 9, $token->length );
		$this->assertFalse( $token->is_true );
	}
}

