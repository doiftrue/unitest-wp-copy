<?php

use Unitest_WP_Copy\WP_Options;

class https_migration__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		WP_Options::save_state();
	}

	protected function tearDown(): void {
		WP_Options::restore_state();
		remove_all_filters( 'wp_should_replace_insecure_home_url' );
		parent::tearDown();
	}

	public function test__wp_should_replace_insecure_home_url() {
		$this->assertFalse( wp_should_replace_insecure_home_url() );

		WP_Options::set( 'https_migration_required', true );
		$this->assertTrue( wp_should_replace_insecure_home_url() );

		add_filter( 'wp_should_replace_insecure_home_url', '__return_false' );
		$this->assertFalse( wp_should_replace_insecure_home_url() );
	}

	public function test__wp_replace_insecure_home_url() {
		$content = 'http://wp.test/path http:\/\/wp.test\/escaped';
		$this->assertSame( $content, wp_replace_insecure_home_url( $content ) );

		WP_Options::set( 'https_migration_required', true );
		$this->assertSame(
			'https://wp.test/path https:\/\/wp.test\/escaped',
			wp_replace_insecure_home_url( $content )
		);
	}

}
