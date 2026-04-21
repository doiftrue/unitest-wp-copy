<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class theme_mocks__Test extends \PHPUnit\Framework\TestCase {

	private object $initial_stub_wp_options;

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();

		$this->initial_stub_wp_options = clone $GLOBALS['stub_wp_options'];
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	protected function tearDown(): void {
		$GLOBALS['stub_wp_options'] = clone $this->initial_stub_wp_options;

		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_stylesheet_directory_and_uri() {
		$GLOBALS['stub_wp_options']->stylesheet = 'child/theme';

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
		$GLOBALS['stub_wp_options']->template = 'parent/theme';

		$this->assertSame(
			wp_normalize_path( WP_CONTENT_DIR . '/themes/parent/theme' ),
			get_template_directory()
		);
		$this->assertStringContainsString(
			'/wp-content/themes/parent/theme',
			get_template_directory_uri()
		);
	}

	public function test__get_stylesheet_directory_uri_wp_mock_handler() {
		\WP_Mock::userFunction( 'get_stylesheet_directory_uri', [ 'return' => 'https://mocked.test/child' ] );

		$this->assertSame( 'https://mocked.test/child', get_stylesheet_directory_uri() );
	}

	public function test__get_template_directory_wp_mock_handler() {
		\WP_Mock::userFunction( 'get_template_directory', [ 'return' => '/tmp/mocked-theme' ] );

		$this->assertSame( '/tmp/mocked-theme', get_template_directory() );
	}
}
