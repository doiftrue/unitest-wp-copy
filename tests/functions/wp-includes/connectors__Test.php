<?php

class connectors__Test extends \PHPUnit\Framework\TestCase {

	public function test___wp_connectors_mask_api_key() {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "_wp_connectors_mask_api_key() not exists on WP $wp_ver" );
		}

		$this->assertSame( 'key', _wp_connectors_mask_api_key( 'key' ) );
		$this->assertSame( str_repeat( "\u{2022}", 6 ) . '7890', _wp_connectors_mask_api_key( '1234567890' ) );
		$this->assertSame( str_repeat( "\u{2022}", 16 ) . '7890', _wp_connectors_mask_api_key( str_repeat( 'x', 20 ) . '7890' ) );
	}
}
