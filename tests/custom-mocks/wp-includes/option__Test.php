<?php

use Unitest_WP_Copy\WP_Options;

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class option__custom_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		WP_Mock::setUp();
		WP_Options::save_state();
	}

	protected function tearDown(): void {
		WP_Options::restore_state();
		WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_option() {
		WP_Options::set( 'runtime_test', 'stored' );
		WP_Options::set( 'runtime_false', false );
		WP_Options::set( 'runtime_null', null );
		WP_Options::set( 'runtime_serialized', 'a:1:{s:3:"key";s:5:"value";}' );
		WP_Options::set( 'siteurl', 'https://example.com/' );
		WP_Options::set( 'category_base', '/category/' );

		$this->assertSame( 'stored', get_option( ' runtime_test ' ) );
		$this->assertFalse( get_option( 'runtime_false', 'fallback' ) );
		$this->assertNull( get_option( 'runtime_null', 'fallback' ) );
		$this->assertSame( 'fallback', get_option( 'missing_runtime_test', 'fallback' ) );
		$this->assertSame( [ 'key' => 'value' ], get_option( 'runtime_serialized' ) );
		$this->assertSame( 'https://example.com', get_option( 'siteurl' ) );
		$this->assertSame( '/category', get_option( 'category_base' ) );
		$this->assertFalse( get_option( '' ) );

		$option_filter = static fn( $value ) => "filtered-$value";
		add_filter( 'option_runtime_test', $option_filter );
		$this->assertSame( 'filtered-stored', get_option( 'runtime_test' ) );
		remove_filter( 'option_runtime_test', $option_filter );

		$default_filter = static fn( $value ) => "filtered-$value";
		add_filter( 'default_option_missing_runtime_test', $default_filter );
		$this->assertSame( 'filtered-fallback', get_option( 'missing_runtime_test', 'fallback' ) );
		remove_filter( 'default_option_missing_runtime_test', $default_filter );

		$pre_filter = static fn() => 'preempted';
		add_filter( 'pre_option_runtime_test', $pre_filter );
		$this->assertSame( 'preempted', get_option( 'runtime_test' ) );
		remove_filter( 'pre_option_runtime_test', $pre_filter );
	}

	public function test__get_option__mockable_handler() {
		WP_Options::set( 'runtime_test', 'stored' );
		WP_Mock::userFunction( 'get_option', [
			'return' => 'mocked',
		] );

		$this->assertSame( 'stored', get_option( 'runtime_test', 'fallback' ) );
		$this->assertSame( 'mocked', get_option( 'missing_runtime_test', 'fallback' ) );
	}

	public function test__get_site_option() {
		WP_Options::set( 'runtime_network_test', 'single-site' );
		WP_Options::set_site( 'runtime_network_test', 'multisite' );
		WP_Options::set_site( 'runtime_false', false );
		WP_Options::set_site( 'runtime_null', null );

		$this->assertSame( 'single-site', get_site_option( 'runtime_network_test' ) );

		WP_Mock::userFunction( 'is_multisite', [ 'return' => true ] );
		$this->assertSame( 'multisite', get_site_option( 'runtime_network_test' ) );
		$this->assertFalse( get_site_option( 'runtime_false', 'fallback' ) );
		$this->assertNull( get_site_option( 'runtime_null', 'fallback' ) );
		$this->assertSame( 'fallback', get_site_option( 'missing_network_test', 'fallback' ) );
		$this->assertFalse( get_site_option( '' ) );

		$option_filter = static fn( $value ) => "filtered-$value";
		add_filter( 'site_option_runtime_network_test', $option_filter );
		$this->assertSame( 'filtered-multisite', get_site_option( 'runtime_network_test' ) );
		remove_filter( 'site_option_runtime_network_test', $option_filter );

		$default_filter = static fn( $value ) => "filtered-$value";
		add_filter( 'default_site_option_missing_network_test', $default_filter );
		$this->assertSame( 'filtered-fallback', get_site_option( 'missing_network_test', 'fallback' ) );
		remove_filter( 'default_site_option_missing_network_test', $default_filter );

		$pre_filter = static fn() => 'preempted';
		add_filter( 'pre_site_option_runtime_network_test', $pre_filter );
		$this->assertSame( 'preempted', get_site_option( 'runtime_network_test' ) );
		remove_filter( 'pre_site_option_runtime_network_test', $pre_filter );
	}

	public function test__get_site_option__mockable_handler() {
		WP_Options::set_site( 'runtime_network_test', 'stored' );
		WP_Mock::userFunction( 'is_multisite', [ 'return' => true ] );
		WP_Mock::userFunction( 'get_site_option', [
			'return' => 'mocked',
		] );

		$this->assertSame( 'stored', get_site_option( 'runtime_network_test', 'fallback' ) );
		$this->assertSame( 'mocked', get_site_option( 'missing_network_test', 'fallback' ) );
	}

}
