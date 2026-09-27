<?php

use Unitest_WP_Copy\WP_Options;
use Unitest_WP_Copy\WP_Runtime;

require_once TESTS_ROOT_DIR . '/Project_TestCase.php';

class WP_Options__Test extends Project_TestCase {

	public function test__set_and_set_site(): void {
		WP_Options::save_state();

		try {
			WP_Options::set( 'runtime_option_test', false );
			WP_Options::set_site( 'runtime_site_option_test', null );

			$this->assertFalse( get_option( 'runtime_option_test', 'fallback' ) );

			\WP_Mock::userFunction( 'is_multisite', [ 'return' => true ] );
			$this->assertNull( get_site_option( 'runtime_site_option_test', 'fallback' ) );
		}
		finally {
			WP_Options::restore_state();
		}
	}

	public function test__save_state_and_restore_state(): void {
		$original = get_option( 'blogname' );

		WP_Options::save_state();
		WP_Options::set( 'blogname', 'First' );
		WP_Options::save_state();
		WP_Options::set( 'blogname', 'Second' );

		WP_Options::restore_state();
		$this->assertSame( 'First', get_option( 'blogname' ) );

		WP_Options::restore_state();
		$this->assertSame( $original, get_option( 'blogname' ) );
	}

	public function test__restore_state_without_save_throws(): void {
		$this->expectException( LogicException::class );

		WP_Options::restore_state();
	}

	public function test__runtime_boot_and_bootstrap_init_are_compatible(): void {
		$this->assertSame( WP_Runtime::boot(), \Unitest_WP_Copy\Bootstrap::init() );
	}

}
