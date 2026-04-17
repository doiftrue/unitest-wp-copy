<?php

class taxonomy__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_taxonomies'] = [];
		$GLOBALS['wp_post_types'] = [];
	}

	public function test__taxonomy_exists() {
		$this->assertFalse( taxonomy_exists( 'genre' ) );

		$GLOBALS['wp_taxonomies']['genre'] = (object) [ 'name' => 'genre' ];

		$this->assertTrue( taxonomy_exists( 'genre' ) );
	}

	public function test__get_taxonomy() {
		$this->assertFalse( get_taxonomy( 'genre' ) );

		$taxonomy = (object) [ 'name' => 'genre', 'hierarchical' => true ];
		$GLOBALS['wp_taxonomies']['genre'] = $taxonomy;

		$this->assertSame( $taxonomy, get_taxonomy( 'genre' ) );
	}

	public function test__get_taxonomies() {
		$GLOBALS['wp_taxonomies'] = [
			'category' => (object) [ 'name' => 'category', 'hierarchical' => true ],
			'post_tag' => (object) [ 'name' => 'post_tag', 'hierarchical' => false ],
		];

		$this->assertSame( [ 'category' => 'category' ], get_taxonomies( [ 'hierarchical' => true ] ) );

		$objects = get_taxonomies( [ 'hierarchical' => false ], 'objects' );
		$this->assertArrayHasKey( 'post_tag', $objects );
		$this->assertSame( 'post_tag', $objects['post_tag']->name );
	}

	public function test__is_taxonomy_hierarchical() {
		$this->assertFalse( is_taxonomy_hierarchical( 'unknown' ) );

		$GLOBALS['wp_taxonomies']['category'] = (object) [ 'name' => 'category', 'hierarchical' => true ];
		$GLOBALS['wp_taxonomies']['post_tag'] = (object) [ 'name' => 'post_tag', 'hierarchical' => false ];

		$this->assertTrue( is_taxonomy_hierarchical( 'category' ) );
		$this->assertFalse( is_taxonomy_hierarchical( 'post_tag' ) );
	}

	public function test__register_taxonomy_for_object_type() {
		$GLOBALS['wp_taxonomies']['genre'] = (object) [ 'name' => 'genre', 'object_type' => [ 'book', '' ] ];
		$GLOBALS['wp_post_types']['post']  = (object) [ 'name' => 'post' ];

		$this->assertTrue( register_taxonomy_for_object_type( 'genre', 'post' ) );
		$this->assertSame( [ 'book', 'post' ], array_values( $GLOBALS['wp_taxonomies']['genre']->object_type ) );

		$this->assertFalse( register_taxonomy_for_object_type( 'unknown', 'post' ) );
		$this->assertFalse( register_taxonomy_for_object_type( 'genre', 'missing_type' ) );
	}

	public function test__unregister_taxonomy_for_object_type() {
		$GLOBALS['wp_taxonomies']['genre'] = (object) [ 'name' => 'genre', 'object_type' => [ 'book', 'post' ] ];
		$GLOBALS['wp_post_types']['post']  = (object) [ 'name' => 'post' ];

		$this->assertTrue( unregister_taxonomy_for_object_type( 'genre', 'post' ) );
		$this->assertSame( [ 0 => 'book' ], $GLOBALS['wp_taxonomies']['genre']->object_type );

		$this->assertFalse( unregister_taxonomy_for_object_type( 'genre', 'post' ) );
		$this->assertFalse( unregister_taxonomy_for_object_type( 'unknown', 'post' ) );
	}

	public function test__sanitize_term_field() {
		$this->assertSame( 0, sanitize_term_field( 'term_id', -12, 5, 'category', 'display' ) );
		$this->assertSame( 'A &quot;quote&quot;', sanitize_term_field( 'name', 'A "quote"', 5, 'category', 'attribute' ) );
		$this->assertSame( 'A &quot;quote&quot;', sanitize_term_field( 'name', 'A "quote"', 5, 'category', 'js' ) );
	}

	public function test__sanitize_term() {
		$term = [
			'term_id'   => '7',
			'name'      => 'Name',
			'parent'    => '-1',
			'object_id' => '4',
		];

		$sanitized = sanitize_term( $term, 'category', 'raw' );

		$this->assertSame( 7, $sanitized['term_id'] );
		$this->assertSame( 0, $sanitized['parent'] );
		$this->assertSame( 4, $sanitized['object_id'] );
		$this->assertSame( 'raw', $sanitized['filter'] );
	}

	public function test__is_taxonomy_viewable() {
		$this->assertFalse( is_taxonomy_viewable( 'unknown' ) );

		$GLOBALS['wp_taxonomies']['genre'] = (object) [ 'name' => 'genre', 'publicly_queryable' => true ];

		$this->assertTrue( is_taxonomy_viewable( 'genre' ) );
	}
}
