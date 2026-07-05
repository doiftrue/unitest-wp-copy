<?php

class user__Test extends \PHPUnit\Framework\TestCase {
	public function test__sanitize_user_field() {
		$this->assertSame( 7, sanitize_user_field( 'ID', '7', 7, 'raw' ) );
		$this->assertSame( 'name', sanitize_user_field( 'display_name', 'name', 7, 'raw' ) );
	}

	public function test__wp_get_session_token() {
		$this->assertSame( '', wp_get_session_token() );
	}

	public function test__validate_username() {
		$this->assertTrue( validate_username( 'john' ) );
		$this->assertFalse( validate_username( '' ) );
	}

	public function test__wp_get_password_hint() {
		$this->assertStringContainsString( 'twelve characters', wp_get_password_hint() );
	}

	public function test___wp_privacy_action_request_types() {
		$this->assertSame( [ 'export_personal_data', 'remove_personal_data' ], _wp_privacy_action_request_types() );
	}

	public function test__wp_register_user_personal_data_exporter() {
		$result = wp_register_user_personal_data_exporter( [] );
		$this->assertSame( 'wp_user_personal_data_exporter', $result['wordpress-user']['callback'] );
	}

	public function test__wp_user_request_action_description() {
		$this->assertSame( 'Export Personal Data', wp_user_request_action_description( 'export_personal_data' ) );
		$this->assertStringContainsString( 'custom', wp_user_request_action_description( 'custom' ) );
	}

	public function test__wp_is_application_passwords_supported() {
		$this->assertTrue( wp_is_application_passwords_supported() );
	}

	public function test__wp_is_application_passwords_available() {
		$this->assertTrue( wp_is_application_passwords_available() );
	}

	public function test__wp_cache_set_users_last_changed() {
		wp_cache_set_users_last_changed();
		$this->assertNotFalse( wp_cache_get( 'last_changed', 'users' ) );
	}
}
