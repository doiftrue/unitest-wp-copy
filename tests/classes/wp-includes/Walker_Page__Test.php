<?php

class Walker_Page__Test extends \PHPUnit\Framework\TestCase {

	public function test__partial_independence() {
		$walker = new Walker_Page();
		$out = '';

		$walker->start_lvl( $out, 1, [ 'item_spacing' => 'preserve' ] );
		$walker->end_lvl( $out, 1, [ 'item_spacing' => 'preserve' ] );
		$walker->end_el( $out, (object) [], 0, [ 'item_spacing' => 'preserve' ] );

		$this->assertNotEmpty( $out );
	}

	public function test__not_independent_start_el_dependency() {
		$walker = new Walker_Page();
		$out = '';
		$page = (object) [
			'ID'          => 10,
			'post_title'  => 'Title',
			'post_parent' => 0,
			'post_date'   => '2024-01-01 00:00:00',
			'post_modified' => '2024-01-01 00:00:00',
		];

		$this->expectException( Error::class );
		$walker->start_el( $out, $page, 0, [], 1 );
	}
}

