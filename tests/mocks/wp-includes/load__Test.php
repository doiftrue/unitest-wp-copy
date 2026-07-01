<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class load_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__is_multisite() {
		$this->assertFalse( is_multisite() );
	}

	public function test__is_multisite_wp_mock_handler() {
		\WP_Mock::userFunction( 'is_multisite', [ 'return' => true ] );
		$this->assertTrue( is_multisite() );
	}

	public function test__is_admin() {
		$GLOBALS['current_screen'] = new class {
			public function in_admin() {
				return true;
			}
		};

		$this->assertTrue( is_admin() );
		unset( $GLOBALS['current_screen'] );
		$this->assertFalse( is_admin() );
	}

	public function test__is_admin_wp_mock_handler() {
		\WP_Mock::userFunction( 'is_admin' )->andReturn( true );
		$this->assertTrue( is_admin() );
	}

	public function test__get_current_blog_id(): void {
		$GLOBALS['blog_id'] = 12;
		$this->assertSame( 12, get_current_blog_id() );
	}

	public function test__get_current_blog_id_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'get_current_blog_id', [ 'return' => 77 ] );
		$this->assertSame( 77, get_current_blog_id() );
	}

	public function test__get_current_network_id(): void {
		$this->assertSame( 1, get_current_network_id() );
	}

	public function test__get_current_network_id_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'get_current_network_id', [ 'return' => 88 ] );
		$this->assertSame( 88, get_current_network_id() );
	}

	public function test__timer_float(): void {
		$_SERVER['REQUEST_TIME_FLOAT'] = microtime( true ) - 0.5;
		$this->assertGreaterThan( 0, timer_float() );
	}

	public function test__timer_float_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'timer_float', [ 'return' => 1.25 ] );
		$this->assertSame( 1.25, timer_float() );
	}

	public function test__timer_stop(): void {
		timer_start();
		$this->assertMatchesRegularExpression( '/^\d+(?:[.,]\d+)?$/', timer_stop( 0, 3 ) );
	}

	public function test__timer_stop_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'timer_stop', [ 'return' => '2.500' ] );
		$this->assertSame( '2.500', timer_stop() );
	}
}
