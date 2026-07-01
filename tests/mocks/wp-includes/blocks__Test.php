<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class blocks_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_dynamic_block_names() {
		$registry = WP_Block_Type_Registry::get_instance();
		$registry->register( 'unitest/dynamic', [ 'render_callback' => static fn() => '' ] );

		$this->assertContains( 'unitest/dynamic', get_dynamic_block_names() );

		$registry->unregister( 'unitest/dynamic' );
	}

	public function test__get_dynamic_block_names_wp_mock_handler() {
		\WP_Mock::userFunction( 'get_dynamic_block_names', [ 'return' => [ 'unitest/mocked-dynamic' ] ] );

		$this->assertSame( [ 'unitest/mocked-dynamic' ], get_dynamic_block_names() );
	}

	public function test__get_hooked_blocks() {
		$registry = WP_Block_Type_Registry::get_instance();
		$registry->register( 'unitest/hooked', [ 'block_hooks' => [ 'core/paragraph' => 'after' ] ] );

		$this->assertContains( 'unitest/hooked', get_hooked_blocks()['core/paragraph']['after'] );

		$registry->unregister( 'unitest/hooked' );
	}

	public function test__get_hooked_blocks_wp_mock_handler() {
		$hooked_blocks = [ 'core/paragraph' => [ 'after' => [ 'unitest/mocked-hooked' ] ] ];
		\WP_Mock::userFunction( 'get_hooked_blocks', [ 'return' => $hooked_blocks ] );

		$this->assertSame( $hooked_blocks, get_hooked_blocks() );
	}
}
