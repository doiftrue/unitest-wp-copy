<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class compat_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test___wp_can_use_pcre_u(): void {
		if( wp_version_compare( '>= 6.9.0' ) ){
			$first = _wp_can_use_pcre_u();
			$this->assertIsBool( $first );
			$this->assertSame( $first, _wp_can_use_pcre_u() );
		} else {
			_wp_can_use_pcre_u( 1 );
			$this->assertSame( 1, _wp_can_use_pcre_u() );
			_wp_can_use_pcre_u( 0 );
			$this->assertSame( 0, _wp_can_use_pcre_u() );
		}
	}

	public function test___wp_can_use_pcre_u_wp_mock_handler(): void {
		\WP_Mock::userFunction( '_wp_can_use_pcre_u', [ 'return' => false ] );

		$this->assertFalse( _wp_can_use_pcre_u() );
	}
}
