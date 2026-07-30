<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class connectors__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		if ( class_exists( 'WP_Connector_Registry' ) ) {
			$this->set_registry( null );
		}
		\WP_Mock::tearDown();
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

	public function test__wp_is_connector_registered(): void {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "wp_is_connector_registered() not exists on WP $wp_ver" );
		}

		$this->assertFalse( wp_is_connector_registered( 'unitest-connector' ) );
		$this->set_registry( $this->create_registry() );
		$this->assertTrue( wp_is_connector_registered( 'unitest-connector' ) );
	}

	public function test__wp_is_connector_registered__mockable_handler(): void {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "wp_is_connector_registered() not exists on WP $wp_ver" );
		}

		\WP_Mock::userFunction( 'wp_is_connector_registered', [ 'return' => true ] );
		$this->assertTrue( wp_is_connector_registered( 'mocked-connector' ) );
	}

	public function test__wp_get_connector(): void {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "wp_get_connector() not exists on WP $wp_ver" );
		}

		$this->assertNull( wp_get_connector( 'unitest-connector' ) );
		$this->set_registry( $this->create_registry() );
		$this->assertSame( 'Unitest Connector', wp_get_connector( 'unitest-connector' )['name'] );
	}

	public function test__wp_get_connector__mockable_handler(): void {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "wp_get_connector() not exists on WP $wp_ver" );
		}

		$connector = [
			'name'           => 'Mocked Connector',
			'type'           => 'content_helper',
			'authentication' => [ 'method' => 'none' ],
		];
		\WP_Mock::userFunction( 'wp_get_connector', [ 'return' => $connector ] );

		$this->assertSame( $connector, wp_get_connector( 'mocked-connector' ) );
	}

	public function test__wp_get_connectors(): void {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "wp_get_connectors() not exists on WP $wp_ver" );
		}

		$this->assertSame( [], wp_get_connectors() );
		$this->set_registry( $this->create_registry() );
		$this->assertArrayHasKey( 'unitest-connector', wp_get_connectors() );
	}

	public function test__wp_get_connectors__mockable_handler(): void {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "wp_get_connectors() not exists on WP $wp_ver" );
		}

		$connectors = [
			'mocked-connector' => [
				'name'           => 'Mocked Connector',
				'type'           => 'content_helper',
				'authentication' => [ 'method' => 'none' ],
			],
		];
		\WP_Mock::userFunction( 'wp_get_connectors', [ 'return' => $connectors ] );

		$this->assertSame( $connectors, wp_get_connectors() );
	}
}
