<?php

class wpdb__Runtime__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$wpdb = new \Unitest_WP_Copy\wpdb__Runtime();

		$query = $wpdb->prepare(
			'SELECT * FROM %i WHERE title = %s AND count = %d AND ratio = %f',
			'my_table',
			"O'Reilly",
			7,
			1.5
		);

		$this->assertSame(
			"SELECT * FROM `my_table` WHERE title = 'O\\'Reilly' AND count = 7 AND ratio = 1.500000",
			$wpdb->remove_placeholder_escape( $query )
		);
		$this->assertSame( 'a\\%\\_b', $wpdb->esc_like( 'a%_b' ) );
		$this->assertSame( '100%', $wpdb->remove_placeholder_escape( $wpdb->add_placeholder_escape( '100%' ) ) );
		$this->assertSame( 'wp_posts', $wpdb->posts );
		$this->assertSame( 'wp_comments', $wpdb->comments );
		$this->assertSame( 'wp_users', $wpdb->users );
		$this->assertSame( 'wp_blogs', $wpdb->blogs );
		$this->assertSame( 'wp_', $wpdb->base_prefix );
		$this->assertSame( 'wp_', $wpdb->prefix );
		$this->assertSame( 1, $wpdb->blogid );
		$this->assertSame( 1, $wpdb->siteid );

		$this->assertSame( 1, $wpdb->set_blog_id( 5, 2 ) );
		$this->assertSame( 'wp_', $wpdb->base_prefix );
		$this->assertSame( 'wp_5_', $wpdb->prefix );
		$this->assertSame( 5, $wpdb->blogid );
		$this->assertSame( 2, $wpdb->siteid );
		$this->assertSame( 'wp_5_posts', $wpdb->posts );
		$this->assertSame( 'wp_users', $wpdb->users );
	}
}
