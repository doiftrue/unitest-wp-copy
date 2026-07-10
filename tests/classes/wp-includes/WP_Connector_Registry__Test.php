<?php

class WP_Connector_Registry__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "WP_Connector_Registry not exists on WP $wp_ver" );
		}

		$registry = new WP_Connector_Registry();

		$connector = $registry->register( 'unitest-connector', [
			'name'           => 'Unitest Connector',
			'description'    => 'Connector for isolated runtime tests.',
			'logo_url'       => 'https://example.com/logo.svg',
			'type'           => 'spam_filtering',
			'authentication' => [
				'method'          => 'api_key',
				'credentials_url' => 'https://example.com/keys',
				'constant_name'   => 'UNITEST_CONNECTOR_API_KEY',
				'env_var_name'    => 'UNITEST_CONNECTOR_API_KEY',
			],
			'plugin'         => [
				'file' => 'unitest/unitest.php',
			],
		] );

		$this->assertSame( 'Unitest Connector', $connector['name'] );
		$this->assertSame( 'Connector for isolated runtime tests.', $connector['description'] );
		$this->assertSame( 'https://example.com/logo.svg', $connector['logo_url'] );
		$this->assertSame( 'spam_filtering', $connector['type'] );
		$this->assertSame( 'api_key', $connector['authentication']['method'] );
		$this->assertSame( 'https://example.com/keys', $connector['authentication']['credentials_url'] );
		$this->assertSame( 'connectors_spam_filtering_unitest_connector_api_key', $connector['authentication']['setting_name'] );
		$this->assertSame( 'UNITEST_CONNECTOR_API_KEY', $connector['authentication']['constant_name'] );
		$this->assertSame( 'UNITEST_CONNECTOR_API_KEY', $connector['authentication']['env_var_name'] );
		$this->assertSame( 'unitest/unitest.php', $connector['plugin']['file'] );
		$this->assertSame( '__return_true', $connector['plugin']['is_active'] );

		$this->assertTrue( $registry->is_registered( 'unitest-connector' ) );
		$this->assertSame( $connector, $registry->get_registered( 'unitest-connector' ) );
		$this->assertSame( [ 'unitest-connector' => $connector ], $registry->get_all_registered() );
		$this->assertSame( $connector, $registry->unregister( 'unitest-connector' ) );
		$this->assertFalse( $registry->is_registered( 'unitest-connector' ) );
		$this->assertSame( [], $registry->get_all_registered() );
	}

	public function test__registers_none_authentication_connector() {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "WP_Connector_Registry not exists on WP $wp_ver" );
		}

		$registry = new WP_Connector_Registry();

		$connector = $registry->register( 'unitest-none', [
			'name'           => 'Unitest None',
			'type'           => 'content_helper',
			'authentication' => [
				'method' => 'none',
			],
			'plugin'         => [
				'is_active' => static fn() => false,
			],
		] );

		$this->assertSame( 'none', $connector['authentication']['method'] );
		$this->assertArrayNotHasKey( 'setting_name', $connector['authentication'] );
		$this->assertFalse( $connector['plugin']['is_active']() );
	}
}
