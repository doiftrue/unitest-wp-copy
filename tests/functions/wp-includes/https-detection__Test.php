<?php

use Unitest_WP_Copy\WP_Options;

class https_detection__Test extends \PHPUnit\Framework\TestCase {

	private string $home;
	private string $siteurl;

	protected function setUp(): void {
		parent::setUp();
		$this->home    = get_option( 'home' );
		$this->siteurl = get_option( 'siteurl' );
	}

	protected function tearDown(): void {
		WP_Options::set( 'home', $this->home );
		WP_Options::set( 'siteurl', $this->siteurl );
		parent::tearDown();
	}

	public function test__wp_is_home_url_using_https() {
		WP_Options::set( 'home', 'https://wp.test' );
		$this->assertTrue( wp_is_home_url_using_https() );

		WP_Options::set( 'home', 'http://wp.test' );
		$this->assertFalse( wp_is_home_url_using_https() );
	}

	public function test__wp_is_site_url_using_https() {
		WP_Options::set( 'siteurl', 'https://wp.test' );
		$this->assertTrue( wp_is_site_url_using_https() );

		WP_Options::set( 'siteurl', 'http://wp.test' );
		$this->assertFalse( wp_is_site_url_using_https() );
	}

	public function test__wp_is_using_https() {
		WP_Options::set( 'home', 'https://wp.test' );
		WP_Options::set( 'siteurl', 'https://wp.test' );
		$this->assertTrue( wp_is_using_https() );

		WP_Options::set( 'siteurl', 'http://wp.test' );
		$this->assertFalse( wp_is_using_https() );

		WP_Options::set( 'home', 'http://wp.test' );
		WP_Options::set( 'siteurl', 'https://wp.test' );
		$this->assertFalse( wp_is_using_https() );
	}

	public function test__wp_is_local_html_output() {
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		$this->assertNull( wp_is_local_html_output( '<html></html>' ) );

		add_action( 'wp_head', 'rsd_link' );
		$this->assertTrue( wp_is_local_html_output( '<link href="//wp.test/xmlrpc.php?rsd">' ) );
		$this->assertFalse( wp_is_local_html_output( '<html></html>' ) );
		remove_action( 'wp_head', 'rsd_link' );

		add_action( 'wp_head', 'rest_output_link_wp_head' );
		$this->assertTrue( wp_is_local_html_output( '<link href="//wp.test/wp-json/">' ) );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
	}

}
