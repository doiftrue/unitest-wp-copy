<?php

class WP_Token_Map__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		if( $wp_ver = wp_version_compare( '< 6.6.0' ) ){
			$this->markTestSkipped( "WP_Token_Map not exists on WP $wp_ver" );
		}

		$map = WP_Token_Map::from_array( [ ':)' => 'smile', ':(' => 'sad' ] );
		$matched_length = null;

		$this->assertTrue( $map->contains( ':)' ) );
		$this->assertSame( 'smile', $map->read_token( 'x :)', 2, $matched_length ) );
		$this->assertSame( 2, $matched_length );
		$this->assertSame( [ ':(' => 'sad', ':)' => 'smile' ], $map->to_array() );
	}
}
