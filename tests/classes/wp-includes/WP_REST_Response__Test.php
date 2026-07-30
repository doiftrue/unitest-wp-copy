<?php

class WP_REST_Response__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$response = new WP_REST_Response( [ 'code' => 'broken', 'message' => 'Broken', 'data' => [] ], 400 );
		$response->add_link( 'self', 'https://wp.test/item' );
		$response->set_matched_route( '/items/(?P<id>\d+)' );

		$this->assertTrue( $response->is_error() );
		$this->assertSame( 'https://wp.test/item', $response->get_links()['self'][0]['href'] );
		$this->assertSame( '/items/(?P<id>\d+)', $response->get_matched_route() );
		$this->assertInstanceOf( WP_Error::class, $response->as_error() );
	}
}
