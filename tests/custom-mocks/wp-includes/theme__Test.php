<?php

use Unitest_WP_Copy\WP_Options;

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class theme__custom_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		WP_Mock::setUp();

		WP_Options::save_state();
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	protected function tearDown(): void {
		WP_Options::restore_state();

		WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_stylesheet_directory_and_uri() {
		WP_Options::set( 'stylesheet', 'child/theme' );

		$this->assertSame(
			wp_normalize_path( WP_CONTENT_DIR . '/themes/child/theme' ),
			get_stylesheet_directory()
		);
		$this->assertStringContainsString(
			'/wp-content/themes/child/theme',
			get_stylesheet_directory_uri()
		);
	}

	public function test__get_template_directory_and_uri() {
		WP_Options::set( 'template', 'parent/theme' );

		$this->assertSame(
			wp_normalize_path( WP_CONTENT_DIR . '/themes/parent/theme' ),
			get_template_directory()
		);
		$this->assertStringContainsString(
			'/wp-content/themes/parent/theme',
			get_template_directory_uri()
		);
	}

	public function test__get_stylesheet_directory_uri__mockable_handler() {
		WP_Mock::userFunction( 'get_stylesheet_directory_uri', [ 'return' => 'https://mocked.test/child' ] );

		$this->assertSame( 'https://mocked.test/child', get_stylesheet_directory_uri() );
	}

	public function test__get_template_directory__mockable_handler() {
		WP_Mock::userFunction( 'get_template_directory', [ 'return' => '/tmp/mocked-theme' ] );

		$this->assertSame( '/tmp/mocked-theme', get_template_directory() );
	}

}
