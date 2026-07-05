<?php

class WP_Meta_Query__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$query = new WP_Meta_Query( [
			'relation' => 'OR',
			'color'    => [ 'key' => 'color', 'value' => 'blue' ],
			'size'     => [ 'key' => 'size', 'value' => [ 10, 20 ], 'compare' => 'BETWEEN', 'type' => 'NUMERIC' ],
		] );

		$this->assertSame( 'OR', $query->relation );
		$this->assertTrue( $query->has_or_relation() );
		$this->assertSame( 'SIGNED', $query->get_cast_for_type( 'NUMERIC' ) );
		$this->assertSame( 'CHAR', $query->get_cast_for_type( 'invalid' ) );

		$sql = $query->get_sql( 'post', 'wp_posts', 'ID' );
		$this->assertStringContainsString( 'INNER JOIN wp_postmeta', $sql['join'] );
		$this->assertStringContainsString( "wp_postmeta.meta_key = 'color'", $sql['where'] );
		$this->assertStringContainsString( 'CAST(wp_postmeta.meta_value AS SIGNED) BETWEEN', $sql['where'] );
		$this->assertArrayHasKey( 'color', $query->get_clauses() );
		$this->assertArrayHasKey( 'size', $query->get_clauses() );

		$query->parse_query_vars( [ 'meta_key' => 'rating', 'meta_value' => '5' ] );
		$this->assertSame( 'rating', $query->queries[0]['key'] );
		$this->assertSame( '5', $query->queries[0]['value'] );
	}
}
