<?php

use PHPUnit\Framework\TestCase;
use Unitest_WP_Copy\Bootstrap;

class Bootstrap__Test extends TestCase {

	public function test__init() {
		// NOTE: Bootstrap::init() already run on test init
		$this->assertTrue( Bootstrap::init() instanceof Bootstrap );
	}

	public function test__detect_wp_line() {
		$wp_line = Closure::bind( fn() => $this->detect_wp_line(), new Bootstrap(), Bootstrap::class )();

		$this->assertMatchesRegularExpression( '/^\d+\.\d+$/', $wp_line );
	}

}
