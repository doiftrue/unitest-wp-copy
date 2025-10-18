<?php

use PHPUnit\Framework\TestCase;

class default_contants_Test extends TestCase {

	/** @covers ::wp_initial_constants() */
	public function test__wp_initial_constants(): void {
		$exact = [
			'KB_IN_BYTES'        => 1024,
			'MB_IN_BYTES'        => 1024 * 1024,
			'GB_IN_BYTES'        => 1024 * 1024 * 1024,
			'MINUTE_IN_SECONDS'  => 60,
			'HOUR_IN_SECONDS'    => 60 * 60,
			'DAY_IN_SECONDS'     => 24 * 60 * 60,
			'WEEK_IN_SECONDS'    => 7 * 24 * 60 * 60,
			'MONTH_IN_SECONDS'   => 30 * 24 * 60 * 60,
			'YEAR_IN_SECONDS'    => 365 * 24 * 60 * 60,
		];
		foreach ( $exact as $name => $expected ) {
			$this->assertTrue( defined( $name ), "$name should be defined" );
			$this->assertSame( $expected, constant( $name ), "$name should be $expected" );
		}

		$this->assertTrue( defined( 'TB_IN_BYTES' ) );
		$this->assertTrue( defined( 'PB_IN_BYTES' ) );
		$this->assertTrue( defined( 'EB_IN_BYTES' ) );
		$this->assertTrue( defined( 'ZB_IN_BYTES' ) );
		$this->assertTrue( defined( 'YB_IN_BYTES' ) );

		$this->assertSame( 1024 * KB_IN_BYTES, MB_IN_BYTES );
		$this->assertSame( 1024 * MB_IN_BYTES, GB_IN_BYTES );

		$this->assertEquals( 1024 * GB_IN_BYTES, TB_IN_BYTES );
		$this->assertEquals( 1024 * TB_IN_BYTES, PB_IN_BYTES );
		$this->assertEquals( 1024 * PB_IN_BYTES, EB_IN_BYTES );
		$this->assertEquals( 1024 * EB_IN_BYTES, ZB_IN_BYTES );
		$this->assertEquals( 1024 * ZB_IN_BYTES, YB_IN_BYTES );

		$this->assertSame( 60, MINUTE_IN_SECONDS );
		$this->assertSame( 60 * MINUTE_IN_SECONDS, HOUR_IN_SECONDS );
		$this->assertSame( 24 * HOUR_IN_SECONDS, DAY_IN_SECONDS );
		$this->assertSame( 7 * DAY_IN_SECONDS, WEEK_IN_SECONDS );
		$this->assertSame( 30 * DAY_IN_SECONDS, MONTH_IN_SECONDS );
		$this->assertSame( 365 * DAY_IN_SECONDS, YEAR_IN_SECONDS );

		$this->assertTrue( defined( 'WP_START_TIMESTAMP' ) );
		$this->assertIsFloat( WP_START_TIMESTAMP );
		$this->assertGreaterThan( 0.0, WP_START_TIMESTAMP );
	}

	/** @covers ::wp_plugin_directory_constants() */
	public function test__wp_plugin_directory_constants(): void {
		$this->assertTrue( defined( 'WP_CONTENT_URL' ), 'WP_CONTENT_URL should be defined' );
		$this->assertTrue( defined( 'WP_PLUGIN_DIR' ), 'WP_PLUGIN_DIR should be defined' );
		$this->assertTrue( defined( 'WP_PLUGIN_URL' ), 'WP_PLUGIN_URL should be defined' );
		$this->assertTrue( defined( 'WPMU_PLUGIN_DIR' ), 'WPMU_PLUGIN_DIR should be defined' );
		$this->assertTrue( defined( 'WPMU_PLUGIN_URL' ), 'WPMU_PLUGIN_URL should be defined' );
		$this->assertTrue( defined( 'PLUGINDIR' ), 'PLUGINDIR should be defined' );
		$this->assertTrue( defined( 'MUPLUGINDIR' ), 'MUPLUGINDIR should be defined' );

		// Check expected values if possible.
		$this->assertStringContainsString( '/wp-content', WP_CONTENT_URL );
		$this->assertStringContainsString( '/plugins', WP_PLUGIN_DIR );
		$this->assertStringContainsString( '/plugins', WP_PLUGIN_URL );
		$this->assertStringContainsString( '/mu-plugins', WPMU_PLUGIN_DIR );
		$this->assertStringContainsString( '/mu-plugins', WPMU_PLUGIN_URL );
		$this->assertSame( 'wp-content/plugins', PLUGINDIR );
		$this->assertSame( 'wp-content/mu-plugins', MUPLUGINDIR );
	}

}
