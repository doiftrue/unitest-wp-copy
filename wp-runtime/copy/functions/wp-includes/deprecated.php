<?php

// ------------------auto-generated---------------------

// wp-includes/deprecated.php (WP 7.0)
if( ! function_exists( 'addslashes_gpc' ) ) :
	function addslashes_gpc( $gpc ) {
		_deprecated_function( __FUNCTION__, '7.0.0', 'wp_slash()' );
		return wp_slash( $gpc );
	}
endif;

// wp-includes/deprecated.php (WP 7.0)
if( ! function_exists( 'wp_sanitize_script_attributes' ) ) :
	function wp_sanitize_script_attributes( $attributes ) {
		_deprecated_function( __FUNCTION__, '7.0.0', 'wp_get_script_tag() or wp_get_inline_script_tag()' );
	
		$attributes_string = '';
		foreach ( $attributes as $attribute_name => $attribute_value ) {
			if ( is_bool( $attribute_value ) ) {
				if ( $attribute_value ) {
					$attributes_string .= ' ' . esc_attr( $attribute_name );
				}
			} else {
				$attributes_string .= sprintf( ' %1$s="%2$s"', esc_attr( $attribute_name ), esc_attr( $attribute_value ) );
			}
		}
		return $attributes_string;
	}
endif;

