<?php

class WP_Speculation_Rules__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		if( $wp_ver = wp_version_compare( '< 6.8.0' ) ){
			$this->markTestSkipped( "WP_Speculation_Rules not exists on WP $wp_ver" );
		}

		$rules = new WP_Speculation_Rules();

		$this->assertTrue( $rules->add_rule( 'prefetch', 'next-page', [ 'urls' => [ '/next' ] ] ) );
		$this->assertTrue( $rules->has_rule( 'prefetch', 'next-page' ) );
		$this->assertSame( [ 'prefetch' => [ [ 'urls' => [ '/next' ] ] ] ], $rules->jsonSerialize() );
	}
}
