<?php

// Needed only for mock tests: loads 10up/wp_mock classes.
require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class taxonomy_mocks__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();

		$GLOBALS['wp_taxonomies'] = [];
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__get_taxonomies() {
		$GLOBALS['wp_taxonomies'] = [
			'category' => (object) [ 'name' => 'category', 'hierarchical' => true ],
			'post_tag' => (object) [ 'name' => 'post_tag', 'hierarchical' => false ],
		];

		$this->assertSame( [ 'category' => 'category' ], get_taxonomies( [ 'hierarchical' => true ] ) );
	}

	public function test__get_taxonomies_wp_mock_handler() {
		\WP_Mock::userFunction( 'get_taxonomies', [ 'return' => [ 'mocked' ] ] );
		$this->assertSame( [ 'mocked' ], get_taxonomies( [ 'hierarchical' => true ] ) );
	}

	public function test__get_taxonomy() {
		$this->assertFalse( get_taxonomy( 'genre' ) );

		$taxonomy = (object) [ 'name' => 'genre', 'hierarchical' => true ];
		$GLOBALS['wp_taxonomies']['genre'] = $taxonomy;

		$this->assertSame( $taxonomy, get_taxonomy( 'genre' ) );
	}

	public function test__get_taxonomy_wp_mock_handler() {
		$mocked = (object) [ 'name' => 'mocked' ];
		\WP_Mock::userFunction( 'get_taxonomy', [ 'return' => $mocked ] );

		$this->assertSame( $mocked, get_taxonomy( 'genre' ) );
	}

	public function test__taxonomy_exists() {
		$this->assertFalse( taxonomy_exists( 'genre' ) );

		$GLOBALS['wp_taxonomies']['genre'] = (object) [ 'name' => 'genre' ];

		$this->assertTrue( taxonomy_exists( 'genre' ) );
	}

	public function test__taxonomy_exists_wp_mock_handler() {
		\WP_Mock::userFunction( 'taxonomy_exists', [ 'return' => true ] );
		$this->assertTrue( taxonomy_exists( 'unknown' ) );
	}

	public function test__is_taxonomy_hierarchical() {
		$this->assertFalse( is_taxonomy_hierarchical( 'unknown' ) );

		$GLOBALS['wp_taxonomies']['category'] = (object) [ 'name' => 'category', 'hierarchical' => true ];
		$GLOBALS['wp_taxonomies']['post_tag'] = (object) [ 'name' => 'post_tag', 'hierarchical' => false ];

		$this->assertTrue( is_taxonomy_hierarchical( 'category' ) );
		$this->assertFalse( is_taxonomy_hierarchical( 'post_tag' ) );
	}

	public function test__is_taxonomy_hierarchical_wp_mock_handler() {
		\WP_Mock::userFunction( 'is_taxonomy_hierarchical', [ 'return' => true ] );
		$this->assertTrue( is_taxonomy_hierarchical( 'unknown' ) );
	}

	public function test__is_taxonomy_viewable() {
		$this->assertFalse( is_taxonomy_viewable( 'unknown' ) );

		$GLOBALS['wp_taxonomies']['genre'] = (object) [ 'name' => 'genre', 'publicly_queryable' => true ];

		$this->assertTrue( is_taxonomy_viewable( 'genre' ) );
	}

	public function test__is_taxonomy_viewable_wp_mock_handler() {
		\WP_Mock::userFunction( 'is_taxonomy_viewable', [ 'return' => true ] );
		$this->assertTrue( is_taxonomy_viewable( 'unknown' ) );
	}

}

