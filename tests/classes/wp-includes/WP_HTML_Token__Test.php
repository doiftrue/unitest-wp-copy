<?php

class WP_HTML_Token__Test extends \PHPUnit\Framework\TestCase {

	public function test__construct() {
		$token = new WP_HTML_Token( 'bookmark', 'DIV', false );

		$this->assertSame( 'bookmark', $token->bookmark_name );
		$this->assertSame( 'DIV', $token->node_name );
		$this->assertFalse( $token->has_self_closing_flag );

		if( wp_version_compare( '>= 6.7.0' ) ){
			$this->assertSame( 'html', $token->namespace );
		}
	}
}
