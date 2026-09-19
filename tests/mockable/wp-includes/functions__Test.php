<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class functions__mockable__Test extends \PHPUnit\Framework\TestCase {

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

	public function test__wp_generate_uuid4__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_generate_uuid4', [ 'return' => 'mocked-uuid' ] );
		$this->assertSame( 'mocked-uuid', wp_generate_uuid4() );
	}

	public function test__wp_unique_id(): void {
		$first = wp_unique_id( 'mockable-unique-' );
		$this->assertNotSame( $first, wp_unique_id( 'mockable-unique-' ) );
	}

	public function test__wp_unique_id__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_unique_id', [ 'return' => 'fixed-id' ] );
		$this->assertSame( 'fixed-id', wp_unique_id( 'prefix-' ) );
	}

	public function test__wp_unique_prefixed_id(): void {
		$first = wp_unique_prefixed_id( 'mockable-prefixed-' );
		$this->assertNotSame( $first, wp_unique_prefixed_id( 'mockable-prefixed-' ) );
	}

	public function test__wp_unique_prefixed_id__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_unique_prefixed_id', [ 'return' => 'fixed-prefixed-id' ] );
		$this->assertSame( 'fixed-prefixed-id', wp_unique_prefixed_id( 'prefix-' ) );
	}

	public function test__current_datetime__mockable_handler(): void {
		$datetime = new DateTimeImmutable( '2026-01-01 00:00:00', new DateTimeZone( 'UTC' ) );
		\WP_Mock::userFunction( 'current_datetime', [ 'return' => $datetime ] );
		$this->assertSame( $datetime, current_datetime() );
	}

	public function test__runtime_helpers__mockable_handlers(): void {
		\WP_Mock::userFunction( 'wp_timezone_override_offset', [ 'return' => 4.5 ] );
		\WP_Mock::userFunction( 'wp_is_serving_rest_request', [ 'return' => true ] );
		\WP_Mock::userFunction( 'is_php_version_compatible', [ 'return' => false ] );

		$this->assertSame( 4.5, wp_timezone_override_offset() );
		$this->assertTrue( wp_is_serving_rest_request() );
		$this->assertFalse( is_php_version_compatible( '1.0' ) );
	}
}
