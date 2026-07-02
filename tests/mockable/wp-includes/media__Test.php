<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class media__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
		$GLOBALS['_wp_additional_image_sizes'] = [];
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		$GLOBALS['_wp_additional_image_sizes'] = [];
		parent::tearDown();
	}

	public function test__wp_get_additional_image_sizes() {
		$this->assertSame( [], wp_get_additional_image_sizes() );
		$GLOBALS['_wp_additional_image_sizes']['card'] = [ 'width' => 320 ];
		$this->assertSame( [ 'card' => [ 'width' => 320 ] ], wp_get_additional_image_sizes() );
	}

	public function test__wp_get_additional_image_sizes__mockable_handler() {
		\WP_Mock::userFunction( 'wp_get_additional_image_sizes', [ 'return' => [ 'mocked' => [ 'width' => 10 ] ] ] );
		$this->assertSame( [ 'mocked' => [ 'width' => 10 ] ], wp_get_additional_image_sizes() );
	}
}
