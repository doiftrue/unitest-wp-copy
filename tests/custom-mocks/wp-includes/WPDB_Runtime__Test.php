<?php

class WPDB_Runtime__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$wpdb = new \Unitest_WP_Copy\WPDB_Runtime();

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
	}
}
