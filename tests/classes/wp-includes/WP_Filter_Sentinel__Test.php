<?php

class WP_Filter_Sentinel__Test extends \PHPUnit\Framework\TestCase {

	public function test__identity() {
		if( $wp_ver = wp_version_compare( '< 7.1.0' ) ){
			$this->markTestSkipped( "WP_Filter_Sentinel not exists on WP $wp_ver" );
		}

		$sentinel = new WP_Filter_Sentinel();

		$this->assertInstanceOf( WP_Filter_Sentinel::class, $sentinel );
		$this->assertNotSame( $sentinel, new WP_Filter_Sentinel() );
	}
}
