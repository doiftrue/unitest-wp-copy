<?php

class WP_REST_Controller__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$controller = new class() extends WP_REST_Controller {
			public function get_item_schema() {
				return [
					'title'      => 'item',
					'type'       => 'object',
					'properties' => [
						'id'     => [
							'type'     => 'integer',
							'readonly' => true,
							'context'  => [ 'view', 'edit' ],
						],
						'title'  => [
							'type'     => 'string',
							'required' => true,
							'context'  => [ 'view', 'edit' ],
						],
						'secret' => [
							'type'    => 'string',
							'context' => [ 'edit' ],
						],
					],
				];
			}
		};

		$request = new WP_REST_Request( 'GET', '/demo/v1/items/1' );
		$request['context'] = 'view';
		$request['_fields'] = 'title,secret';

		$this->assertSame( [ 'title', 'id' ], $controller->get_fields_for_response( $request ) );
		$this->assertSame(
			[ 'id' => 1, 'title' => 'Visible' ],
			$controller->filter_response_by_context(
				[ 'id' => 1, 'title' => 'Visible', 'secret' => 'Hidden' ],
				'view'
			)
		);

		$args = $controller->get_endpoint_args_for_item_schema();
		$this->assertArrayNotHasKey( 'id', $args );
		$this->assertTrue( $args['title']['required'] );
		$this->assertSame( 'Demo Item', $controller->sanitize_slug( 'Demo Item' ) );
	}

}
