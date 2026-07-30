<?php

class ms_blogs__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		global $wp_object_cache;
		$wp_object_cache = new WP_Object_Cache();

		$GLOBALS['blog_id']              = 1;
		$GLOBALS['current_blog_id']      = 1;
		$GLOBALS['_wp_switched_stack']   = [];
		$GLOBALS['switched']             = false;
	}

	protected function tearDown(): void {
		$GLOBALS['blog_id']              = 1;
		$GLOBALS['current_blog_id']      = 1;
		$GLOBALS['_wp_switched_stack']   = [];
		$GLOBALS['switched']             = false;

		parent::tearDown();
	}

	public function test__clean_site_details_cache() {
		wp_cache_set( 5, 'data', 'site-details' );
		wp_cache_set( 5, 'data', 'blog-details' );

		clean_site_details_cache( 5 );

		$this->assertFalse( wp_cache_get( 5, 'site-details' ) );
		$this->assertFalse( wp_cache_get( 5, 'blog-details' ) );

		// No arg = current blog
		$blog_id = get_current_blog_id();
		wp_cache_set( $blog_id, 'data', 'site-details' );

		clean_site_details_cache();

		$this->assertFalse( wp_cache_get( $blog_id, 'site-details' ) );
	}

}
