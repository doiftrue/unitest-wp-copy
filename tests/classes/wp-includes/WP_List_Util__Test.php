<?php

use PHPUnit\Framework\TestCase;

class WP_List_Util__Test extends TestCase {

	public function test__public_methods() {
		$list = new WP_List_Util( [
			[ 'id' => 2, 'type' => 'a' ],
			[ 'id' => 1, 'type' => 'b' ],
		] );

		$this->assertCount( 2, $list->get_input() );
		$this->assertCount( 2, $list->get_output() );
		$this->assertCount( 1, $list->filter( [ 'type' => 'a' ] ) );
		$this->assertSame( [ 2 ], array_values( $list->pluck( 'id' ) ) );

		$list2 = new WP_List_Util( [
			[ 'id' => 2, 'type' => 'a' ],
			[ 'id' => 1, 'type' => 'b' ],
		] );
		$sorted = $list2->sort( [ 'id' => 'ASC' ] );
		$this->assertSame( [ 1, 2 ], array_column( $sorted, 'id' ) );
	}
}

