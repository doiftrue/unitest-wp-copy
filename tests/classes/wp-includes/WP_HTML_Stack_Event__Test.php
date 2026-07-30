<?php

class WP_HTML_Stack_Event__Test extends \PHPUnit\Framework\TestCase {

	public function test__construct() {
		if( $wp_ver = wp_version_compare( '< 6.6.0' ) ){
			$this->markTestSkipped( "WP_HTML_Stack_Event not exists on WP $wp_ver" );
		}

		$token = new WP_HTML_Token( null, 'DIV', false );
		$event = new WP_HTML_Stack_Event( $token, WP_HTML_Stack_Event::PUSH, 'real' );

		$this->assertSame( $token, $event->token );
		$this->assertSame( 'push', $event->operation );
		$this->assertSame( 'real', $event->provenance );
	}
}
