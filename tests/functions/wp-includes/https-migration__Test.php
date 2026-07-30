<?php

class https_migration__Test extends \PHPUnit\Framework\TestCase {

	private object $stub_wp_options;

	protected function setUp(): void {
		parent::setUp();
		$this->stub_wp_options = clone $GLOBALS['stub_wp_options'];
	}

	protected function tearDown(): void {
		$GLOBALS['stub_wp_options'] = clone $this->stub_wp_options;
		remove_all_filters( 'wp_should_replace_insecure_home_url' );
		parent::tearDown();
	}

	public function test__wp_should_replace_insecure_home_url() {
		$this->assertFalse( wp_should_replace_insecure_home_url() );

		$GLOBALS['stub_wp_options']->https_migration_required = true;
		$this->assertTrue( wp_should_replace_insecure_home_url() );

		add_filter( 'wp_should_replace_insecure_home_url', '__return_false' );
		$this->assertFalse( wp_should_replace_insecure_home_url() );
	}

	public function test__wp_replace_insecure_home_url() {
		$content = 'http://wp.test/path http:\/\/wp.test\/escaped';
		$this->assertSame( $content, wp_replace_insecure_home_url( $content ) );

		$GLOBALS['stub_wp_options']->https_migration_required = true;
		$this->assertSame(
			'https://wp.test/path https:\/\/wp.test\/escaped',
			wp_replace_insecure_home_url( $content )
		);
	}
}
