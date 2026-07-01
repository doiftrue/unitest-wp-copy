<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class formatting_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__balanceTags(): void {
		$this->assertSame( '<b>a</b>', balanceTags( '<b>a', true ) );
	}

	public function test__balanceTags_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'balanceTags', [ 'return' => 'mocked-balanced' ] );

		$this->assertSame( 'mocked-balanced', balanceTags( '<b>a' ) );
	}

	public function test__antispambot(): void {
		$this->assertIsString( antispambot( 'test@example.com' ) );
	}

	public function test__antispambot_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'antispambot', [ 'return' => 'mocked-email' ] );

		$this->assertSame( 'mocked-email', antispambot( 'test@example.com' ) );
	}

	public function test__convert_smilies(): void {
		$this->assertIsString( convert_smilies( ':)' ) );
	}

	public function test__convert_smilies_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'convert_smilies', [ 'return' => 'mocked-smiley' ] );

		$this->assertSame( 'mocked-smiley', convert_smilies( ':)' ) );
	}
}
