<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class general_template_mocks__Test extends \PHPUnit\Framework\TestCase {

	private object $initial_stub_wp_options;
	private string $initial_wp_version;

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();

		$this->initial_stub_wp_options = clone $GLOBALS['stub_wp_options'];
		$this->initial_wp_version      = (string) ( $GLOBALS['wp_version'] ?? '' );

		$GLOBALS['wp_filter']         = [];
		$GLOBALS['wp_actions']        = [];
		$GLOBALS['wp_filters']        = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	protected function tearDown(): void {
		$GLOBALS['stub_wp_options'] = clone $this->initial_stub_wp_options;
		$GLOBALS['wp_version']      = $this->initial_wp_version;

		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_bloginfo_basic_fields() {
		$GLOBALS['stub_wp_options']->html_type       = 'application/xhtml+xml';
		$GLOBALS['stub_wp_options']->language        = 'fr-FR';
		$GLOBALS['stub_wp_options']->blogdescription = 'Example tagline';
		$GLOBALS['stub_wp_options']->blog_charset    = 'ISO-8859-1';
		$GLOBALS['stub_wp_options']->admin_email     = 'admin@example.test';
		$GLOBALS['stub_wp_options']->home            = 'https://test.loc';
		$GLOBALS['stub_wp_options']->siteurl         = 'https://test.loc';
		$GLOBALS['stub_wp_options']->stylesheet      = 'child-theme';
		$GLOBALS['stub_wp_options']->template        = 'parent-theme';

		$this->assertSame( 'application/xhtml+xml', get_bloginfo( 'html_type' ) );
		$this->assertSame( 'fr-FR', get_bloginfo( 'language' ) );
		$this->assertSame( 'Example tagline', get_bloginfo( 'description' ) );
		$this->assertSame( 'ISO-8859-1', get_bloginfo( 'charset' ) );
		$this->assertSame( 'admin@example.test', get_bloginfo( 'admin_email' ) );
		$this->assertSame( 'https://test.loc', get_bloginfo( 'home' ) );
		$this->assertSame( 'https://test.loc', get_bloginfo( 'siteurl' ) );
		$this->assertSame( site_url(), get_bloginfo( 'wpurl' ) );

		$this->assertSame( home_url(), get_bloginfo( 'url' ) );
		$this->assertStringContainsString( '/wp-content/themes/child-theme/style.css', get_bloginfo( 'stylesheet_url' ) );
		$this->assertStringContainsString( '/wp-content/themes/child-theme', get_bloginfo( 'stylesheet_directory' ) );
		$this->assertStringContainsString( '/wp-content/themes/parent-theme', get_bloginfo( 'template_directory' ) );
		$this->assertStringContainsString( '/wp-content/themes/parent-theme', get_bloginfo( 'template_url' ) );
		$this->assertMatchesRegularExpression( '/\d.\d.\d/', get_bloginfo( 'version' ) );
	}

	public function test__get_bloginfo_display_filters() {
		$GLOBALS['stub_wp_options']->blogdescription = 'tagline';

		$cb1 = static function ( $value, $show ) {
			return ( 'description' === $show ) ? strtoupper( $value ) : $value;
		};
		add_filter( 'bloginfo', $cb1, 10, 2 );

		$cb2 = static function ( $value, $show ) {
			return ( 'url' === $show ) ? "$value/filtered" : $value;
		};
		add_filter( 'bloginfo_url', $cb2, 10, 2 );

		$this->assertSame( 'TAGLINE', get_bloginfo( 'description', 'display' ) );
		$this->assertSame( home_url() . '/filtered', get_bloginfo( 'url', 'display' ) );
	}

	public function test__bloginfo_echoes_display_value() {
		$GLOBALS['stub_wp_options']->blogdescription = 'tagline';

		ob_start();
		bloginfo( 'description' );
		$out = ob_get_clean();

		$this->assertSame( 'tagline', $out );
	}

	public function test__wp_get_wp_version() {
		if( $wp_ver = wp_version_compare( '< 6.7.0' ) ){
			$this->markTestSkipped( "wp_version_compare() not exists on WP $wp_ver" );
		}

		$this->assertMatchesRegularExpression( '/\d.\d.\d/', wp_get_wp_version() );
	}

	public function test__wp_get_wp_version_wp_mock_handler() {
		if( $wp_ver = wp_version_compare( '< 6.7.0' ) ){
			$this->markTestSkipped( "wp_version_compare() not exists on WP $wp_ver" );
		}

		\WP_Mock::userFunction( 'wp_get_wp_version', [ 'return' => 'mocked-version' ] );

		$this->assertSame( 'mocked-version', wp_get_wp_version() );
	}

}
