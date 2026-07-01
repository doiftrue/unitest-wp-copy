<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class script_modules_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
		unset( $GLOBALS['wp_script_modules'] );
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__wp_script_modules(): void {
		$registry = wp_script_modules();
		$this->assertInstanceOf( WP_Script_Modules::class, $registry );
		$this->assertSame( $registry, wp_script_modules() );
	}

	public function test__wp_script_modules_wp_mock_handler(): void {
		$registry = new WP_Script_Modules();
		\WP_Mock::userFunction( 'wp_script_modules', [ 'return' => $registry ] );
		$this->assertSame( $registry, wp_script_modules() );
	}
}
