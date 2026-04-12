<?php

// ------------------auto-generated---------------------

// wp-includes/rest-api.php (WP 6.8.5)
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

