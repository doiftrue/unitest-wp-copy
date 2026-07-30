<?php

class WP_HTML_Token__Test extends \PHPUnit\Framework\TestCase {

	public function test__construct() {
		$token = new WP_HTML_Token( 'bookmark', 'DIV', false );

		$this->assertSame( 'bookmark', $token->bookmark_name );
		$this->assertSame( 'DIV', $token->node_name );
		$this->assertSame( 'html', $token->namespace );
		$this->assertFalse( $token->has_self_closing_flag );
	}
}
