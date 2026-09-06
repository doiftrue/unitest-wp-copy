<?php

class WP_REST_Server__Runtime__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_filter']         = [];
		$GLOBALS['wp_actions']        = [];
		$GLOBALS['wp_filters']        = [];
		$GLOBALS['wp_current_filter'] = [];
		$GLOBALS['wp_rest_server']    = null;
	}

	protected function tearDown(): void {
		unset( $GLOBALS['wp_rest_server'] );
		parent::tearDown();
	}

	public function test__public_methods() {
		$server = new WP_REST_Server();

		$this->assertInstanceOf( \Unitest_WP_Copy\WP_REST_Server__Runtime::class, $server );
		$this->assertArrayHasKey( '/', $server->get_routes() );
		$this->assertArrayHasKey( '/batch/v1', $server->get_routes() );

		$request = new WP_REST_Request( 'GET', '/' );
		$request['context'] = 'view';
		$response = $server->get_index( $request );
		$data = $response->get_data();

		$this->assertSame( 'Unitest WP Copy', $data['name'] );
		$this->assertArrayHasKey( 'routes', $data );
		$this->assertArrayNotHasKey( 'site_logo', $data );
		$this->assertArrayNotHasKey( 'site_icon', $data );
		$this->assertArrayNotHasKey( 'image_sizes', $data );
		if ( version_compare( $GLOBALS['wp_version'], '6.7', '>=' ) ) {
			$this->assertSame( 0, $data['page_for_posts'] );
			$this->assertSame( 0, $data['page_on_front'] );
			$this->assertSame( 'posts', $data['show_on_front'] );
		} else {
			$this->assertArrayNotHasKey( 'page_for_posts', $data );
			$this->assertArrayNotHasKey( 'page_on_front', $data );
			$this->assertArrayNotHasKey( 'show_on_front', $data );
		}

		$embedded_index_request = new WP_REST_Request( 'GET', '/' );
		$embedded_index_request['context'] = 'view';
		$embedded_index_request['_fields'] = 'name';
		$embedded_index_request['_embed']  = true;
		$embedded_index = $server->get_index( $embedded_index_request );
		$this->assertArrayHasKey( 'help', $embedded_index->get_links() );

		$auth_error = new WP_Error( 'auth_failed', 'Authentication failed.' );
		add_filter( 'rest_authentication_errors', static fn() => $auth_error );
		$this->assertSame( $auth_error, $server->check_authentication() );

		$server->register_route(
			'demo/v1',
			'/demo/v1/related/(?P<id>\d+)',
			[
				[
					'methods'             => 'GET, POST',
					'callback'            => static fn( WP_REST_Request $request ) => [ 'id' => (int) $request['id'] ],
					'permission_callback' => '__return_true',
					'allow_batch'         => [ 'v1' => true ],
				],
			]
		);
		$GLOBALS['wp_rest_server'] = $server;

		$namespace_request = new WP_REST_Request( 'GET', '/demo/v1' );
		$namespace_request['namespace'] = 'demo/v1';
		$namespace_request['context']   = 'view';
		$namespace_response = $server->get_namespace_index( $namespace_request );
		$this->assertArrayHasKey( '/demo/v1/related/(?P<id>\d+)', $namespace_response->get_data()['routes'] );

		$linked_response = new WP_REST_Response( [ 'id' => 1 ] );
		$linked_response->add_link(
			'related',
			rest_url( '/demo/v1/related/2' ),
			[ 'embeddable' => true ]
		);
		$linked_data = $server->response_to_data( $linked_response, true );

		$this->assertSame( 2, $linked_data['_embedded']['related'][0]['id'] );

		$batch_request = new WP_REST_Request( 'POST', '/batch/v1' );
		$batch_request->set_body_params( [
			'validation' => 'normal',
			'requests'   => [
				[
					'method' => 'POST',
					'path'   => '/demo/v1/related/3',
				],
			],
		] );
		$batch_response = $server->serve_batch_request_v1( $batch_request );

		$this->assertSame( 207, $batch_response->get_status() );
		$this->assertSame( 3, $batch_response->get_data()['responses'][0]['body']['id'] );

		Closure::bind(
			static function ( $runtime ) {
				$runtime->set_status( 202 );
			},
			null,
			WP_REST_Server::class
		)( $server );
		$this->assertSame( 202, $server->sent_status );

		$server->send_headers( [ 'X-Test' => "one \n two" ] );
		$this->assertSame( 'one two', $server->sent_headers['X-Test'] );
		$server->remove_header( 'X-Test' );
		$this->assertArrayNotHasKey( 'X-Test', $server->sent_headers );
		$this->assertSame( '', WP_REST_Server::get_raw_data() );
	}

	public function test__serve_request_is_not_supported() {
		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( 'use rest_do_request()' );

		( new WP_REST_Server() )->serve_request();
	}

}
