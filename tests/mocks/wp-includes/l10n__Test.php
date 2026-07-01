<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class l10n_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test____() {
		$this->assertSame( 'abc', __( 'abc' ) );
	}

	public function test____wp_mock_handler() {
		\WP_Mock::userFunction( '__', [ 'return' => 'translated' ] );
		$this->assertSame( 'translated', __( 'abc' ) );
	}

	public function test___e() {
		ob_start();
		_e( 'abc' );
		$this->assertSame( 'abc', ob_get_clean() );
	}

	public function test___x() {
		$this->assertSame( 'abc', _x( 'abc', 'ctx' ) );
	}

	public function test___n() {
		$this->assertSame( 'one', _n( 'one', 'many', 1 ) );
		$this->assertSame( 'many', _n( 'one', 'many', 2 ) );
	}

	public function test___nx() {
		$this->assertSame( 'one', _nx( 'one', 'many', 1, 'ctx' ) );
		$this->assertSame( 'many', _nx( 'one', 'many', 2, 'ctx' ) );
	}

	public function test__esc_html__() {
		$this->assertSame( 'abc', esc_html__( 'abc' ) );
	}

	public function test__esc_html_e() {
		ob_start();
		esc_html_e( 'abc' );
		$this->assertSame( 'abc', ob_get_clean() );
	}

	public function test__esc_html_x() {
		$this->assertSame( 'abc', esc_html_x( 'abc', 'ctx' ) );
	}

	public function test__esc_attr__() {
		$this->assertSame( 'abc', esc_attr__( 'abc' ) );
	}

	public function test__esc_attr_e() {
		ob_start();
		esc_attr_e( 'abc' );
		$this->assertSame( 'abc', ob_get_clean() );
	}

	public function test__esc_attr_x() {
		$this->assertSame( 'abc', esc_attr_x( 'abc', 'ctx' ) );
	}

	public function test__is_rtl(): void {
		$this->assertIsBool( is_rtl() );
	}

	public function test__is_rtl_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'is_rtl', [ 'return' => true ] );
		$this->assertTrue( is_rtl() );
	}
}
