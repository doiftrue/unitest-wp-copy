<?php

class connectors__Test extends \PHPUnit\Framework\TestCase {

	protected function tearDown(): void {
		if ( class_exists( 'WP_Connector_Registry' ) ) {
			$this->set_registry( null );
		}
		parent::tearDown();
	}

	private function set_registry( ?WP_Connector_Registry $registry ): void {
		$set_registry = Closure::bind( static fn ( $reg ) => self::$instance = $reg, null, WP_Connector_Registry::class );
		$set_registry( $registry );
	}

	private function create_registry(): WP_Connector_Registry {
		$registry = new WP_Connector_Registry();
		$registry->register( 'unitest-connector', [
			'name'           => 'Unitest Connector',
			'type'           => 'content_helper',
			'authentication' => [ 'method' => 'none' ],
		] );
		return $registry;
	}

	public function test__wp_is_connector_registered() {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "wp_is_connector_registered() not exists on WP $wp_ver" );
		}

		$this->assertFalse( wp_is_connector_registered( 'unitest-connector' ) );

		$this->set_registry( $this->create_registry() );
		$this->assertTrue( wp_is_connector_registered( 'unitest-connector' ) );
	}

	public function test__wp_get_connector() {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "wp_get_connector() not exists on WP $wp_ver" );
		}

		$this->assertNull( wp_get_connector( 'unitest-connector' ) );

		$this->set_registry( $this->create_registry() );
		$this->assertSame( 'Unitest Connector', wp_get_connector( 'unitest-connector' )['name'] );
	}

	public function test__wp_get_connectors() {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "wp_get_connectors() not exists on WP $wp_ver" );
		}

		$this->assertSame( [], wp_get_connectors() );

		$this->set_registry( $this->create_registry() );
		$this->assertArrayHasKey( 'unitest-connector', wp_get_connectors() );
	}

	public function test___wp_connectors_mask_api_key() {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "_wp_connectors_mask_api_key() not exists on WP $wp_ver" );
		}

		$this->assertSame( 'key', _wp_connectors_mask_api_key( 'key' ) );
		$this->assertSame( str_repeat( "\u{2022}", 6 ) . '7890', _wp_connectors_mask_api_key( '1234567890' ) );
		$this->assertSame( str_repeat( "\u{2022}", 16 ) . '7890', _wp_connectors_mask_api_key( str_repeat( 'x', 20 ) . '7890' ) );
	}
}
