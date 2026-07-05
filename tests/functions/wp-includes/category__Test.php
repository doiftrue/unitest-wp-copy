<?php

class category__Test extends \PHPUnit\Framework\TestCase {
	public function test__sanitize_category() {
		$result = sanitize_category( [ 'term_id' => 1, 'name' => 'Name' ], 'raw' );
		$this->assertSame( 1, $result['term_id'] );
	}

	public function test__sanitize_category_field() {
		$this->assertSame( 'Name', sanitize_category_field( 'name', 'Name', 1, 'raw' ) );
	}

	public function test___make_cat_compat() {
		$category = (object) [ 'term_id' => 1, 'count' => 2, 'description' => '', 'name' => 'Name', 'slug' => 'name', 'parent' => 0 ];
		_make_cat_compat( $category );
		$this->assertSame( 1, $category->cat_ID );
	}
}
