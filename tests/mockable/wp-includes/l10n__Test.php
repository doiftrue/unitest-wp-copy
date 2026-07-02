<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class l10n__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__is_rtl(): void {
		$this->assertIsBool( is_rtl() );
	}

	public function test__is_rtl__mockable_handler(): void {
		\WP_Mock::userFunction( 'is_rtl', [ 'return' => true ] );
		$this->assertTrue( is_rtl() );
	}
}
