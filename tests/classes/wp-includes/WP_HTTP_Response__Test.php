<?php

class WP_HTTP_Response__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$response = new WP_HTTP_Response( [ 'ok' => true ], 201, [ 'X-Test' => 'one' ] );
		$response->header( 'X-Test', 'two', false );

		$this->assertSame( [ 'ok' => true ], $response->get_data() );
		$this->assertSame( 201, $response->get_status() );
		$this->assertSame( 'one, two', $response->get_headers()['X-Test'] );
		$this->assertSame( [ 'ok' => true ], $response->jsonSerialize() );
	}
}
