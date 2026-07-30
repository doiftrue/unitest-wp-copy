<?php

// ------------------auto-generated---------------------

// wp-includes/meta.php (WP 6.9.5)
if( ! function_exists( 'get_registered_meta_keys' ) ) :
	function get_registered_meta_keys( $object_type, $object_subtype = '' ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_meta_keys;
	
		if ( ! is_array( $wp_meta_keys ) || ! isset( $wp_meta_keys[ $object_type ] ) || ! isset( $wp_meta_keys[ $object_type ][ $object_subtype ] ) ) {
			return array();
		}
	
		return $wp_meta_keys[ $object_type ][ $object_subtype ];
	}
endif;

// wp-includes/meta.php (WP 6.9.5)
if( ! function_exists( 'is_protected_meta' ) ) :
	function is_protected_meta( $meta_key, $meta_type = '' ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$sanitized_key = preg_replace( "/[^\x20-\x7E\p{L}]/", '', $meta_key );
		$protected     = strlen( $sanitized_key ) > 0 && ( '_' === $sanitized_key[0] );
	
		/**
		 * Filters whether a meta key is considered protected.
		 *
		 * @since 3.2.0
		 *
		 * @param bool   $protected Whether the key is considered protected.
		 * @param string $meta_key  Metadata key.
		 * @param string $meta_type Type of object metadata is for. Accepts 'blog', 'post', 'comment', 'term',
		 *                          'user', or any other object type with an associated meta table.
		 */
		return apply_filters( 'is_protected_meta', $protected, $meta_key, $meta_type );
	}
endif;

