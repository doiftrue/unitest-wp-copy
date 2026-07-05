<?php

class error_protection__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_get_extension_error_description() {
		$result = wp_get_extension_error_description( [
			'type'    => E_USER_ERROR,
			'line'    => 12,
			'file'    => '/tmp/plugin.php',
			'message' => 'Failure',
		] );

		$this->assertStringContainsString( '<code>E_USER_ERROR</code>', $result );
		$this->assertStringContainsString( '<code>12</code>', $result );
		$this->assertStringContainsString( '<code>/tmp/plugin.php</code>', $result );
		$this->assertStringContainsString( '<code>Failure</code>', $result );
	}

	public function test__wp_is_fatal_error_handler_enabled() {
		$this->assertTrue( wp_is_fatal_error_handler_enabled() );

		add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );
		$this->assertFalse( wp_is_fatal_error_handler_enabled() );
		remove_all_filters( 'wp_fatal_error_handler_enabled' );
	}
}
