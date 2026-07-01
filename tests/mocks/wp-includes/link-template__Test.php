<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class link_template_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__includes_url(): void {
		$this->assertStringContainsString( '/wp-includes/x.js', includes_url( 'x.js' ) );
	}

	public function test__includes_url_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'includes_url', [ 'return' => 'https://mocked.test/includes/x.js' ] );
		$this->assertSame( 'https://mocked.test/includes/x.js', includes_url( 'x.js' ) );
	}

	public function test__content_url(): void {
		$this->assertStringContainsString( '/wp-content/a.css', content_url( 'a.css' ) );
	}

	public function test__content_url_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'content_url', [ 'return' => 'https://mocked.test/content/a.css' ] );
		$this->assertSame( 'https://mocked.test/content/a.css', content_url( 'a.css' ) );
	}

	public function test__plugins_url(): void {
		$url = plugins_url( 'asset.js', '/path/to/wp/wp-content/plugins/my-plugin/main.php' );
		$this->assertStringContainsString( '/plugins/my-plugin/asset.js', $url );
	}

	public function test__plugins_url_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'plugins_url', [ 'return' => 'https://mocked.test/plugins/asset.js' ] );
		$this->assertSame( 'https://mocked.test/plugins/asset.js', plugins_url( 'asset.js' ) );
	}
}
