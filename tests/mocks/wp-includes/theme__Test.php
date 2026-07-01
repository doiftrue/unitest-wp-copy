<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class theme_mocks__Test extends \PHPUnit\Framework\TestCase {

	private object $initial_stub_wp_options;

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();

		$this->initial_stub_wp_options = clone $GLOBALS['stub_wp_options'];
		$GLOBALS['_wp_theme_features'] = [];
		$GLOBALS['_wp_registered_theme_features'] = [];
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

	public function test__current_theme_supports(): void {
		$GLOBALS['_wp_theme_features']['html5'] = [ [ 'script' ] ];
		$this->assertTrue( current_theme_supports( 'html5', 'script' ) );
	}

	public function test__current_theme_supports_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'current_theme_supports', [ 'return' => true ] );
		$this->assertTrue( current_theme_supports( 'mocked-feature' ) );
	}

	public function test__get_theme_support(): void {
		add_theme_support( 'custom-logo', [ 'width' => 100 ] );
		$this->assertSame( 100, get_theme_support( 'custom-logo', 'width' ) );
	}

	public function test__get_theme_support_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'get_theme_support', [ 'return' => [ [ 'mocked' ] ] ] );
		$this->assertSame( [ [ 'mocked' ] ], get_theme_support( 'feature' ) );
	}

	public function test__get_registered_theme_features(): void {
		register_theme_feature( 'feature-a', [ 'type' => 'boolean' ] );
		$this->assertArrayHasKey( 'feature-a', get_registered_theme_features() );
	}

	public function test__get_registered_theme_features_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'get_registered_theme_features', [ 'return' => [ 'mocked' => [] ] ] );
		$this->assertSame( [ 'mocked' => [] ], get_registered_theme_features() );
	}

	public function test__get_registered_theme_feature(): void {
		register_theme_feature( 'feature-one', [ 'type' => 'boolean' ] );
		$this->assertSame( 'boolean', get_registered_theme_feature( 'feature-one' )['type'] );
	}

	public function test__get_registered_theme_feature_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'get_registered_theme_feature', [ 'return' => [ 'type' => 'mocked' ] ] );
		$this->assertSame( [ 'type' => 'mocked' ], get_registered_theme_feature( 'feature' ) );
	}

	public function test__get_stylesheet(): void {
		$GLOBALS['stub_wp_options']->stylesheet = 'child-theme';
		$this->assertSame( 'child-theme', get_stylesheet() );
	}

	public function test__get_stylesheet_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'get_stylesheet', [ 'return' => 'mocked-stylesheet' ] );
		$this->assertSame( 'mocked-stylesheet', get_stylesheet() );
	}

	public function test__get_template(): void {
		$GLOBALS['stub_wp_options']->template = 'parent-theme';
		$this->assertSame( 'parent-theme', get_template() );
	}

	public function test__get_template_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'get_template', [ 'return' => 'mocked-template' ] );
		$this->assertSame( 'mocked-template', get_template() );
	}
}
