<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class abilities_api__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__ability_lookup_helpers__mockable_handlers(): void {
		if ( $wp_ver = wp_version_compare( '< 6.9.0' ) ) {
			$this->markTestSkipped( "Ability API not exists on WP $wp_ver" );
		}

		\WP_Mock::userFunction( 'wp_has_ability', [ 'return' => true ] );
		\WP_Mock::userFunction( 'wp_get_ability', [ 'return' => null ] );
		\WP_Mock::userFunction( 'wp_has_ability_category', [ 'return' => true ] );
		\WP_Mock::userFunction( 'wp_get_ability_category', [ 'return' => null ] );
		\WP_Mock::userFunction( 'wp_get_ability_categories', [ 'return' => [ 'unitest' => null ] ] );

		$this->assertTrue( wp_has_ability( 'unitest/run' ) );
		$this->assertNull( wp_get_ability( 'unitest/run' ) );
		$this->assertTrue( wp_has_ability_category( 'unitest' ) );
		$this->assertNull( wp_get_ability_category( 'unitest' ) );
		$this->assertSame( [ 'unitest' => null ], wp_get_ability_categories() );
	}
}
