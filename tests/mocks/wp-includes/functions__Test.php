<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class functions_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__wp_generate_uuid4(): void {
		$this->assertTrue( wp_is_uuid( wp_generate_uuid4(), 4 ) );
	}

	public function test__wp_generate_uuid4_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'wp_generate_uuid4', [ 'return' => 'mocked-uuid' ] );
		$this->assertSame( 'mocked-uuid', wp_generate_uuid4() );
	}

	public function test__wp_unique_id(): void {
		$first = wp_unique_id( 'mockable-unique-' );
		$this->assertNotSame( $first, wp_unique_id( 'mockable-unique-' ) );
	}

	public function test__wp_unique_id_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'wp_unique_id', [ 'return' => 'fixed-id' ] );
		$this->assertSame( 'fixed-id', wp_unique_id( 'prefix-' ) );
	}

	public function test__wp_unique_prefixed_id(): void {
		$first = wp_unique_prefixed_id( 'mockable-prefixed-' );
		$this->assertNotSame( $first, wp_unique_prefixed_id( 'mockable-prefixed-' ) );
	}

	public function test__wp_unique_prefixed_id_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'wp_unique_prefixed_id', [ 'return' => 'fixed-prefixed-id' ] );
		$this->assertSame( 'fixed-prefixed-id', wp_unique_prefixed_id( 'prefix-' ) );
	}
}
