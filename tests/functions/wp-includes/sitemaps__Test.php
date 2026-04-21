<?php

class sitemaps__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_filter']         = [];
		$GLOBALS['wp_actions']        = [];
		$GLOBALS['wp_filters']        = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	public function test__wp_sitemaps_get_max_urls() {
		$this->assertSame( 2000, wp_sitemaps_get_max_urls( 'post' ) );

		$cb = static function ( $max_urls, $object_type ) {
			return 'post' === $object_type ? 125 : $max_urls;
		};
		add_filter( 'wp_sitemaps_max_urls', $cb, 10, 2 );

		$this->assertSame( 125, wp_sitemaps_get_max_urls( 'post' ) );
		$this->assertSame( 2000, wp_sitemaps_get_max_urls( 'term' ) );

		remove_filter( 'wp_sitemaps_max_urls', $cb, 10 );
	}

}
