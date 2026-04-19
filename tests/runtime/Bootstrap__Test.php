<?php

use PHPUnit\Framework\TestCase;
use Unitest_WP_Copy\Bootstrap;

class Bootstrap__Test extends TestCase {

	public function test__detect_wp_line() {
		$wp_line = Closure::bind(
			static fn() => Bootstrap::detect_wp_line(),
			null,
			Bootstrap::class
		)();

		$this->assertMatchesRegularExpression( '/^\d+\.\d+$/', $wp_line );
	}

}
