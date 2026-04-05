<?php

use PHPUnit\Framework\TestCase;

class load__Test extends TestCase {

	public function test__wp_get_environment_type() {
		$this->assertSame( 'local', wp_get_environment_type() );
	}

	public function test__wp_get_development_mode() {
		$this->assertSame( '', wp_get_development_mode() );
	}

	public function test__is_admin() {
		$this->assertFalse( is_admin() );
	}

	public function test__is_multisite() {
		$this->assertFalse( is_multisite() );
	}

	public function test__absint() {
		$this->assertSame( 5, absint( -5 ) );
	}

	public function test__is_ssl() {
		$prev_https = $_SERVER['HTTPS'] ?? null;
		$_SERVER['HTTPS'] = 'on';
		$this->assertTrue( is_ssl() );
		unset( $_SERVER['HTTPS'] );
		$this->assertFalse( is_ssl() );
		if ( null !== $prev_https ) {
			$_SERVER['HTTPS'] = $prev_https;
		}
	}

	public function test__wp_convert_hr_to_bytes() {
		$this->assertSame( 2 * MB_IN_BYTES, wp_convert_hr_to_bytes( '2M' ) );
	}

	public function test__wp_is_ini_value_changeable() {
		$this->assertIsBool( wp_is_ini_value_changeable( 'memory_limit' ) );
	}

	public function test__wp_doing_ajax() {
		$this->assertFalse( wp_doing_ajax() );
	}

	public function test__is_wp_error() {
		$this->assertTrue( is_wp_error( new WP_Error( 'e', 'msg' ) ) );
		$this->assertFalse( is_wp_error( new stdClass() ) );
	}

}
