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

class rest_api_Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
		$GLOBALS['wp_rest_additional_fields'] = [];
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
