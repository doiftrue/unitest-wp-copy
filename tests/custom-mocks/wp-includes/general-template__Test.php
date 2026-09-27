<?php

use Unitest_WP_Copy\WP_Options;

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class general_template__custom_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		WP_Mock::setUp();
		WP_Options::save_state();
		$GLOBALS['wp_filter']         = [];
		$GLOBALS['wp_actions']        = [];
		$GLOBALS['wp_filters']        = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	protected function tearDown(): void {
		WP_Options::restore_state();
		WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_bloginfo_basic_fields() {
		WP_Options::set( 'html_type', 'application/xhtml+xml' );
		WP_Options::set( 'language', 'fr-FR' );
		WP_Options::set( 'blogname', 'Example Site' );
		WP_Options::set( 'blogdescription', 'Example tagline' );
		WP_Options::set( 'blog_charset', 'ISO-8859-1' );
		WP_Options::set( 'admin_email', 'admin@example.test' );
		WP_Options::set( 'home', 'https://test.loc' );
		WP_Options::set( 'siteurl', 'https://test.loc' );
		WP_Options::set( 'stylesheet', 'child-theme' );
		WP_Options::set( 'template', 'parent-theme' );

		$this->assertSame( 'application/xhtml+xml', get_bloginfo( 'html_type' ) );
		$this->assertSame( 'fr-FR', get_bloginfo( 'language' ) );
		$this->assertSame( 'Example Site', get_bloginfo() );
		$this->assertSame( 'Example Site', get_bloginfo( 'name' ) );
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
		$this->assertMatchesRegularExpression( '/^\d+\.\d+(?:\.\d+)?/', get_bloginfo( 'version' ) );
	}

	public function test__get_bloginfo_display_filters() {
		WP_Options::set( 'blogdescription', 'tagline' );

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
		WP_Options::set( 'blogdescription', 'tagline' );

		ob_start();
		bloginfo( 'description' );
		$out = ob_get_clean();

		$this->assertSame( 'tagline', $out );
	}

}
