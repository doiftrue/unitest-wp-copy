<?php

// Needed for WP_Mock to enable multisite mode in get_site_option().
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class ms_functions__Test extends \PHPUnit\Framework\TestCase {

	private object $stub_wp_options;
	private object $stub_wp_site_options;

	protected function setUp(): void {
		parent::setUp();

		$this->stub_wp_options      = clone $GLOBALS['stub_wp_options'];
		$this->stub_wp_site_options = clone $GLOBALS['stub_wp_site_options'];

		global $wp_object_cache;
		$wp_object_cache = new WP_Object_Cache();

		\WP_Mock::setUp();
		\WP_Mock::userFunction( 'is_multisite', [ 'return' => true ] );
	}

	protected function tearDown(): void {
		$GLOBALS['stub_wp_options']      = clone $this->stub_wp_options;
		$GLOBALS['stub_wp_site_options'] = clone $this->stub_wp_site_options;
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__is_email_address_unsafe() {
		$GLOBALS['stub_wp_site_options']->banned_email_domains = [ 'blocked.test' ];

		$this->assertTrue( is_email_address_unsafe( 'user@blocked.test' ) );
		$this->assertTrue( is_email_address_unsafe( 'user@sub.blocked.test' ) );
		$this->assertFalse( is_email_address_unsafe( 'user@allowed.test' ) );
	}

	public function test__check_upload_mimes() {
		$GLOBALS['stub_wp_site_options']->upload_filetypes = 'jpg png';

		$this->assertSame(
			[
				'jpg|jpeg|jpe' => 'image/jpeg',
				'png'          => 'image/png',
			],
			check_upload_mimes( [
				'jpg|jpeg|jpe' => 'image/jpeg',
				'png'          => 'image/png',
				'pdf'          => 'application/pdf',
			] )
		);
	}

	public function test__upload_is_file_too_big() {
		$GLOBALS['stub_wp_site_options']->fileupload_maxk = 1;

		$small = [ 'bits' => str_repeat( 'x', KB_IN_BYTES ) ];
		$large = [ 'bits' => str_repeat( 'x', KB_IN_BYTES + 1 ) ];

		$this->assertSame( $small, upload_is_file_too_big( $small ) );
		$this->assertIsString( upload_is_file_too_big( $large ) );

		$GLOBALS['stub_wp_site_options']->upload_space_check_disabled = true;
		$this->assertSame( $large, upload_is_file_too_big( $large ) );
	}

	public function test__users_can_register_signup_filter() {
		$GLOBALS['stub_wp_site_options']->registration = 'all';
		$this->assertTrue( users_can_register_signup_filter() );

		$GLOBALS['stub_wp_site_options']->registration = 'none';
		$this->assertFalse( users_can_register_signup_filter() );
	}

	public function test__get_space_allowed() {
		$GLOBALS['stub_wp_options']->blog_upload_space = 25;
		$this->assertSame( 25, get_space_allowed() );

		$GLOBALS['stub_wp_options']->blog_upload_space      = false;
		$GLOBALS['stub_wp_site_options']->blog_upload_space = 50;
		$this->assertSame( 50, get_space_allowed() );
	}

	public function test__force_ssl_content() {
		// Default is false
		$this->assertFalse( force_ssl_content() );

		if( wp_version_compare( '< 6.9.0' ) ){
			$this->assertSame( '', force_ssl_content( true ) );
			$this->assertSame( '', force_ssl_content() );
		} else {
			// Set to true, returns old value
			$old = force_ssl_content( true );
			$this->assertFalse( $old );
			$this->assertTrue( force_ssl_content() );

			// Reset
			force_ssl_content( false );
			$this->assertFalse( force_ssl_content() );
		}
	}

	public function test__filter_SSL() {
		// When force_ssl_content is false, URL returned as-is
		force_ssl_content( false );
		$this->assertSame( 'http://example.com/page', filter_SSL( 'http://example.com/page' ) );

		// When force and ssl, URL is https
		force_ssl_content( true );
		$_SERVER['HTTPS'] = 'on';
		$expected = wp_version_compare( '< 6.9.0' ) ? 'http://example.com/page' : 'https://example.com/page';
		$this->assertSame( $expected, filter_SSL( 'http://example.com/page' ) );

		// Non-string input returns bloginfo url
		$this->assertIsString( filter_SSL( null ) );

		force_ssl_content( false );
		unset( $_SERVER['HTTPS'] );
	}

	public function test__get_subdirectory_reserved_names() {
		$names = get_subdirectory_reserved_names();

		$this->assertIsArray( $names );
		$this->assertContains( 'wp-admin', $names );
		$this->assertContains( 'wp-content', $names );
		$this->assertContains( 'wp-includes', $names );
		$this->assertContains( 'wp-json', $names );
	}
}
