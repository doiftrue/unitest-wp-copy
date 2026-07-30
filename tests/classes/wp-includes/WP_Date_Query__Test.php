<?php

class WP_Date_Query__Test extends \PHPUnit\Framework\TestCase {

	public function test__get_sql() {
		$query = new WP_Date_Query( [
			[
				'year'  => 2024,
				'month' => 7,
			],
		] );

		$sql = $query->get_sql();

		$this->assertStringContainsString( 'YEAR( wp_posts.post_date ) = 2024', $sql );
		$this->assertStringContainsString( 'MONTH( wp_posts.post_date ) = 7', $sql );

		$week_sql = ( new WP_Date_Query( [ [ 'week' => 1 ] ] ) )->get_sql();
		$this->assertStringContainsString( 'WEEK( wp_posts.post_date, 1 ) = 1', $week_sql );

		$week_alias_sql = ( new WP_Date_Query( [ [ 'w' => 2 ] ] ) )->get_sql();
		$this->assertStringContainsString( 'WEEK( wp_posts.post_date, 1 ) = 2', $week_alias_sql );
	}
}
