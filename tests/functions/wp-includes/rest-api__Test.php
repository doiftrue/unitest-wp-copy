<?php

class RestApiRequestStub {

	private array $attributes;

	public function __construct( array $attributes ) {
		$this->attributes = $attributes;
	}

	public function get_attributes(): array {
		return $this->attributes;
	}

}

class RestApiServerStub extends \Unitest_WP_Copy\WP_REST_Server__Runtime {}

class rest_api__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
		$GLOBALS['wp_rest_additional_fields'] = [];
		$GLOBALS['wp_rest_server'] = null;

		add_filter( 'rest_pre_dispatch', 'rest_handle_options_request', 10, 3 );
	}

	protected function tearDown(): void {
		unset( $GLOBALS['wp_rest_server'] );
		$GLOBALS['wp_rest_additional_fields'] = [];

		parent::tearDown();
	}

	public function test__register_rest_route() {
		$server = new WP_REST_Server();
		$GLOBALS['wp_rest_server'] = $server;
		do_action( 'rest_api_init', $server );

		$this->assertTrue(
			register_rest_route(
				'demo/v1',
				'/items',
				[
					'methods'             => 'GET',
					'callback'            => '__return_true',
					'permission_callback' => '__return_true',
				]
			)
		);
		$this->assertArrayHasKey( '/demo/v1/items', $server->get_routes() );
	}

	public function test__rest_do_request() {
		$server = new WP_REST_Server();
		$GLOBALS['wp_rest_server'] = $server;
		$server->register_route(
			'demo/v1',
			'/demo/v1/items/(?P<id>\d+)',
			[
				[
					'methods'             => 'GET',
					'callback'            => static function ( WP_REST_Request $request ) {
						$response = new WP_REST_Response( [ 'id' => $request['id'], 'hidden' => 'removed' ], 201 );
						$response->header( 'X-Test', 'yes' );
						$response->add_link( 'item', rest_url( '/demo/v1/items/' . $request['id'] ) );
						return $response;
					},
					'permission_callback' => '__return_true',
					'args'                => [
						'id' => [ 'type' => 'integer' ],
					],
				],
			]
		);

		$request = new WP_REST_Request( 'GET', '/demo/v1/items/42' );
		$request['_fields'] = 'id';
		$response = rest_do_request( $request );

		$this->assertSame( 201, $response->get_status() );
		$this->assertSame( [ 'id' => 42, 'hidden' => 'removed' ], $response->get_data() );
		$this->assertSame( 'yes', $response->get_headers()['X-Test'] );
		$this->assertSame( 'https://wp.test/wp-json/demo/v1/items/42', $response->get_links()['item'][0]['href'] );

		$invalid = rest_do_request( new WP_REST_Request( 'GET', '/demo/v1/items/not-an-integer' ) );
		$this->assertSame( 404, $invalid->get_status() );
		$this->assertSame( 'rest_no_route', $invalid->get_data()['code'] );

		$server->register_route(
			'demo/v1',
			'/demo/v1/validate/(?P<id>[^/]+)',
			[
				[
					'methods'             => 'GET',
					'callback'            => '__return_true',
					'permission_callback' => '__return_true',
					'args'                => [
						'id' => [
							'type'              => 'integer',
							'validate_callback' => 'rest_validate_request_arg',
						],
					],
				],
			]
		);
		$invalid_param = rest_do_request( new WP_REST_Request( 'GET', '/demo/v1/validate/nope' ) );
		$this->assertSame( 400, $invalid_param->get_status() );
		$this->assertSame( 'rest_invalid_param', $invalid_param->get_data()['code'] );

		$server->register_route(
			'demo/v1',
			'/demo/v1/forbidden',
			[
				[
					'methods'             => 'GET',
					'callback'            => '__return_true',
					'permission_callback' => '__return_false',
				],
			]
		);
		$forbidden = rest_do_request( new WP_REST_Request( 'GET', '/demo/v1/forbidden' ) );
		$this->assertSame( 401, $forbidden->get_status() );
		$this->assertSame( 'rest_forbidden', $forbidden->get_data()['code'] );
	}

	public function test__rest_get_server() {
		$server = rest_get_server();

		$this->assertInstanceOf( \Unitest_WP_Copy\WP_REST_Server__Runtime::class, $server );
		$this->assertSame( $server, rest_get_server() );

		$GLOBALS['wp_rest_server'] = null;
		$initialized = null;

		add_action(
			'rest_api_init',
			static function ( $server ) use ( &$initialized ) {
				$initialized = $server;
			}
		);
		add_filter( 'wp_rest_server_class', static fn() => RestApiServerStub::class );

		$filtered_server = rest_get_server();

		$this->assertInstanceOf( RestApiServerStub::class, $filtered_server );
		$this->assertSame( $filtered_server, $initialized );
		$this->assertSame( $filtered_server, rest_get_server() );
	}

	public function test__rest_ensure_request() {
		$request = rest_ensure_request( '/demo/v1/items' );

		$this->assertInstanceOf( WP_REST_Request::class, $request );
		$this->assertSame( '/demo/v1/items', $request->get_route() );
	}

	public function test__rest_ensure_response() {
		$response = rest_ensure_response( [ 'ok' => true ] );

		$this->assertInstanceOf( WP_REST_Response::class, $response );
		$this->assertSame( [ 'ok' => true ], $response->get_data() );
	}

	public function test__rest_handle_options_request() {
		$server = new WP_REST_Server();
		$server->register_route(
			'demo/v1',
			'/demo/v1/items',
			[
				[
					'methods'             => 'GET',
					'callback'            => '__return_true',
					'permission_callback' => '__return_true',
				],
			]
		);
		$request = new WP_REST_Request( 'OPTIONS', '/demo/v1/items' );

		$response = rest_handle_options_request( null, $server, $request );

		$this->assertInstanceOf( WP_REST_Response::class, $response );
		$this->assertSame( '/demo/v1/items', $response->get_matched_route() );
		$this->assertContains( 'GET', $response->get_data()['methods'] );
	}

	public function test__rest_send_allow_header() {
		$server = new WP_REST_Server();
		$server->register_route(
			'demo/v1',
			'/demo/v1/items',
			[
				[
					'methods'             => 'GET, POST',
					'callback'            => '__return_true',
					'permission_callback' => '__return_true',
				],
			]
		);
		$request = new WP_REST_Request( 'GET', '/demo/v1/items' );
		$response = new WP_REST_Response();
		$response->set_matched_route( '/demo/v1/items' );

		rest_send_allow_header( $response, $server, $request );

		$this->assertSame( 'GET, POST', $response->get_headers()['Allow'] );
	}

	public function test__rest_filter_response_fields() {
		$request = new WP_REST_Request( 'GET', '/demo/v1/items' );
		$request['_fields'] = 'id,nested.name';
		$response = new WP_REST_Response(
			[
				'id'     => 1,
				'title'  => 'Hidden',
				'nested' => [ 'name' => 'Visible', 'secret' => 'Hidden' ],
			]
		);

		rest_filter_response_fields( $response, new WP_REST_Server(), $request );

		$this->assertSame( [ 'id' => 1, 'nested' => [ 'name' => 'Visible' ] ], $response->get_data() );
	}

	public function test__rest_authorization_required_code() {
		$this->assertSame( 401, rest_authorization_required_code() );
	}

	public function test__rest_get_endpoint_args_for_schema() {
		$args = rest_get_endpoint_args_for_schema(
			[
				'type'       => 'object',
				'properties' => [
					'id'    => [ 'type' => 'integer', 'readonly' => true ],
					'title' => [ 'type' => 'string', 'required' => true, 'default' => 'Draft' ],
				],
			]
		);

		$this->assertArrayNotHasKey( 'id', $args );
		$this->assertTrue( $args['title']['required'] );
		$this->assertSame( 'Draft', $args['title']['default'] );
	}

	public function test__rest_convert_error_to_response() {
		$response = rest_convert_error_to_response(
			new WP_Error( 'demo_error', 'Broken', [ 'status' => 422, 'detail' => 'value' ] )
		);

		$this->assertSame( 422, $response->get_status() );
		$this->assertSame( 'demo_error', $response->get_data()['code'] );
		$this->assertSame( 'value', $response->get_data()['data']['detail'] );
	}

	public function test__register_rest_field() {
		register_rest_field( 'post', 'rating', [ 'schema' => [ 'type' => 'integer' ] ] );

		$this->assertArrayHasKey( 'post', $GLOBALS['wp_rest_additional_fields'] );
		$this->assertArrayHasKey( 'rating', $GLOBALS['wp_rest_additional_fields']['post'] );
		$this->assertSame( 'integer', $GLOBALS['wp_rest_additional_fields']['post']['rating']['schema']['type'] );
	}

	public function test__rest_get_url_prefix() {
		$this->assertSame( 'wp-json', rest_get_url_prefix() );
	}

	public function test__rest_url() {
		$this->assertSame( 'https://wp.test/wp-json/wp/v2/posts', rest_url( '/wp/v2/posts' ) );
	}

	public function test___rest_array_intersect_key_recursive() {
		$result = _rest_array_intersect_key_recursive(
			[ 'a' => 1, 'b' => [ 'x' => 2, 'y' => 3 ], 'c' => 4 ],
			[ 'b' => [ 'y' => true ], 'c' => true ]
		);

		$this->assertSame( [ 'b' => [ 'y' => 3 ], 'c' => 4 ], $result );
	}

	public function test__rest_is_field_included() {
		$fields = [ 'title.rendered', 'content', 'meta.foo' ];

		$this->assertTrue( rest_is_field_included( 'title', $fields ) );
		$this->assertTrue( rest_is_field_included( 'meta.foo', $fields ) );
		$this->assertFalse( rest_is_field_included( 'author', $fields ) );
	}

	public function test__rest_get_avatar_sizes() {
		$this->assertSame( [ 24, 48, 96 ], rest_get_avatar_sizes() );
	}

	public function test__rest_parse_date() {
		$this->assertIsInt( rest_parse_date( '2025-01-02T03:04:05Z' ) );
		$this->assertFalse( rest_parse_date( 'not-a-date' ) );
	}

	public function test__rest_parse_hex_color() {
		$this->assertSame( '#abcdef', rest_parse_hex_color( '#abcdef' ) );
		$this->assertFalse( rest_parse_hex_color( 'abcdef' ) );
	}

	public function test__rest_get_date_with_gmt() {
		$result = rest_get_date_with_gmt( '2025-01-02T03:04:05Z', true );

		$this->assertIsArray( $result );
		$this->assertCount( 2, $result );
		$this->assertSame( '2025-01-02 03:04:05', $result[0] );
		$this->assertSame( '2025-01-02 03:04:05', $result[1] );
	}

	public function test__rest_validate_request_arg() {
		$request = new RestApiRequestStub( [
			'args' => [
				'id' => [ 'type' => 'integer' ],
			],
		] );

		$this->assertTrue( rest_validate_request_arg( '12', $request, 'id' ) );
	}

	public function test__rest_sanitize_request_arg() {
		$request = new RestApiRequestStub( [
			'args' => [
				'id' => [ 'type' => 'integer' ],
			],
		] );

		$this->assertSame( 12, rest_sanitize_request_arg( '12', $request, 'id' ) );
	}

	public function test__rest_parse_request_arg() {
		$request = new RestApiRequestStub( [
			'args' => [
				'id' => [ 'type' => 'integer' ],
			],
		] );

		$this->assertSame( 15, rest_parse_request_arg( '15', $request, 'id' ) );
	}

	public function test__rest_handle_deprecated_function() {
		$this->assertNull( rest_handle_deprecated_function( 'old_func', 'new_func', '1.0.0' ) );
	}

	public function test__rest_handle_deprecated_argument() {
		$this->assertNull( rest_handle_deprecated_argument( 'func', 'message', '1.0.0' ) );
	}

	public function test__rest_handle_doing_it_wrong() {
		$this->assertNull( rest_handle_doing_it_wrong( 'func', 'message', '1.0.0' ) );
	}

	public function test__rest_is_ip_address() {
		$this->assertSame( '127.0.0.1', rest_is_ip_address( '127.0.0.1' ) );
		$this->assertSame( '2001:db8::1', rest_is_ip_address( '2001:db8::1' ) );
		$this->assertFalse( rest_is_ip_address( 'invalid-ip' ) );
	}

	public function test__rest_sanitize_boolean() {
		$this->assertFalse( rest_sanitize_boolean( 'false' ) );
		$this->assertTrue( rest_sanitize_boolean( '1' ) );
	}

	public function test__rest_is_boolean() {
		$this->assertTrue( rest_is_boolean( true ) );
		$this->assertTrue( rest_is_boolean( '0' ) );
		$this->assertFalse( rest_is_boolean( 'nope' ) );
	}

	public function test__rest_is_integer() {
		$this->assertTrue( rest_is_integer( '10' ) );
		$this->assertFalse( rest_is_integer( '10.5' ) );
	}

	public function test__rest_is_array() {
		$this->assertTrue( rest_is_array( [ 1, 2 ] ) );
		$this->assertTrue( rest_is_array( '1,2,3' ) );
		$this->assertFalse( rest_is_array( [ 'a' => 1 ] ) );
	}

	public function test__rest_sanitize_array() {
		$this->assertSame( [ 'a', 'b' ], rest_sanitize_array( 'a,b' ) );
		$this->assertSame( [ 'x', 'y' ], rest_sanitize_array( [ 3 => 'x', 9 => 'y' ] ) );
		$this->assertSame( [], rest_sanitize_array( null ) );
	}

	public function test__rest_is_object() {
		$this->assertTrue( rest_is_object( '' ) );
		$this->assertTrue( rest_is_object( (object) [ 'x' => 1 ] ) );
		$this->assertTrue( rest_is_object( [ 'x' => 1 ] ) );
		$this->assertFalse( rest_is_object( 10 ) );
	}

	public function test__rest_sanitize_object() {
		$this->assertSame( [], rest_sanitize_object( '' ) );
		$this->assertSame( [ 'x' => 1 ], rest_sanitize_object( (object) [ 'x' => 1 ] ) );
		$this->assertSame( [], rest_sanitize_object( 10 ) );
	}

	public function test__rest_get_best_type_for_value() {
		$this->assertSame( 'string', rest_get_best_type_for_value( '', [ 'array', 'string' ] ) );
		$this->assertSame( 'integer', rest_get_best_type_for_value( '10', [ 'boolean', 'integer' ] ) );
	}

	public function test__rest_handle_multi_type_schema() {
		$this->assertSame(
			'integer',
			rest_handle_multi_type_schema( 10, [ 'type' => [ 'string', 'integer' ] ], 'id' )
		);
	}

	public function test__rest_validate_array_contains_unique_items() {
		$this->assertTrue( rest_validate_array_contains_unique_items( [ 1, 2, 3 ] ) );
		$this->assertFalse( rest_validate_array_contains_unique_items( [ 1, 2, 1 ] ) );
	}

	public function test__rest_stabilize_value() {
		$this->assertSame(
			[ 'a' => [ 'a' => 2, 'b' => 1 ], 'b' => 3 ],
			rest_stabilize_value( [ 'b' => 3, 'a' => [ 'b' => 1, 'a' => 2 ] ] )
		);
	}

	public function test__rest_validate_json_schema_pattern() {
		$this->assertTrue( rest_validate_json_schema_pattern( '^foo', 'foobar' ) );
		$this->assertFalse( rest_validate_json_schema_pattern( '^foo', 'barfoo' ) );
	}

	public function test__rest_find_matching_pattern_property_schema() {
		$args = [
			'patternProperties' => [
				'^meta_' => [ 'type' => 'string' ],
			],
		];

		$this->assertSame( [ 'type' => 'string' ], rest_find_matching_pattern_property_schema( 'meta_title', $args ) );
		$this->assertNull( rest_find_matching_pattern_property_schema( 'title', $args ) );
	}

	public function test__rest_format_combining_operation_error() {
		$error = rest_format_combining_operation_error(
			'field',
			[
				'index'        => 2,
				'error_object' => new WP_Error( 'rest_invalid_type', 'Wrong type' ),
				'schema'       => [ 'title' => 'MyType' ],
			]
		);

		$this->assertInstanceOf( WP_Error::class, $error );
		$this->assertSame( 'rest_no_matching_schema', $error->get_error_code() );
		$this->assertSame( 2, $error->get_error_data()['position'] );
	}

	public function test__rest_get_combining_operation_error() {
		$error = rest_get_combining_operation_error(
			[ 'x' => 1 ],
			'field',
			[
				[
					'index'        => 0,
					'error_object' => new WP_Error( 'rest_invalid_type', 'Wrong type' ),
					'schema'       => [ 'title' => 'Obj', 'type' => 'object' ],
				],
			]
		);

		$this->assertInstanceOf( WP_Error::class, $error );
		$this->assertSame( 'rest_no_matching_schema', $error->get_error_code() );
	}

	public function test__rest_find_any_matching_schema() {
		$result = rest_find_any_matching_schema(
			12,
			[
				'anyOf' => [
					[ 'type' => 'string' ],
					[ 'type' => 'integer' ],
				],
			],
			'field'
		);

		$this->assertIsArray( $result );
		$this->assertSame( 'integer', $result['type'] );
	}

	public function test__rest_find_one_matching_schema() {
		$result = rest_find_one_matching_schema(
			12,
			[
				'oneOf' => [
					[ 'type' => 'string' ],
					[ 'type' => 'integer' ],
				],
			],
			'field'
		);

		$this->assertIsArray( $result );
		$this->assertSame( 'integer', $result['type'] );
	}

	public function test__rest_are_values_equal() {
		$this->assertTrue( rest_are_values_equal( [ 'a' => 1, 'b' => [ 'x' => 2 ] ], [ 'a' => 1, 'b' => [ 'x' => 2 ] ] ) );
		$this->assertTrue( rest_are_values_equal( 1, 1.0 ) );
		$this->assertFalse( rest_are_values_equal( [ 'a' => 1 ], [ 'a' => 2 ] ) );
	}

	public function test__rest_validate_enum() {
		$this->assertTrue( rest_validate_enum( '2', [ 'type' => 'integer', 'enum' => [ 1, 2 ] ], 'field' ) );
	}

	public function test__rest_get_allowed_schema_keywords() {
		$keywords = rest_get_allowed_schema_keywords();

		$this->assertContains( 'type', $keywords );
		$this->assertContains( 'oneOf', $keywords );
	}

	public function test__rest_validate_value_from_schema() {
		$this->assertTrue(
			rest_validate_value_from_schema(
				'127.0.0.1',
				[ 'type' => 'string', 'format' => 'ip' ],
				'ip'
			)
		);
	}

	public function test__rest_validate_null_value_from_schema() {
		$this->assertTrue( rest_validate_null_value_from_schema( null, 'field' ) );
	}

	public function test__rest_validate_boolean_value_from_schema() {
		$this->assertTrue( rest_validate_boolean_value_from_schema( '1', 'field' ) );
	}

	public function test__rest_validate_object_value_from_schema() {
		$this->assertTrue(
			rest_validate_object_value_from_schema(
				[ 'id' => 10 ],
				[
					'type'       => 'object',
					'properties' => [
						'id' => [ 'type' => 'integer' ],
					],
				],
				'obj'
			)
		);
	}

	public function test__rest_validate_array_value_from_schema() {
		$this->assertTrue(
			rest_validate_array_value_from_schema(
				[ 1, 2, 3 ],
				[
					'type'  => 'array',
					'items' => [ 'type' => 'integer' ],
				],
				'items'
			)
		);
	}

	public function test__rest_validate_number_value_from_schema() {
		$this->assertTrue(
			rest_validate_number_value_from_schema(
				6,
				[
					'type'       => 'number',
					'multipleOf' => 2,
					'minimum'    => 2,
					'maximum'    => 10,
				],
				'num'
			)
		);
	}

	public function test__rest_validate_string_value_from_schema() {
		$this->assertTrue(
			rest_validate_string_value_from_schema(
				'aaa',
				[
					'type'      => 'string',
					'minLength' => 2,
					'maxLength' => 4,
					'pattern'   => '^a+$',
				],
				'str'
			)
		);
	}

	public function test__rest_validate_integer_value_from_schema() {
		$this->assertTrue(
			rest_validate_integer_value_from_schema(
				10,
				[
					'type'    => 'integer',
					'minimum' => 1,
					'maximum' => 20,
				],
				'int'
			)
		);
	}

	public function test__rest_sanitize_value_from_schema() {
		$this->assertSame( 7, rest_sanitize_value_from_schema( '7', [ 'type' => 'integer' ], 'int' ) );
	}

	public function test__rest_parse_embed_param() {
		$this->assertTrue( rest_parse_embed_param( '1' ) );
		$this->assertSame( [ 'author', 'replies' ], rest_parse_embed_param( 'author,replies' ) );
	}

	public function test__rest_filter_response_by_context() {
		$schema = [
			'type'       => 'object',
			'properties' => [
				'visible' => [
					'type'    => 'string',
					'context' => [ 'view', 'edit' ],
				],
				'hidden'  => [
					'type'    => 'string',
					'context' => [ 'edit' ],
				],
			],
		];
		$data = [ 'visible' => 'ok', 'hidden' => 'secret' ];

		$result = rest_filter_response_by_context( $data, $schema, 'view' );

		$this->assertArrayHasKey( 'visible', $result );
		$this->assertArrayNotHasKey( 'hidden', $result );
	}

	public function test__rest_default_additional_properties_to_false() {
		$schema = [
			'type' => 'object',
			'properties' => [
				'meta' => [
					'type' => 'object',
					'properties' => [
						'items' => [
							'type' => 'array',
							'items' => [ 'type' => 'object' ],
						],
					],
				],
			],
		];

		$result = rest_default_additional_properties_to_false( $schema );

		$this->assertFalse( $result['additionalProperties'] );
		$this->assertFalse( $result['properties']['meta']['additionalProperties'] );
		$this->assertFalse( $result['properties']['meta']['properties']['items']['items']['additionalProperties'] );
	}

}
