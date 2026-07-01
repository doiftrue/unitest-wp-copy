<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class post_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
		$GLOBALS['wp_post_statuses']       = [];
		$GLOBALS['_wp_post_type_features'] = [];
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_post_status_object(): void {
		$status = (object) [ 'name' => 'publish' ];
		$GLOBALS['wp_post_statuses']['publish'] = $status;
		$this->assertSame( $status, get_post_status_object( 'publish' ) );
	}

	public function test__get_post_status_object_wp_mock_handler(): void {
		$status = (object) [ 'name' => 'mocked' ];
		\WP_Mock::userFunction( 'get_post_status_object', [ 'return' => $status ] );
		$this->assertSame( $status, get_post_status_object( 'publish' ) );
	}

	public function test__get_post_stati(): void {
		$GLOBALS['wp_post_statuses']['publish'] = (object) [ 'name' => 'publish', 'public' => true ];
		$this->assertSame( [ 'publish' => 'publish' ], get_post_stati( [ 'public' => true ] ) );
	}

	public function test__get_post_stati_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'get_post_stati', [ 'return' => [ 'mocked' ] ] );
		$this->assertSame( [ 'mocked' ], get_post_stati() );
	}

	public function test__get_all_post_type_supports(): void {
		$GLOBALS['_wp_post_type_features']['book'] = [ 'editor' => true ];
		$this->assertSame( [ 'editor' => true ], get_all_post_type_supports( 'book' ) );
	}

	public function test__get_all_post_type_supports_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'get_all_post_type_supports', [ 'return' => [ 'mocked' => true ] ] );
		$this->assertSame( [ 'mocked' => true ], get_all_post_type_supports( 'book' ) );
	}

	public function test__post_type_supports(): void {
		$GLOBALS['_wp_post_type_features']['book'] = [ 'editor' => true ];
		$this->assertTrue( post_type_supports( 'book', 'editor' ) );
	}

	public function test__post_type_supports_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'post_type_supports', [ 'return' => true ] );
		$this->assertTrue( post_type_supports( 'book', 'mocked' ) );
	}

	public function test__get_post_types_by_support(): void {
		$GLOBALS['_wp_post_type_features'] = [
			'book' => [ 'editor' => true ],
			'note' => [ 'thumbnail' => true ],
		];
		$this->assertSame( [ 'book' ], get_post_types_by_support( 'editor' ) );
	}

	public function test__get_post_types_by_support_wp_mock_handler(): void {
		\WP_Mock::userFunction( 'get_post_types_by_support', [ 'return' => [ 'mocked' ] ] );
		$this->assertSame( [ 'mocked' ], get_post_types_by_support( 'editor' ) );
	}
}
