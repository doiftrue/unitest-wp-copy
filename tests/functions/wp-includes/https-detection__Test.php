<?php

class https_detection__Test extends \PHPUnit\Framework\TestCase {

	private string $home;
	private string $siteurl;

	protected function setUp(): void {
		parent::setUp();
		$this->home    = $GLOBALS['stub_wp_options']->home;
		$this->siteurl = $GLOBALS['stub_wp_options']->siteurl;
	}

	protected function tearDown(): void {
		$GLOBALS['stub_wp_options']->home    = $this->home;
		$GLOBALS['stub_wp_options']->siteurl = $this->siteurl;
		parent::tearDown();
	}

	public function test__wp_is_home_url_using_https() {
		$GLOBALS['stub_wp_options']->home = 'https://wp.test';
		$this->assertTrue( wp_is_home_url_using_https() );

		$GLOBALS['stub_wp_options']->home = 'http://wp.test';
		$this->assertFalse( wp_is_home_url_using_https() );
	}

	public function test__wp_is_site_url_using_https() {
		$GLOBALS['stub_wp_options']->siteurl = 'https://wp.test';
		$this->assertTrue( wp_is_site_url_using_https() );

		$GLOBALS['stub_wp_options']->siteurl = 'http://wp.test';
		$this->assertFalse( wp_is_site_url_using_https() );
	}

	public function test__wp_is_using_https() {
		$GLOBALS['stub_wp_options']->home    = 'https://wp.test';
		$GLOBALS['stub_wp_options']->siteurl = 'https://wp.test';
		$this->assertTrue( wp_is_using_https() );

		$GLOBALS['stub_wp_options']->siteurl = 'http://wp.test';
		$this->assertFalse( wp_is_using_https() );

		$GLOBALS['stub_wp_options']->home    = 'http://wp.test';
		$GLOBALS['stub_wp_options']->siteurl = 'https://wp.test';
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
