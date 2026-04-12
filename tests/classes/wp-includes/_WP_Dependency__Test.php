<?php

class _WP_Dependency__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$dep = new _WP_Dependency( 'asset-handle', '/assets/app.js', [ 'jquery' ], '1.0.0', null );

		$this->assertSame( 'asset-handle', $dep->handle );
		$this->assertTrue( $dep->add_data( 'strategy', 'defer' ) );
		$this->assertSame( 'defer', $dep->extra['strategy'] );
		$this->assertTrue( $dep->set_translations( 'assets-domain', '/tmp/lang' ) );
		$this->assertSame( 'assets-domain', $dep->textdomain );
	}

}
