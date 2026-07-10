<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class rest_api__custom_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();

		$GLOBALS['wp_filter']         = [];
		$GLOBALS['wp_actions']        = [];
		$GLOBALS['wp_filters']        = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_rest_url(): void {
		$this->assertSame( 'https://wp.test/wp-json/', get_rest_url() );
		$this->assertSame( 'https://wp.test/wp-json/wp/v2/posts', get_rest_url( null, '/wp/v2/posts' ) );
		$this->assertSame( 'http://wp.test/wp-json/wp/v2/posts', get_rest_url( null, 'wp/v2/posts', 'http' ) );

		add_filter(
			'rest_url',
			static function ( $url, $path, $blog_id, $scheme ) {
				return "$url?path=$path&blog=$blog_id&scheme=$scheme";
			},
			10,
			4
		);

		$this->assertSame(
			'https://wp.test/wp-json/wp/v2/types?path=/wp/v2/types&blog=12&scheme=rest',
			get_rest_url( 12, 'wp/v2/types' )
		);
	}

	public function test__get_rest_url__mockable_handler(): void {
		\WP_Mock::userFunction( 'get_rest_url', [ 'return' => 'https://api.test/custom-root/' ] );

		$this->assertSame( 'https://api.test/custom-root/', get_rest_url( null, '/wp/v2/posts' ) );
	}

}
