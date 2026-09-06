<?php

class WP_REST_Request__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$request = new WP_REST_Request(
			'POST',
			'/demo/v1/items/7',
			[
				'args' => [
					'id' => [
						'required' => true,
						'type'     => 'integer',
					],
				],
			]
		);
		$request->set_url_params( [ 'id' => '7' ] );
		$request->set_header( 'Content-Type', 'application/json' );
		$request->set_body( '{"enabled":true}' );

		$this->assertSame( 'POST', $request->get_method() );
		$this->assertSame( '/demo/v1/items/7', $request->get_route() );
		$this->assertSame( 'application/json', $request->get_header( 'content-type' ) );
		$this->assertTrue( $request->has_valid_params() );
		$this->assertTrue( $request->sanitize_params() );
		$this->assertSame( 7, $request['id'] );
		$this->assertSame( [ 'enabled' => true ], $request->get_json_params() );

		$from_url = WP_REST_Request::from_url( 'https://wp.test/wp-json/demo/v1/items/9?context=edit' );

		$this->assertInstanceOf( WP_REST_Request::class, $from_url );
		$this->assertSame( '/demo/v1/items/9', $from_url->get_route() );
		$this->assertSame( 'edit', $from_url['context'] );
	}

}
