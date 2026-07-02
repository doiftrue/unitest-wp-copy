<?php

use Parser\Source_Code_Replacer;

require_once TESTS_ROOT_DIR . '/Project_TestCase.php';

class Source_Code_Replacer__Test extends Project_TestCase {

	public function test__replace_in_code__replaces_runtime_compat_calls(): void {
		$replacer = new Source_Code_Replacer(
			$this->make_config( [
				'static_methods_data' => [],
			] )
		);

		$input  = 'return WpOrg\\Requests\\Ipv6::check_ipv6( $ip );';
		$output = $replacer->replace_in_code( $input );

		$this->assertSame( 'return WP_Http__is_ip_address( $ip );', $output );
	}

	public function test__replace_in_code__replaces_configured_static_methods(): void {
		$replacer = new Source_Code_Replacer(
			$this->make_config( [
				'static_methods_data' => [
					'wp-includes/class-wp-http.php' => [
						'class'   => 'WP_Http',
						'methods' => [ 'is_ip_address' => '' ],
					],
				],
			] )
		);

		$input  = 'return WP_Http::is_ip_address( $ip );';
		$output = $replacer->replace_in_code( $input );

		$this->assertSame( 'return WP_Http__is_ip_address( $ip );', $output );
	}
}
