<?php

// ------------------auto-generated---------------------

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'register_rest_field' ) ) :
	function register_rest_field( $object_type, $attribute, $args = array() ) {
		global $wp_rest_additional_fields;
	
		$defaults = array(
			'get_callback'    => null,
			'update_callback' => null,
			'schema'          => null,
		);
	
		$args = wp_parse_args( $args, $defaults );
	
		$object_types = (array) $object_type;
	
		foreach ( $object_types as $object_type ) {
			$wp_rest_additional_fields[ $object_type ][ $attribute ] = $args;
		}
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_get_url_prefix' ) ) :
	function rest_get_url_prefix() {
		/**
		 * Filters the REST URL prefix.
		 *
		 * @since 4.4.0
		 *
		 * @param string $prefix URL prefix. Default 'wp-json'.
		 */
		return apply_filters( 'rest_url_prefix', 'wp-json' );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( '_rest_array_intersect_key_recursive' ) ) :
	function _rest_array_intersect_key_recursive( $array1, $array2 ) {
		$array1 = array_intersect_key( $array1, $array2 );
		foreach ( $array1 as $key => $value ) {
			if ( is_array( $value ) && is_array( $array2[ $key ] ) ) {
				$array1[ $key ] = _rest_array_intersect_key_recursive( $value, $array2[ $key ] );
			}
		}
		return $array1;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_is_field_included' ) ) :
	function rest_is_field_included( $field, $fields ) {
		if ( in_array( $field, $fields, true ) ) {
			return true;
		}
	
		foreach ( $fields as $accepted_field ) {
			/*
			 * Check to see if $field is the parent of any item in $fields.
			 * A field "parent" should be accepted if "parent.child" is accepted.
			 */
			if ( str_starts_with( $accepted_field, "$field." ) ) {
				return true;
			}
			/*
			 * Conversely, if "parent" is accepted, all "parent.child" fields
			 * should also be accepted.
			 */
			if ( str_starts_with( $field, "$accepted_field." ) ) {
				return true;
			}
		}
	
		return false;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_get_avatar_sizes' ) ) :
	function rest_get_avatar_sizes() {
		/**
		 * Filters the REST avatar sizes.
		 *
		 * Use this filter to adjust the array of sizes returned by the
		 * `rest_get_avatar_sizes` function.
		 *
		 * @since 4.4.0
		 *
		 * @param int[] $sizes An array of int values that are the pixel sizes for avatars.
		 *                     Default `[ 24, 48, 96 ]`.
		 */
		return apply_filters( 'rest_avatar_sizes', array( 24, 48, 96 ) );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_parse_date' ) ) :
	function rest_parse_date( $date, $force_utc = false ) {
		if ( $force_utc ) {
			$date = preg_replace( '/[+-]\d+:?\d+$/', '+00:00', $date );
		}
	
		$regex = '#^\d{4}-\d{2}-\d{2}[Tt ]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}(?::\d{2})?)?$#';
	
		if ( ! preg_match( $regex, $date, $matches ) ) {
			return false;
		}
	
		return strtotime( $date );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_parse_hex_color' ) ) :
	function rest_parse_hex_color( $color ) {
		$regex = '|^#([A-Fa-f0-9]{3}){1,2}$|';
		if ( ! preg_match( $regex, $color, $matches ) ) {
			return false;
		}
	
		return $color;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_get_date_with_gmt' ) ) :
	function rest_get_date_with_gmt( $date, $is_utc = false ) {
		/*
		 * Whether or not the original date actually has a timezone string
		 * changes the way we need to do timezone conversion.
		 * Store this info before parsing the date, and use it later.
		 */
		$has_timezone = preg_match( '#(Z|[+-]\d{2}(:\d{2})?)$#', $date );
	
		$date = rest_parse_date( $date );
	
		if ( false === $date ) {
			return null;
		}
	
		/*
		 * At this point $date could either be a local date (if we were passed
		 * a *local* date without a timezone offset) or a UTC date (otherwise).
		 * Timezone conversion needs to be handled differently between these two cases.
		 */
		if ( ! $is_utc && ! $has_timezone ) {
			$local = gmdate( 'Y-m-d H:i:s', $date );
			$utc   = get_gmt_from_date( $local );
		} else {
			$utc   = gmdate( 'Y-m-d H:i:s', $date );
			$local = get_date_from_gmt( $utc );
		}
	
		return array( $local, $utc );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_request_arg' ) ) :
	function rest_validate_request_arg( $value, $request, $param ) {
		$attributes = $request->get_attributes();
		if ( ! isset( $attributes['args'][ $param ] ) || ! is_array( $attributes['args'][ $param ] ) ) {
			return true;
		}
		$args = $attributes['args'][ $param ];
	
		return rest_validate_value_from_schema( $value, $args, $param );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_sanitize_request_arg' ) ) :
	function rest_sanitize_request_arg( $value, $request, $param ) {
		$attributes = $request->get_attributes();
		if ( ! isset( $attributes['args'][ $param ] ) || ! is_array( $attributes['args'][ $param ] ) ) {
			return $value;
		}
		$args = $attributes['args'][ $param ];
	
		return rest_sanitize_value_from_schema( $value, $args, $param );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_parse_request_arg' ) ) :
	function rest_parse_request_arg( $value, $request, $param ) {
		$is_valid = rest_validate_request_arg( $value, $request, $param );
	
		if ( is_wp_error( $is_valid ) ) {
			return $is_valid;
		}
	
		$value = rest_sanitize_request_arg( $value, $request, $param );
	
		return $value;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_is_ip_address' ) ) :
	function rest_is_ip_address( $ip ) {
		$ipv4_pattern = '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';
	
		if ( ! preg_match( $ipv4_pattern, $ip ) && ! WP_Http__is_ip_address( $ip ) ) {
			return false;
		}
	
		return $ip;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_sanitize_boolean' ) ) :
	function rest_sanitize_boolean( $value ) {
		// String values are translated to `true`; make sure 'false' is false.
		if ( is_string( $value ) ) {
			$value = strtolower( $value );
			if ( in_array( $value, array( 'false', '0' ), true ) ) {
				$value = false;
			}
		}
	
		// Everything else will map nicely to boolean.
		return (bool) $value;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_is_boolean' ) ) :
	function rest_is_boolean( $maybe_bool ) {
		if ( is_bool( $maybe_bool ) ) {
			return true;
		}
	
		if ( is_string( $maybe_bool ) ) {
			$maybe_bool = strtolower( $maybe_bool );
	
			$valid_boolean_values = array(
				'false',
				'true',
				'0',
				'1',
			);
	
			return in_array( $maybe_bool, $valid_boolean_values, true );
		}
	
		if ( is_int( $maybe_bool ) ) {
			return in_array( $maybe_bool, array( 0, 1 ), true );
		}
	
		return false;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_is_integer' ) ) :
	function rest_is_integer( $maybe_integer ) {
		return is_numeric( $maybe_integer ) && round( (float) $maybe_integer ) === (float) $maybe_integer;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_is_array' ) ) :
	function rest_is_array( $maybe_array ) {
		if ( is_scalar( $maybe_array ) ) {
			$maybe_array = wp_parse_list( $maybe_array );
		}
	
		return wp_is_numeric_array( $maybe_array );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_sanitize_array' ) ) :
	function rest_sanitize_array( $maybe_array ) {
		if ( is_scalar( $maybe_array ) ) {
			return wp_parse_list( $maybe_array );
		}
	
		if ( ! is_array( $maybe_array ) ) {
			return array();
		}
	
		// Normalize to numeric array so nothing unexpected is in the keys.
		return array_values( $maybe_array );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_is_object' ) ) :
	function rest_is_object( $maybe_object ) {
		if ( '' === $maybe_object ) {
			return true;
		}
	
		if ( $maybe_object instanceof stdClass ) {
			return true;
		}
	
		if ( $maybe_object instanceof JsonSerializable ) {
			$maybe_object = $maybe_object->jsonSerialize();
		}
	
		return is_array( $maybe_object );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_sanitize_object' ) ) :
	function rest_sanitize_object( $maybe_object ) {
		if ( '' === $maybe_object ) {
			return array();
		}
	
		if ( $maybe_object instanceof stdClass ) {
			return (array) $maybe_object;
		}
	
		if ( $maybe_object instanceof JsonSerializable ) {
			$maybe_object = $maybe_object->jsonSerialize();
		}
	
		if ( ! is_array( $maybe_object ) ) {
			return array();
		}
	
		return $maybe_object;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_get_best_type_for_value' ) ) :
	function rest_get_best_type_for_value( $value, $types ) {
		static $checks = array(
			'array'   => 'rest_is_array',
			'object'  => 'rest_is_object',
			'integer' => 'rest_is_integer',
			'number'  => 'is_numeric',
			'boolean' => 'rest_is_boolean',
			'string'  => 'is_string',
			'null'    => 'is_null',
		);
	
		/*
		 * Both arrays and objects allow empty strings to be converted to their types.
		 * But the best answer for this type is a string.
		 */
		if ( '' === $value && in_array( 'string', $types, true ) ) {
			return 'string';
		}
	
		foreach ( $types as $type ) {
			if ( isset( $checks[ $type ] ) && $checks[ $type ]( $value ) ) {
				return $type;
			}
		}
	
		return '';
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_handle_multi_type_schema' ) ) :
	function rest_handle_multi_type_schema( $value, $args, $param = '' ) {
		$allowed_types = array( 'array', 'object', 'string', 'number', 'integer', 'boolean', 'null' );
		$invalid_types = array_diff( $args['type'], $allowed_types );
	
		if ( $invalid_types ) {
			_doing_it_wrong(
				__FUNCTION__,
				/* translators: 1: Parameter, 2: List of allowed types. */
				wp_sprintf( __( 'The "type" schema keyword for %1$s can only contain the built-in types: %2$l.' ), $param, $allowed_types ),
				'5.5.0'
			);
		}
	
		$best_type = rest_get_best_type_for_value( $value, $args['type'] );
	
		if ( ! $best_type ) {
			if ( ! $invalid_types ) {
				return '';
			}
	
			// Backward compatibility for previous behavior which allowed the value if there was an invalid type used.
			$best_type = reset( $invalid_types );
		}
	
		return $best_type;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_array_contains_unique_items' ) ) :
	function rest_validate_array_contains_unique_items( $input_array ) {
		$seen = array();
	
		foreach ( $input_array as $item ) {
			$stabilized = rest_stabilize_value( $item );
			$key        = serialize( $stabilized );
	
			if ( ! isset( $seen[ $key ] ) ) {
				$seen[ $key ] = true;
	
				continue;
			}
	
			return false;
		}
	
		return true;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_stabilize_value' ) ) :
	function rest_stabilize_value( $value ) {
		if ( is_scalar( $value ) || is_null( $value ) ) {
			return $value;
		}
	
		if ( is_object( $value ) ) {
			_doing_it_wrong( __FUNCTION__, __( 'Cannot stabilize objects. Convert the object to an array first.' ), '5.5.0' );
	
			return $value;
		}
	
		ksort( $value );
	
		foreach ( $value as $k => $v ) {
			$value[ $k ] = rest_stabilize_value( $v );
		}
	
		return $value;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_json_schema_pattern' ) ) :
	function rest_validate_json_schema_pattern( $pattern, $value ) {
		$escaped_pattern = str_replace( '#', '\\#', $pattern );
	
		return 1 === preg_match( '#' . $escaped_pattern . '#u', $value );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_find_matching_pattern_property_schema' ) ) :
	function rest_find_matching_pattern_property_schema( $property, $args ) {
		if ( isset( $args['patternProperties'] ) ) {
			foreach ( $args['patternProperties'] as $pattern => $child_schema ) {
				if ( rest_validate_json_schema_pattern( $pattern, $property ) ) {
					return $child_schema;
				}
			}
		}
	
		return null;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_format_combining_operation_error' ) ) :
	function rest_format_combining_operation_error( $param, $error ) {
		$position = $error['index'];
		$reason   = $error['error_object']->get_error_message();
	
		if ( isset( $error['schema']['title'] ) ) {
			$title = $error['schema']['title'];
	
			return new WP_Error(
				'rest_no_matching_schema',
				/* translators: 1: Parameter, 2: Schema title, 3: Reason. */
				sprintf( __( '%1$s is not a valid %2$s. Reason: %3$s' ), $param, $title, $reason ),
				array( 'position' => $position )
			);
		}
	
		return new WP_Error(
			'rest_no_matching_schema',
			/* translators: 1: Parameter, 2: Reason. */
			sprintf( __( '%1$s does not match the expected format. Reason: %2$s' ), $param, $reason ),
			array( 'position' => $position )
		);
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_get_combining_operation_error' ) ) :
	function rest_get_combining_operation_error( $value, $param, $errors ) {
		// If there is only one error, simply return it.
		if ( 1 === count( $errors ) ) {
			return rest_format_combining_operation_error( $param, $errors[0] );
		}
	
		// Filter out all errors related to type validation.
		$filtered_errors = array();
		foreach ( $errors as $error ) {
			$error_code = $error['error_object']->get_error_code();
			$error_data = $error['error_object']->get_error_data();
	
			if ( 'rest_invalid_type' !== $error_code || ( isset( $error_data['param'] ) && $param !== $error_data['param'] ) ) {
				$filtered_errors[] = $error;
			}
		}
	
		// If there is only one error left, simply return it.
		if ( 1 === count( $filtered_errors ) ) {
			return rest_format_combining_operation_error( $param, $filtered_errors[0] );
		}
	
		// If there are only errors related to object validation, try choosing the most appropriate one.
		if ( count( $filtered_errors ) > 1 && 'object' === $filtered_errors[0]['schema']['type'] ) {
			$result = null;
			$number = 0;
	
			foreach ( $filtered_errors as $error ) {
				if ( isset( $error['schema']['properties'] ) ) {
					$n = count( array_intersect_key( $error['schema']['properties'], $value ) );
					if ( $n > $number ) {
						$result = $error;
						$number = $n;
					}
				}
			}
	
			if ( null !== $result ) {
				return rest_format_combining_operation_error( $param, $result );
			}
		}
	
		// If each schema has a title, include those titles in the error message.
		$schema_titles = array();
		foreach ( $errors as $error ) {
			if ( isset( $error['schema']['title'] ) ) {
				$schema_titles[] = $error['schema']['title'];
			}
		}
	
		if ( count( $schema_titles ) === count( $errors ) ) {
			/* translators: 1: Parameter, 2: Schema titles. */
			return new WP_Error( 'rest_no_matching_schema', wp_sprintf( __( '%1$s is not a valid %2$l.' ), $param, $schema_titles ) );
		}
	
		/* translators: %s: Parameter. */
		return new WP_Error( 'rest_no_matching_schema', sprintf( __( '%s does not match any of the expected formats.' ), $param ) );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_find_any_matching_schema' ) ) :
	function rest_find_any_matching_schema( $value, $args, $param ) {
		$errors = array();
	
		foreach ( $args['anyOf'] as $index => $schema ) {
			if ( ! isset( $schema['type'] ) && isset( $args['type'] ) ) {
				$schema['type'] = $args['type'];
			}
	
			$is_valid = rest_validate_value_from_schema( $value, $schema, $param );
			if ( ! is_wp_error( $is_valid ) ) {
				return $schema;
			}
	
			$errors[] = array(
				'error_object' => $is_valid,
				'schema'       => $schema,
				'index'        => $index,
			);
		}
	
		return rest_get_combining_operation_error( $value, $param, $errors );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_find_one_matching_schema' ) ) :
	function rest_find_one_matching_schema( $value, $args, $param, $stop_after_first_match = false ) {
		$matching_schemas = array();
		$errors           = array();
	
		foreach ( $args['oneOf'] as $index => $schema ) {
			if ( ! isset( $schema['type'] ) && isset( $args['type'] ) ) {
				$schema['type'] = $args['type'];
			}
	
			$is_valid = rest_validate_value_from_schema( $value, $schema, $param );
			if ( ! is_wp_error( $is_valid ) ) {
				if ( $stop_after_first_match ) {
					return $schema;
				}
	
				$matching_schemas[] = array(
					'schema_object' => $schema,
					'index'         => $index,
				);
			} else {
				$errors[] = array(
					'error_object' => $is_valid,
					'schema'       => $schema,
					'index'        => $index,
				);
			}
		}
	
		if ( ! $matching_schemas ) {
			return rest_get_combining_operation_error( $value, $param, $errors );
		}
	
		if ( count( $matching_schemas ) > 1 ) {
			$schema_positions = array();
			$schema_titles    = array();
	
			foreach ( $matching_schemas as $schema ) {
				$schema_positions[] = $schema['index'];
	
				if ( isset( $schema['schema_object']['title'] ) ) {
					$schema_titles[] = $schema['schema_object']['title'];
				}
			}
	
			// If each schema has a title, include those titles in the error message.
			if ( count( $schema_titles ) === count( $matching_schemas ) ) {
				return new WP_Error(
					'rest_one_of_multiple_matches',
					/* translators: 1: Parameter, 2: Schema titles. */
					wp_sprintf( __( '%1$s matches %2$l, but should match only one.' ), $param, $schema_titles ),
					array( 'positions' => $schema_positions )
				);
			}
	
			return new WP_Error(
				'rest_one_of_multiple_matches',
				/* translators: %s: Parameter. */
				sprintf( __( '%s matches more than one of the expected formats.' ), $param ),
				array( 'positions' => $schema_positions )
			);
		}
	
		return $matching_schemas[0]['schema_object'];
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_are_values_equal' ) ) :
	function rest_are_values_equal( $value1, $value2 ) {
		if ( is_array( $value1 ) && is_array( $value2 ) ) {
			if ( count( $value1 ) !== count( $value2 ) ) {
				return false;
			}
	
			foreach ( $value1 as $index => $value ) {
				if ( ! array_key_exists( $index, $value2 ) || ! rest_are_values_equal( $value, $value2[ $index ] ) ) {
					return false;
				}
			}
	
			return true;
		}
	
		if ( is_int( $value1 ) && is_float( $value2 )
			|| is_float( $value1 ) && is_int( $value2 )
		) {
			return (float) $value1 === (float) $value2;
		}
	
		return $value1 === $value2;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_enum' ) ) :
	function rest_validate_enum( $value, $args, $param ) {
		$sanitized_value = rest_sanitize_value_from_schema( $value, $args, $param );
		if ( is_wp_error( $sanitized_value ) ) {
			return $sanitized_value;
		}
	
		foreach ( $args['enum'] as $enum_value ) {
			if ( rest_are_values_equal( $sanitized_value, $enum_value ) ) {
				return true;
			}
		}
	
		$encoded_enum_values = array();
		foreach ( $args['enum'] as $enum_value ) {
			$encoded_enum_values[] = is_scalar( $enum_value ) ? $enum_value : wp_json_encode( $enum_value );
		}
	
		if ( count( $encoded_enum_values ) === 1 ) {
			/* translators: 1: Parameter, 2: Valid values. */
			return new WP_Error( 'rest_not_in_enum', wp_sprintf( __( '%1$s is not %2$s.' ), $param, $encoded_enum_values[0] ) );
		}
	
		/* translators: 1: Parameter, 2: List of valid values. */
		return new WP_Error( 'rest_not_in_enum', wp_sprintf( __( '%1$s is not one of %2$l.' ), $param, $encoded_enum_values ) );
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_get_allowed_schema_keywords' ) ) :
	function rest_get_allowed_schema_keywords() {
		return array(
			'title',
			'description',
			'default',
			'type',
			'format',
			'enum',
			'items',
			'properties',
			'additionalProperties',
			'patternProperties',
			'minProperties',
			'maxProperties',
			'minimum',
			'maximum',
			'exclusiveMinimum',
			'exclusiveMaximum',
			'multipleOf',
			'minLength',
			'maxLength',
			'pattern',
			'minItems',
			'maxItems',
			'uniqueItems',
			'anyOf',
			'oneOf',
		);
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_value_from_schema' ) ) :
	function rest_validate_value_from_schema( $value, $args, $param = '' ) {
		if ( isset( $args['anyOf'] ) ) {
			$matching_schema = rest_find_any_matching_schema( $value, $args, $param );
			if ( is_wp_error( $matching_schema ) ) {
				return $matching_schema;
			}
	
			if ( ! isset( $args['type'] ) && isset( $matching_schema['type'] ) ) {
				$args['type'] = $matching_schema['type'];
			}
		}
	
		if ( isset( $args['oneOf'] ) ) {
			$matching_schema = rest_find_one_matching_schema( $value, $args, $param );
			if ( is_wp_error( $matching_schema ) ) {
				return $matching_schema;
			}
	
			if ( ! isset( $args['type'] ) && isset( $matching_schema['type'] ) ) {
				$args['type'] = $matching_schema['type'];
			}
		}
	
		$allowed_types = array( 'array', 'object', 'string', 'number', 'integer', 'boolean', 'null' );
	
		if ( ! isset( $args['type'] ) ) {
			/* translators: %s: Parameter. */
			_doing_it_wrong( __FUNCTION__, sprintf( __( 'The "type" schema keyword for %s is required.' ), $param ), '5.5.0' );
		}
	
		if ( is_array( $args['type'] ) ) {
			$best_type = rest_handle_multi_type_schema( $value, $args, $param );
	
			if ( ! $best_type ) {
				return new WP_Error(
					'rest_invalid_type',
					/* translators: 1: Parameter, 2: List of types. */
					sprintf( __( '%1$s is not of type %2$s.' ), $param, implode( ',', $args['type'] ) ),
					array( 'param' => $param )
				);
			}
	
			$args['type'] = $best_type;
		}
	
		if ( ! in_array( $args['type'], $allowed_types, true ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				/* translators: 1: Parameter, 2: The list of allowed types. */
				wp_sprintf( __( 'The "type" schema keyword for %1$s can only be one of the built-in types: %2$l.' ), $param, $allowed_types ),
				'5.5.0'
			);
		}
	
		switch ( $args['type'] ) {
			case 'null':
				$is_valid = rest_validate_null_value_from_schema( $value, $param );
				break;
			case 'boolean':
				$is_valid = rest_validate_boolean_value_from_schema( $value, $param );
				break;
			case 'object':
				$is_valid = rest_validate_object_value_from_schema( $value, $args, $param );
				break;
			case 'array':
				$is_valid = rest_validate_array_value_from_schema( $value, $args, $param );
				break;
			case 'number':
				$is_valid = rest_validate_number_value_from_schema( $value, $args, $param );
				break;
			case 'string':
				$is_valid = rest_validate_string_value_from_schema( $value, $args, $param );
				break;
			case 'integer':
				$is_valid = rest_validate_integer_value_from_schema( $value, $args, $param );
				break;
			default:
				$is_valid = true;
				break;
		}
	
		if ( is_wp_error( $is_valid ) ) {
			return $is_valid;
		}
	
		if ( ! empty( $args['enum'] ) ) {
			$enum_contains_value = rest_validate_enum( $value, $args, $param );
			if ( is_wp_error( $enum_contains_value ) ) {
				return $enum_contains_value;
			}
		}
	
		/*
		 * The "format" keyword should only be applied to strings. However, for backward compatibility,
		 * we allow the "format" keyword if the type keyword was not specified, or was set to an invalid value.
		 */
		if ( isset( $args['format'] )
			&& ( ! isset( $args['type'] ) || 'string' === $args['type'] || ! in_array( $args['type'], $allowed_types, true ) )
		) {
			switch ( $args['format'] ) {
				case 'hex-color':
					if ( ! rest_parse_hex_color( $value ) ) {
						return new WP_Error( 'rest_invalid_hex_color', __( 'Invalid hex color.' ) );
					}
					break;
	
				case 'date-time':
					if ( false === rest_parse_date( $value ) ) {
						return new WP_Error( 'rest_invalid_date', __( 'Invalid date.' ) );
					}
					break;
	
				case 'email':
					if ( ! is_email( $value ) ) {
						return new WP_Error( 'rest_invalid_email', __( 'Invalid email address.' ) );
					}
					break;
				case 'ip':
					if ( ! rest_is_ip_address( $value ) ) {
						/* translators: %s: IP address. */
						return new WP_Error( 'rest_invalid_ip', sprintf( __( '%s is not a valid IP address.' ), $param ) );
					}
					break;
				case 'uuid':
					if ( ! wp_is_uuid( $value ) ) {
						/* translators: %s: The name of a JSON field expecting a valid UUID. */
						return new WP_Error( 'rest_invalid_uuid', sprintf( __( '%s is not a valid UUID.' ), $param ) );
					}
					break;
			}
		}
	
		return true;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_null_value_from_schema' ) ) :
	function rest_validate_null_value_from_schema( $value, $param ) {
		if ( null !== $value ) {
			return new WP_Error(
				'rest_invalid_type',
				/* translators: 1: Parameter, 2: Type name. */
				sprintf( __( '%1$s is not of type %2$s.' ), $param, 'null' ),
				array( 'param' => $param )
			);
		}
	
		return true;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_boolean_value_from_schema' ) ) :
	function rest_validate_boolean_value_from_schema( $value, $param ) {
		if ( ! rest_is_boolean( $value ) ) {
			return new WP_Error(
				'rest_invalid_type',
				/* translators: 1: Parameter, 2: Type name. */
				sprintf( __( '%1$s is not of type %2$s.' ), $param, 'boolean' ),
				array( 'param' => $param )
			);
		}
	
		return true;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_object_value_from_schema' ) ) :
	function rest_validate_object_value_from_schema( $value, $args, $param ) {
		if ( ! rest_is_object( $value ) ) {
			return new WP_Error(
				'rest_invalid_type',
				/* translators: 1: Parameter, 2: Type name. */
				sprintf( __( '%1$s is not of type %2$s.' ), $param, 'object' ),
				array( 'param' => $param )
			);
		}
	
		$value = rest_sanitize_object( $value );
	
		if ( isset( $args['required'] ) && is_array( $args['required'] ) ) { // schema version 4
			foreach ( $args['required'] as $name ) {
				if ( ! array_key_exists( $name, $value ) ) {
					return new WP_Error(
						'rest_property_required',
						/* translators: 1: Property of an object, 2: Parameter. */
						sprintf( __( '%1$s is a required property of %2$s.' ), $name, $param )
					);
				}
			}
		} elseif ( isset( $args['properties'] ) ) { // schema version 3
			foreach ( $args['properties'] as $name => $property ) {
				if ( isset( $property['required'] ) && true === $property['required'] && ! array_key_exists( $name, $value ) ) {
					return new WP_Error(
						'rest_property_required',
						/* translators: 1: Property of an object, 2: Parameter. */
						sprintf( __( '%1$s is a required property of %2$s.' ), $name, $param )
					);
				}
			}
		}
	
		foreach ( $value as $property => $v ) {
			if ( isset( $args['properties'][ $property ] ) ) {
				$is_valid = rest_validate_value_from_schema( $v, $args['properties'][ $property ], $param . '[' . $property . ']' );
				if ( is_wp_error( $is_valid ) ) {
					return $is_valid;
				}
				continue;
			}
	
			$pattern_property_schema = rest_find_matching_pattern_property_schema( $property, $args );
			if ( null !== $pattern_property_schema ) {
				$is_valid = rest_validate_value_from_schema( $v, $pattern_property_schema, $param . '[' . $property . ']' );
				if ( is_wp_error( $is_valid ) ) {
					return $is_valid;
				}
				continue;
			}
	
			if ( isset( $args['additionalProperties'] ) ) {
				if ( false === $args['additionalProperties'] ) {
					return new WP_Error(
						'rest_additional_properties_forbidden',
						/* translators: %s: Property of an object. */
						sprintf( __( '%1$s is not a valid property of Object.' ), $property )
					);
				}
	
				if ( is_array( $args['additionalProperties'] ) ) {
					$is_valid = rest_validate_value_from_schema( $v, $args['additionalProperties'], $param . '[' . $property . ']' );
					if ( is_wp_error( $is_valid ) ) {
						return $is_valid;
					}
				}
			}
		}
	
		if ( isset( $args['minProperties'] ) && count( $value ) < $args['minProperties'] ) {
			return new WP_Error(
				'rest_too_few_properties',
				sprintf(
					/* translators: 1: Parameter, 2: Number. */
					_n(
						'%1$s must contain at least %2$s property.',
						'%1$s must contain at least %2$s properties.',
						$args['minProperties']
					),
					$param,
					number_format_i18n( $args['minProperties'] )
				)
			);
		}
	
		if ( isset( $args['maxProperties'] ) && count( $value ) > $args['maxProperties'] ) {
			return new WP_Error(
				'rest_too_many_properties',
				sprintf(
					/* translators: 1: Parameter, 2: Number. */
					_n(
						'%1$s must contain at most %2$s property.',
						'%1$s must contain at most %2$s properties.',
						$args['maxProperties']
					),
					$param,
					number_format_i18n( $args['maxProperties'] )
				)
			);
		}
	
		return true;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_array_value_from_schema' ) ) :
	function rest_validate_array_value_from_schema( $value, $args, $param ) {
		if ( ! rest_is_array( $value ) ) {
			return new WP_Error(
				'rest_invalid_type',
				/* translators: 1: Parameter, 2: Type name. */
				sprintf( __( '%1$s is not of type %2$s.' ), $param, 'array' ),
				array( 'param' => $param )
			);
		}
	
		$value = rest_sanitize_array( $value );
	
		if ( isset( $args['items'] ) ) {
			foreach ( $value as $index => $v ) {
				$is_valid = rest_validate_value_from_schema( $v, $args['items'], $param . '[' . $index . ']' );
				if ( is_wp_error( $is_valid ) ) {
					return $is_valid;
				}
			}
		}
	
		if ( isset( $args['minItems'] ) && count( $value ) < $args['minItems'] ) {
			return new WP_Error(
				'rest_too_few_items',
				sprintf(
					/* translators: 1: Parameter, 2: Number. */
					_n(
						'%1$s must contain at least %2$s item.',
						'%1$s must contain at least %2$s items.',
						$args['minItems']
					),
					$param,
					number_format_i18n( $args['minItems'] )
				)
			);
		}
	
		if ( isset( $args['maxItems'] ) && count( $value ) > $args['maxItems'] ) {
			return new WP_Error(
				'rest_too_many_items',
				sprintf(
					/* translators: 1: Parameter, 2: Number. */
					_n(
						'%1$s must contain at most %2$s item.',
						'%1$s must contain at most %2$s items.',
						$args['maxItems']
					),
					$param,
					number_format_i18n( $args['maxItems'] )
				)
			);
		}
	
		if ( ! empty( $args['uniqueItems'] ) && ! rest_validate_array_contains_unique_items( $value ) ) {
			/* translators: %s: Parameter. */
			return new WP_Error( 'rest_duplicate_items', sprintf( __( '%s has duplicate items.' ), $param ) );
		}
	
		return true;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_number_value_from_schema' ) ) :
	function rest_validate_number_value_from_schema( $value, $args, $param ) {
		if ( ! is_numeric( $value ) ) {
			return new WP_Error(
				'rest_invalid_type',
				/* translators: 1: Parameter, 2: Type name. */
				sprintf( __( '%1$s is not of type %2$s.' ), $param, $args['type'] ),
				array( 'param' => $param )
			);
		}
	
		if ( isset( $args['multipleOf'] ) && fmod( $value, $args['multipleOf'] ) !== 0.0 ) {
			return new WP_Error(
				'rest_invalid_multiple',
				/* translators: 1: Parameter, 2: Multiplier. */
				sprintf( __( '%1$s must be a multiple of %2$s.' ), $param, $args['multipleOf'] )
			);
		}
	
		if ( isset( $args['minimum'] ) && ! isset( $args['maximum'] ) ) {
			if ( ! empty( $args['exclusiveMinimum'] ) && $value <= $args['minimum'] ) {
				return new WP_Error(
					'rest_out_of_bounds',
					/* translators: 1: Parameter, 2: Minimum number. */
					sprintf( __( '%1$s must be greater than %2$d' ), $param, $args['minimum'] )
				);
			}
	
			if ( empty( $args['exclusiveMinimum'] ) && $value < $args['minimum'] ) {
				return new WP_Error(
					'rest_out_of_bounds',
					/* translators: 1: Parameter, 2: Minimum number. */
					sprintf( __( '%1$s must be greater than or equal to %2$d' ), $param, $args['minimum'] )
				);
			}
		}
	
		if ( isset( $args['maximum'] ) && ! isset( $args['minimum'] ) ) {
			if ( ! empty( $args['exclusiveMaximum'] ) && $value >= $args['maximum'] ) {
				return new WP_Error(
					'rest_out_of_bounds',
					/* translators: 1: Parameter, 2: Maximum number. */
					sprintf( __( '%1$s must be less than %2$d' ), $param, $args['maximum'] )
				);
			}
	
			if ( empty( $args['exclusiveMaximum'] ) && $value > $args['maximum'] ) {
				return new WP_Error(
					'rest_out_of_bounds',
					/* translators: 1: Parameter, 2: Maximum number. */
					sprintf( __( '%1$s must be less than or equal to %2$d' ), $param, $args['maximum'] )
				);
			}
		}
	
		if ( isset( $args['minimum'], $args['maximum'] ) ) {
			if ( ! empty( $args['exclusiveMinimum'] ) && ! empty( $args['exclusiveMaximum'] ) ) {
				if ( $value >= $args['maximum'] || $value <= $args['minimum'] ) {
					return new WP_Error(
						'rest_out_of_bounds',
						sprintf(
							/* translators: 1: Parameter, 2: Minimum number, 3: Maximum number. */
							__( '%1$s must be between %2$d (exclusive) and %3$d (exclusive)' ),
							$param,
							$args['minimum'],
							$args['maximum']
						)
					);
				}
			}
	
			if ( ! empty( $args['exclusiveMinimum'] ) && empty( $args['exclusiveMaximum'] ) ) {
				if ( $value > $args['maximum'] || $value <= $args['minimum'] ) {
					return new WP_Error(
						'rest_out_of_bounds',
						sprintf(
							/* translators: 1: Parameter, 2: Minimum number, 3: Maximum number. */
							__( '%1$s must be between %2$d (exclusive) and %3$d (inclusive)' ),
							$param,
							$args['minimum'],
							$args['maximum']
						)
					);
				}
			}
	
			if ( ! empty( $args['exclusiveMaximum'] ) && empty( $args['exclusiveMinimum'] ) ) {
				if ( $value >= $args['maximum'] || $value < $args['minimum'] ) {
					return new WP_Error(
						'rest_out_of_bounds',
						sprintf(
							/* translators: 1: Parameter, 2: Minimum number, 3: Maximum number. */
							__( '%1$s must be between %2$d (inclusive) and %3$d (exclusive)' ),
							$param,
							$args['minimum'],
							$args['maximum']
						)
					);
				}
			}
	
			if ( empty( $args['exclusiveMinimum'] ) && empty( $args['exclusiveMaximum'] ) ) {
				if ( $value > $args['maximum'] || $value < $args['minimum'] ) {
					return new WP_Error(
						'rest_out_of_bounds',
						sprintf(
							/* translators: 1: Parameter, 2: Minimum number, 3: Maximum number. */
							__( '%1$s must be between %2$d (inclusive) and %3$d (inclusive)' ),
							$param,
							$args['minimum'],
							$args['maximum']
						)
					);
				}
			}
		}
	
		return true;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_string_value_from_schema' ) ) :
	function rest_validate_string_value_from_schema( $value, $args, $param ) {
		if ( ! is_string( $value ) ) {
			return new WP_Error(
				'rest_invalid_type',
				/* translators: 1: Parameter, 2: Type name. */
				sprintf( __( '%1$s is not of type %2$s.' ), $param, 'string' ),
				array( 'param' => $param )
			);
		}
	
		if ( isset( $args['minLength'] ) && mb_strlen( $value ) < $args['minLength'] ) {
			return new WP_Error(
				'rest_too_short',
				sprintf(
					/* translators: 1: Parameter, 2: Number of characters. */
					_n(
						'%1$s must be at least %2$s character long.',
						'%1$s must be at least %2$s characters long.',
						$args['minLength']
					),
					$param,
					number_format_i18n( $args['minLength'] )
				)
			);
		}
	
		if ( isset( $args['maxLength'] ) && mb_strlen( $value ) > $args['maxLength'] ) {
			return new WP_Error(
				'rest_too_long',
				sprintf(
					/* translators: 1: Parameter, 2: Number of characters. */
					_n(
						'%1$s must be at most %2$s character long.',
						'%1$s must be at most %2$s characters long.',
						$args['maxLength']
					),
					$param,
					number_format_i18n( $args['maxLength'] )
				)
			);
		}
	
		if ( isset( $args['pattern'] ) && ! rest_validate_json_schema_pattern( $args['pattern'], $value ) ) {
			return new WP_Error(
				'rest_invalid_pattern',
				/* translators: 1: Parameter, 2: Pattern. */
				sprintf( __( '%1$s does not match pattern %2$s.' ), $param, $args['pattern'] )
			);
		}
	
		return true;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_validate_integer_value_from_schema' ) ) :
	function rest_validate_integer_value_from_schema( $value, $args, $param ) {
		$is_valid_number = rest_validate_number_value_from_schema( $value, $args, $param );
		if ( is_wp_error( $is_valid_number ) ) {
			return $is_valid_number;
		}
	
		if ( ! rest_is_integer( $value ) ) {
			return new WP_Error(
				'rest_invalid_type',
				/* translators: 1: Parameter, 2: Type name. */
				sprintf( __( '%1$s is not of type %2$s.' ), $param, 'integer' ),
				array( 'param' => $param )
			);
		}
	
		return true;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_sanitize_value_from_schema' ) ) :
	function rest_sanitize_value_from_schema( $value, $args, $param = '' ) {
		if ( isset( $args['anyOf'] ) ) {
			$matching_schema = rest_find_any_matching_schema( $value, $args, $param );
			if ( is_wp_error( $matching_schema ) ) {
				return $matching_schema;
			}
	
			if ( ! isset( $args['type'] ) ) {
				$args['type'] = $matching_schema['type'];
			}
	
			$value = rest_sanitize_value_from_schema( $value, $matching_schema, $param );
		}
	
		if ( isset( $args['oneOf'] ) ) {
			$matching_schema = rest_find_one_matching_schema( $value, $args, $param );
			if ( is_wp_error( $matching_schema ) ) {
				return $matching_schema;
			}
	
			if ( ! isset( $args['type'] ) ) {
				$args['type'] = $matching_schema['type'];
			}
	
			$value = rest_sanitize_value_from_schema( $value, $matching_schema, $param );
		}
	
		$allowed_types = array( 'array', 'object', 'string', 'number', 'integer', 'boolean', 'null' );
	
		if ( ! isset( $args['type'] ) ) {
			/* translators: %s: Parameter. */
			_doing_it_wrong( __FUNCTION__, sprintf( __( 'The "type" schema keyword for %s is required.' ), $param ), '5.5.0' );
		}
	
		if ( is_array( $args['type'] ) ) {
			$best_type = rest_handle_multi_type_schema( $value, $args, $param );
	
			if ( ! $best_type ) {
				return null;
			}
	
			$args['type'] = $best_type;
		}
	
		if ( ! in_array( $args['type'], $allowed_types, true ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				/* translators: 1: Parameter, 2: The list of allowed types. */
				wp_sprintf( __( 'The "type" schema keyword for %1$s can only be one of the built-in types: %2$l.' ), $param, $allowed_types ),
				'5.5.0'
			);
		}
	
		if ( 'array' === $args['type'] ) {
			$value = rest_sanitize_array( $value );
	
			if ( ! empty( $args['items'] ) ) {
				foreach ( $value as $index => $v ) {
					$value[ $index ] = rest_sanitize_value_from_schema( $v, $args['items'], $param . '[' . $index . ']' );
				}
			}
	
			if ( ! empty( $args['uniqueItems'] ) && ! rest_validate_array_contains_unique_items( $value ) ) {
				/* translators: %s: Parameter. */
				return new WP_Error( 'rest_duplicate_items', sprintf( __( '%s has duplicate items.' ), $param ) );
			}
	
			return $value;
		}
	
		if ( 'object' === $args['type'] ) {
			$value = rest_sanitize_object( $value );
	
			foreach ( $value as $property => $v ) {
				if ( isset( $args['properties'][ $property ] ) ) {
					$value[ $property ] = rest_sanitize_value_from_schema( $v, $args['properties'][ $property ], $param . '[' . $property . ']' );
					continue;
				}
	
				$pattern_property_schema = rest_find_matching_pattern_property_schema( $property, $args );
				if ( null !== $pattern_property_schema ) {
					$value[ $property ] = rest_sanitize_value_from_schema( $v, $pattern_property_schema, $param . '[' . $property . ']' );
					continue;
				}
	
				if ( isset( $args['additionalProperties'] ) ) {
					if ( false === $args['additionalProperties'] ) {
						unset( $value[ $property ] );
					} elseif ( is_array( $args['additionalProperties'] ) ) {
						$value[ $property ] = rest_sanitize_value_from_schema( $v, $args['additionalProperties'], $param . '[' . $property . ']' );
					}
				}
			}
	
			return $value;
		}
	
		if ( 'null' === $args['type'] ) {
			return null;
		}
	
		if ( 'integer' === $args['type'] ) {
			return (int) $value;
		}
	
		if ( 'number' === $args['type'] ) {
			return (float) $value;
		}
	
		if ( 'boolean' === $args['type'] ) {
			return rest_sanitize_boolean( $value );
		}
	
		// This behavior matches rest_validate_value_from_schema().
		if ( isset( $args['format'] )
			&& ( ! isset( $args['type'] ) || 'string' === $args['type'] || ! in_array( $args['type'], $allowed_types, true ) )
		) {
			switch ( $args['format'] ) {
				case 'hex-color':
					return (string) sanitize_hex_color( $value );
	
				case 'date-time':
					return sanitize_text_field( $value );
	
				case 'email':
					// sanitize_email() validates, which would be unexpected.
					return sanitize_text_field( $value );
	
				case 'uri':
					return sanitize_url( $value );
	
				case 'ip':
					return sanitize_text_field( $value );
	
				case 'uuid':
					return sanitize_text_field( $value );
	
				case 'text-field':
					return sanitize_text_field( $value );
	
				case 'textarea-field':
					return sanitize_textarea_field( $value );
			}
		}
	
		if ( 'string' === $args['type'] ) {
			return (string) $value;
		}
	
		return $value;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_parse_embed_param' ) ) :
	function rest_parse_embed_param( $embed ) {
		if ( ! $embed || 'true' === $embed || '1' === $embed ) {
			return true;
		}
	
		$rels = wp_parse_list( $embed );
	
		if ( ! $rels ) {
			return true;
		}
	
		return $rels;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_filter_response_by_context' ) ) :
	function rest_filter_response_by_context( $response_data, $schema, $context ) {
		if ( isset( $schema['anyOf'] ) ) {
			$matching_schema = rest_find_any_matching_schema( $response_data, $schema, '' );
			if ( ! is_wp_error( $matching_schema ) ) {
				if ( ! isset( $schema['type'] ) ) {
					$schema['type'] = $matching_schema['type'];
				}
	
				$response_data = rest_filter_response_by_context( $response_data, $matching_schema, $context );
			}
		}
	
		if ( isset( $schema['oneOf'] ) ) {
			$matching_schema = rest_find_one_matching_schema( $response_data, $schema, '', true );
			if ( ! is_wp_error( $matching_schema ) ) {
				if ( ! isset( $schema['type'] ) ) {
					$schema['type'] = $matching_schema['type'];
				}
	
				$response_data = rest_filter_response_by_context( $response_data, $matching_schema, $context );
			}
		}
	
		if ( ! is_array( $response_data ) && ! is_object( $response_data ) ) {
			return $response_data;
		}
	
		if ( isset( $schema['type'] ) ) {
			$type = $schema['type'];
		} elseif ( isset( $schema['properties'] ) ) {
			$type = 'object'; // Back compat if a developer accidentally omitted the type.
		} else {
			return $response_data;
		}
	
		$is_array_type  = 'array' === $type || ( is_array( $type ) && in_array( 'array', $type, true ) );
		$is_object_type = 'object' === $type || ( is_array( $type ) && in_array( 'object', $type, true ) );
	
		if ( $is_array_type && $is_object_type ) {
			if ( rest_is_array( $response_data ) ) {
				$is_object_type = false;
			} else {
				$is_array_type = false;
			}
		}
	
		$has_additional_properties = $is_object_type && isset( $schema['additionalProperties'] ) && is_array( $schema['additionalProperties'] );
	
		foreach ( $response_data as $key => $value ) {
			$check = array();
	
			if ( $is_array_type ) {
				$check = isset( $schema['items'] ) ? $schema['items'] : array();
			} elseif ( $is_object_type ) {
				if ( isset( $schema['properties'][ $key ] ) ) {
					$check = $schema['properties'][ $key ];
				} else {
					$pattern_property_schema = rest_find_matching_pattern_property_schema( $key, $schema );
					if ( null !== $pattern_property_schema ) {
						$check = $pattern_property_schema;
					} elseif ( $has_additional_properties ) {
						$check = $schema['additionalProperties'];
					}
				}
			}
	
			if ( ! isset( $check['context'] ) ) {
				continue;
			}
	
			if ( ! in_array( $context, $check['context'], true ) ) {
				if ( $is_array_type ) {
					// All array items share schema, so there's no need to check each one.
					$response_data = array();
					break;
				}
	
				if ( is_object( $response_data ) ) {
					unset( $response_data->$key );
				} else {
					unset( $response_data[ $key ] );
				}
			} elseif ( is_array( $value ) || is_object( $value ) ) {
				$new_value = rest_filter_response_by_context( $value, $check, $context );
	
				if ( is_object( $response_data ) ) {
					$response_data->$key = $new_value;
				} else {
					$response_data[ $key ] = $new_value;
				}
			}
		}
	
		return $response_data;
	}
endif;

// wp-includes/rest-api.php (WP 6.7.5)
if( ! function_exists( 'rest_default_additional_properties_to_false' ) ) :
	function rest_default_additional_properties_to_false( $schema ) {
		$type = (array) $schema['type'];
	
		if ( in_array( 'object', $type, true ) ) {
			if ( isset( $schema['properties'] ) ) {
				foreach ( $schema['properties'] as $key => $child_schema ) {
					$schema['properties'][ $key ] = rest_default_additional_properties_to_false( $child_schema );
				}
			}
	
			if ( isset( $schema['patternProperties'] ) ) {
				foreach ( $schema['patternProperties'] as $key => $child_schema ) {
					$schema['patternProperties'][ $key ] = rest_default_additional_properties_to_false( $child_schema );
				}
			}
	
			if ( ! isset( $schema['additionalProperties'] ) ) {
				$schema['additionalProperties'] = false;
			}
		}
	
		if ( in_array( 'array', $type, true ) ) {
			if ( isset( $schema['items'] ) ) {
				$schema['items'] = rest_default_additional_properties_to_false( $schema['items'] );
			}
		}
	
		return $schema;
	}
endif;

