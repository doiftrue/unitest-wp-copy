<?php

class robots_template__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['stub_wp_options']->blog_public = '1';
	}

	protected function tearDown(): void {
		$GLOBALS['stub_wp_options']->blog_public = '1';
		parent::tearDown();
	}

	public function test__wp_robots() {
		// Empty filter result — no output
		ob_start();
		wp_robots();
		$output = ob_get_clean();
		$this->assertSame( '', $output );

		// With directives via filter
		add_filter( 'wp_robots', function( $robots ) {
			$robots['noindex'] = true;
			$robots['max-image-preview'] = 'large';
			return $robots;
		} );

		ob_start();
		wp_robots();
		$output = ob_get_clean();

		$this->assertStringContainsString( "meta name='robots'", $output );
		$this->assertStringContainsString( 'noindex', $output );
		$this->assertStringContainsString( 'max-image-preview:large', $output );

		remove_all_filters( 'wp_robots' );
	}

	public function test__wp_robots_noindex() {
		// blog_public = 1 → passthrough
		$GLOBALS['stub_wp_options']->blog_public = '1';
		$robots = wp_robots_noindex( [] );
		$this->assertSame( [], $robots );

		// blog_public = 0 → adds noindex+nofollow
		$GLOBALS['stub_wp_options']->blog_public = '0';
		$robots = wp_robots_noindex( [] );
		$this->assertTrue( $robots['noindex'] );
		$this->assertTrue( $robots['nofollow'] );
	}

	public function test__wp_robots_no_robots() {
		// blog_public = 1 → noindex + follow
		$GLOBALS['stub_wp_options']->blog_public = '1';
		$robots = wp_robots_no_robots( [] );
		$this->assertTrue( $robots['noindex'] );
		$this->assertTrue( $robots['follow'] );
		$this->assertArrayNotHasKey( 'nofollow', $robots );

		// blog_public = 0 → noindex + nofollow
		$GLOBALS['stub_wp_options']->blog_public = '0';
		$robots = wp_robots_no_robots( [] );
		$this->assertTrue( $robots['noindex'] );
		$this->assertTrue( $robots['nofollow'] );
		$this->assertArrayNotHasKey( 'follow', $robots );
	}

	public function test__wp_robots_sensitive_page() {
		$robots = wp_robots_sensitive_page( [] );
		$this->assertTrue( $robots['noindex'] );
		$this->assertTrue( $robots['noarchive'] );

		// Preserves existing directives
		$robots = wp_robots_sensitive_page( [ 'follow' => true ] );
		$this->assertTrue( $robots['follow'] );
		$this->assertTrue( $robots['noindex'] );
		$this->assertTrue( $robots['noarchive'] );
	}

	public function test__wp_robots_max_image_preview_large() {
		// blog_public = 1 → adds max-image-preview
		$GLOBALS['stub_wp_options']->blog_public = '1';
		$robots = wp_robots_max_image_preview_large( [] );
		$this->assertSame( 'large', $robots['max-image-preview'] );

		// blog_public = 0 → no change
		$GLOBALS['stub_wp_options']->blog_public = '0';
		$robots = wp_robots_max_image_preview_large( [] );
		$this->assertArrayNotHasKey( 'max-image-preview', $robots );
	}
}
