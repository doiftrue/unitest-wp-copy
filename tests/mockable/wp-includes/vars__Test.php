<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class vars__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
		unset( $_SERVER['HTTP_SEC_CH_UA_MOBILE'], $_SERVER['HTTP_USER_AGENT'] );
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		unset( $_SERVER['HTTP_SEC_CH_UA_MOBILE'], $_SERVER['HTTP_USER_AGENT'] );
		parent::tearDown();
	}

	public function test__wp_is_mobile() {
		$this->assertFalse( wp_is_mobile() );

		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) Mobile/15E148';
		$this->assertTrue( wp_is_mobile() );
	}

	public function test__wp_is_mobile_sec_ch_ua() {
		$_SERVER['HTTP_SEC_CH_UA_MOBILE'] = '?1';
		$this->assertTrue( wp_is_mobile() );

		$_SERVER['HTTP_SEC_CH_UA_MOBILE'] = '?0';
		$this->assertFalse( wp_is_mobile() );
	}

	public function test__wp_is_mobile_wp_mock_handler() {
		\WP_Mock::userFunction( 'wp_is_mobile', [ 'return' => true ] );
		$this->assertTrue( wp_is_mobile() );
	}
}
