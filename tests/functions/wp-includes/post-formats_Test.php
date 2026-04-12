<?php

class post_formats_Test extends \PHPUnit\Framework\TestCase {

	public function test__get_post_format_strings() {
		$formats = get_post_format_strings();

		$this->assertArrayHasKey( 'standard', $formats );
		$this->assertArrayHasKey( 'audio', $formats );
	}

	public function test__get_post_format_slugs() {
		$slugs = get_post_format_slugs();

		$this->assertArrayHasKey( 'standard', $slugs );
		$this->assertSame( 'standard', $slugs['standard'] );
	}

	public function test__get_post_format_string() {
		$this->assertSame( 'Standard', get_post_format_string( '' ) );
		$this->assertSame( 'Quote', get_post_format_string( 'quote' ) );
		$this->assertSame( '', get_post_format_string( 'unknown-slug' ) );
	}

	public function test___post_format_get_term() {
		$term = (object) [ 'slug' => 'post-format-audio', 'name' => 'Unused' ];

		$result = _post_format_get_term( $term );

		$this->assertSame( 'Audio', $result->name );
	}

	public function test___post_format_get_terms() {
		$terms = [ 'post-format-video', 'post-format-status' ];
		$args  = [ 'fields' => 'names' ];

		$names = _post_format_get_terms( $terms, [ 'post_format' ], $args );
		$this->assertSame( 'Video', $names[0] );
		$this->assertSame( 'Status', $names[1] );

		$term_objects = [
			(object) [ 'taxonomy' => 'post_format', 'slug' => 'post-format-quote', 'name' => 'Unused' ],
			(object) [ 'taxonomy' => 'category', 'slug' => 'news', 'name' => 'News' ],
		];
		$objects = _post_format_get_terms( $term_objects, [ 'post_format' ], [] );

		$this->assertSame( 'Quote', $objects[0]->name );
		$this->assertSame( 'News', $objects[1]->name );
	}

	public function test___post_format_wp_get_object_terms() {
		$terms = [
			(object) [ 'taxonomy' => 'post_format', 'slug' => 'post-format-image', 'name' => 'Unused' ],
			(object) [ 'taxonomy' => 'post_tag', 'slug' => 'tag-1', 'name' => 'Tag 1' ],
		];

		$result = _post_format_wp_get_object_terms( $terms );

		$this->assertSame( 'Image', $result[0]->name );
		$this->assertSame( 'Tag 1', $result[1]->name );
	}

}
