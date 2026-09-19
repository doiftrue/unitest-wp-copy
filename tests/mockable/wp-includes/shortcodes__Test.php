<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class shortcodes__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__shortcode_exists__mockable_handler(): void {
		\WP_Mock::userFunction( 'shortcode_exists', [ 'return' => true ] );
		$this->assertTrue( shortcode_exists( 'unitest' ) );
	}
}
