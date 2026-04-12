<?php

class load__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_get_environment_type() {
		$this->assertSame( 'local', wp_get_environment_type() );
	}

	public function test__wp_get_development_mode() {
		$this->assertSame( '', wp_get_development_mode() );
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

	public function test__is_admin() {
		$GLOBALS['current_screen'] = new class {
			public function in_admin() {
				return true;
			}
		};

		$this->assertTrue( is_admin() );

		unset( $GLOBALS['current_screen'] );
		$this->assertFalse( is_admin() );
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

	public function test__wp_installing() {
		$initial = wp_installing();
		$this->assertIsBool( $initial );

		$old = wp_installing( true );
		$this->assertSame( $initial, $old );
		$this->assertTrue( wp_installing() );

		$old = wp_installing( false );
		$this->assertTrue( $old );
		$this->assertFalse( wp_installing() );

		wp_installing( $initial );
	}

	public function test__is_wp_error() {
		$this->assertTrue( is_wp_error( new WP_Error( 'e', 'msg' ) ) );
		$this->assertFalse( is_wp_error( new stdClass() ) );
	}

	public function test__get_current_blog_id() {
		$GLOBALS['blog_id'] = 12;
		$this->assertSame( 12, get_current_blog_id() );
	}

	public function test__get_current_network_id() {
		$this->assertSame( 1, get_current_network_id() );
	}

	public function test__timer_float() {
		$_SERVER['REQUEST_TIME_FLOAT'] = microtime( true ) - 0.5;
		$this->assertGreaterThan( 0, timer_float() );
	}

	public function test__timer_start() {
		$this->assertTrue( timer_start() );
		$this->assertIsFloat( $GLOBALS['timestart'] );
	}

	public function test__timer_stop() {
		timer_start();
		$result = timer_stop( 0, 3 );
		$this->assertIsString( $result );
		$this->assertMatchesRegularExpression( '/^\d+(?:[.,]\d+)?$/', $result );
	}

}
