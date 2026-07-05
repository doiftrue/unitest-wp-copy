<?php

class option__Test extends \PHPUnit\Framework\TestCase {
	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['new_allowed_options'] = [];
		$GLOBALS['wp_registered_settings'] = [];
	}

	public function test__wp_determine_option_autoload_value() {
		$this->assertSame( 'on', wp_determine_option_autoload_value( 'key', 'value', 'value', true ) );
		$this->assertSame( 'auto', wp_determine_option_autoload_value( 'key', 'value', 'value', null ) );
	}

	public function test__wp_filter_default_autoload_value_via_option_size() {
		$this->assertNull( wp_filter_default_autoload_value_via_option_size( null, 'key', '', 'small' ) );
		$this->assertFalse( wp_filter_default_autoload_value_via_option_size( null, 'key', '', str_repeat( 'x', 150001 ) ) );
	}

	public function test__get_registered_settings() {
		$this->assertSame( [], get_registered_settings() );
	}

	public function test__filter_default_option() {
		$GLOBALS['wp_registered_settings']['key'] = [ 'default' => 'value' ];
		$this->assertSame( 'value', filter_default_option( false, 'key', false ) );
	}

	public function test__register_setting() {
		register_setting( 'general', 'key', [ 'default' => 'value' ] );
		$this->assertArrayHasKey( 'key', get_registered_settings() );
	}

	public function test__unregister_setting() {
		register_setting( 'general', 'key' );
		unregister_setting( 'general', 'key' );
		$this->assertArrayNotHasKey( 'key', get_registered_settings() );
	}

	public function test__wp_autoload_values_to_autoload() {
		$this->assertSame( [ 'yes', 'on', 'auto-on', 'auto' ], wp_autoload_values_to_autoload() );
	}
}
