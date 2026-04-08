<?php

class Walker_Category__Test extends \PHPUnit\Framework\TestCase {

	public function test__partial_independence() {
		$walker = new Walker_Category();
		$out = '';

		$walker->start_lvl( $out, 1, [ 'style' => 'list' ] );
		$walker->end_lvl( $out, 1, [ 'style' => 'list' ] );
		$walker->end_el( $out, (object) [], 0, [ 'style' => 'list' ] );

		$this->assertNotEmpty( $out );
	}

	public function test__not_independent_start_el_dependency() {
		$walker = new Walker_Category();
		$out = '';
		$cat = (object) [
			'name'        => 'Cat',
			'description' => '',
			'taxonomy'    => 'category',
			'term_id'     => 10,
			'count'       => 0,
		];
		$args = [
			'style' => 'list',
			'use_desc_for_title' => false,
			'feed_image' => '',
			'feed' => '',
			'feed_type' => '',
			'title' => '',
			'show_count' => false,
			'current_category' => [],
		];

		$this->expectException( Error::class );
		$walker->start_el( $out, $cat, 0, $args, 0 );
	}
}

