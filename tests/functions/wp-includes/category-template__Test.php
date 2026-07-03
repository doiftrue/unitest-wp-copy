<?php

class category_template__Test extends \PHPUnit\Framework\TestCase {

	public function test__default_topic_count_scale() {
		if( wp_version_compare( '< 6.9.0' ) ){
			$this->assertSame( 0.0, default_topic_count_scale( 0 ) );
		} else {
			$this->assertSame( 0, default_topic_count_scale( 0 ) );
			$this->assertSame( 30, default_topic_count_scale( 1 ) );
			$this->assertSame( 48, default_topic_count_scale( 2 ) );
			$this->assertSame( 100, default_topic_count_scale( 9 ) );
			$this->assertSame( 200, default_topic_count_scale( 99 ) );
			$this->assertSame( 300, default_topic_count_scale( 999 ) );
		}
	}

	public function test___wp_object_name_sort_cb() {
		$a = (object) [ 'name' => 'Apple' ];
		$b = (object) [ 'name' => 'Banana' ];
		$c = (object) [ 'name' => 'apple' ];

		$this->assertLessThan( 0, _wp_object_name_sort_cb( $a, $b ) );
		$this->assertGreaterThan( 0, _wp_object_name_sort_cb( $b, $a ) );
		// Case insensitive
		$this->assertSame( 0, _wp_object_name_sort_cb( $a, $c ) );
	}

	public function test___wp_object_count_sort_cb() {
		$a = (object) [ 'count' => 5 ];
		$b = (object) [ 'count' => 10 ];
		$c = (object) [ 'count' => 5 ];

		$this->assertLessThan( 0, _wp_object_count_sort_cb( $a, $b ) );
		$this->assertGreaterThan( 0, _wp_object_count_sort_cb( $b, $a ) );
		$this->assertSame( 0, _wp_object_count_sort_cb( $a, $c ) );
	}

	public function test__wp_generate_tag_cloud() {
		// Empty tags returns empty
		$this->assertSame( '', wp_generate_tag_cloud( [] ) );
		$this->assertSame( [], wp_generate_tag_cloud( [], [ 'format' => 'array' ] ) );

		// Build tag objects
		$tags = [
			(object) [ 'name' => 'PHP', 'slug' => 'php', 'link' => 'https://example.com/tag/php', 'count' => 10, 'id' => 1 ],
			(object) [ 'name' => 'JavaScript', 'slug' => 'javascript', 'link' => 'https://example.com/tag/js', 'count' => 5, 'id' => 2 ],
			(object) [ 'name' => 'Python', 'slug' => 'python', 'link' => 'https://example.com/tag/python', 'count' => 20, 'id' => 3 ],
		];

		// Default format (flat)
		$result = wp_generate_tag_cloud( $tags, [ 'filter' => 0 ] );
		$this->assertIsString( $result );
		$this->assertStringContainsString( 'PHP', $result );
		$this->assertStringContainsString( 'JavaScript', $result );
		$this->assertStringContainsString( 'Python', $result );
		$this->assertStringContainsString( 'tag-cloud-link', $result );

		// Array format
		$result = wp_generate_tag_cloud( $tags, [ 'format' => 'array', 'filter' => 0 ] );
		$this->assertIsArray( $result );
		$this->assertCount( 3, $result );

		// List format
		$result = wp_generate_tag_cloud( $tags, [ 'format' => 'list', 'filter' => 0 ] );
		$this->assertStringContainsString( "<ul class='wp-tag-cloud'", $result );
		$this->assertStringContainsString( '<li>', $result );

		// Number limit
		$result = wp_generate_tag_cloud( $tags, [ 'format' => 'array', 'number' => 2, 'filter' => 0 ] );
		$this->assertCount( 2, $result );

		// Order DESC
		$result = wp_generate_tag_cloud( $tags, [ 'format' => 'array', 'orderby' => 'count', 'order' => 'DESC', 'filter' => 0 ] );
		// First item should be highest count (Python, 20)
		$this->assertStringContainsString( 'Python', $result[0] );

		// show_count option
		$result = wp_generate_tag_cloud( $tags, [ 'format' => 'array', 'show_count' => 1, 'filter' => 0 ] );
		$this->assertStringContainsString( 'tag-link-count', $result[0] );

		// Font sizing — check that font-size styles are present
		$result = wp_generate_tag_cloud( $tags, [ 'format' => 'array', 'filter' => 0 ] );
		$this->assertStringContainsString( 'font-size:', $result[0] );
	}
}
