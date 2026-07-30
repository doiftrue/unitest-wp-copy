<?php

// ------------------auto-generated---------------------

// wp-includes/meta.php (WP 6.8.6)
if( ! function_exists( '_wp_register_meta_args_allowed_list' ) ) :
	function _wp_register_meta_args_allowed_list( $args, $default_args ) {
		return array_intersect_key( $args, $default_args );
	}
endif;

// wp-includes/meta.php (WP 6.8.6)
if( ! function_exists( 'get_metadata_default' ) ) :
	function get_metadata_default( $meta_type, $object_id, $meta_key, $single = false ) {
		if ( $single ) {
			$value = '';
		} else {
			$value = array();
		}
	
		/**
		 * Filters the default metadata value for a specified meta key and object.
		 *
		 * The dynamic portion of the hook name, `$meta_type`, refers to the meta object type
		 * (post, comment, term, user, or any other type with an associated meta table).
		 *
		 * Possible filter names include:
		 *
		 *  - `default_post_metadata`
		 *  - `default_comment_metadata`
		 *  - `default_term_metadata`
		 *  - `default_user_metadata`
		 *
		 * @since 5.5.0
		 *
		 * @param mixed  $value     The value to return, either a single metadata value or an array
		 *                          of values depending on the value of `$single`.
		 * @param int    $object_id ID of the object metadata is for.
		 * @param string $meta_key  Metadata key.
		 * @param bool   $single    Whether to return only the first value of the specified `$meta_key`.
		 * @param string $meta_type Type of object metadata is for. Accepts 'post', 'comment', 'term', 'user',
		 *                          or any other object type with an associated meta table.
		 */
		$value = apply_filters( "default_{$meta_type}_metadata", $value, $object_id, $meta_key, $single, $meta_type );
	
		if ( ! $single && ! wp_is_numeric_array( $value ) ) {
			$value = array( $value );
		}
	
		return $value;
	}
endif;

// wp-includes/meta.php (WP 6.8.6)
if( ! function_exists( 'registered_meta_key_exists' ) ) :
	function registered_meta_key_exists( $object_type, $meta_key, $object_subtype = '' ) {
		$meta_keys = get_registered_meta_keys( $object_type, $object_subtype );
	
		return isset( $meta_keys[ $meta_key ] );
	}
endif;

// wp-includes/meta.php (WP 6.8.6)
if( ! function_exists( 'unregister_meta_key' ) ) :
	function unregister_meta_key( $object_type, $meta_key, $object_subtype = '' ) {
		global $wp_meta_keys;
	
		if ( ! registered_meta_key_exists( $object_type, $meta_key, $object_subtype ) ) {
			return false;
		}
	
		$args = $wp_meta_keys[ $object_type ][ $object_subtype ][ $meta_key ];
	
		if ( isset( $args['sanitize_callback'] ) && is_callable( $args['sanitize_callback'] ) ) {
			if ( ! empty( $object_subtype ) ) {
				remove_filter( "sanitize_{$object_type}_meta_{$meta_key}_for_{$object_subtype}", $args['sanitize_callback'] );
			} else {
				remove_filter( "sanitize_{$object_type}_meta_{$meta_key}", $args['sanitize_callback'] );
			}
		}
	
		if ( isset( $args['auth_callback'] ) && is_callable( $args['auth_callback'] ) ) {
			if ( ! empty( $object_subtype ) ) {
				remove_filter( "auth_{$object_type}_meta_{$meta_key}_for_{$object_subtype}", $args['auth_callback'] );
			} else {
				remove_filter( "auth_{$object_type}_meta_{$meta_key}", $args['auth_callback'] );
			}
		}
	
		unset( $wp_meta_keys[ $object_type ][ $object_subtype ][ $meta_key ] );
	
		// Do some clean up.
		if ( empty( $wp_meta_keys[ $object_type ][ $object_subtype ] ) ) {
			unset( $wp_meta_keys[ $object_type ][ $object_subtype ] );
		}
		if ( empty( $wp_meta_keys[ $object_type ] ) ) {
			unset( $wp_meta_keys[ $object_type ] );
		}
	
		return true;
	}
endif;

// wp-includes/meta.php (WP 6.8.6)
if( ! function_exists( 'register_meta' ) ) :
	function register_meta( $object_type, $meta_key, $args, $deprecated = null ) {
		global $wp_meta_keys;
	
		if ( ! is_array( $wp_meta_keys ) ) {
			$wp_meta_keys = array();
		}
	
		$defaults = array(
			'object_subtype'    => '',
			'type'              => 'string',
			'label'             => '',
			'description'       => '',
			'default'           => '',
			'single'            => false,
			'sanitize_callback' => null,
			'auth_callback'     => null,
			'show_in_rest'      => false,
			'revisions_enabled' => false,
		);
	
		// There used to be individual args for sanitize and auth callbacks.
		$has_old_sanitize_cb = false;
		$has_old_auth_cb     = false;
	
		if ( is_callable( $args ) ) {
			$args = array(
				'sanitize_callback' => $args,
			);
	
			$has_old_sanitize_cb = true;
		} else {
			$args = (array) $args;
		}
	
		if ( is_callable( $deprecated ) ) {
			$args['auth_callback'] = $deprecated;
			$has_old_auth_cb       = true;
		}
	
		/**
		 * Filters the registration arguments when registering meta.
		 *
		 * @since 4.6.0
		 *
		 * @param array  $args        Array of meta registration arguments.
		 * @param array  $defaults    Array of default arguments.
		 * @param string $object_type Type of object metadata is for. Accepts 'post', 'comment', 'term', 'user',
		 *                            or any other object type with an associated meta table.
		 * @param string $meta_key    Meta key.
		 */
		$args = apply_filters( 'register_meta_args', $args, $defaults, $object_type, $meta_key );
		unset( $defaults['default'] );
		$args = wp_parse_args( $args, $defaults );
	
		// Require an item schema when registering array meta.
		if ( false !== $args['show_in_rest'] && 'array' === $args['type'] ) {
			if ( ! is_array( $args['show_in_rest'] ) || ! isset( $args['show_in_rest']['schema']['items'] ) ) {
				_doing_it_wrong( __FUNCTION__, __( 'When registering an "array" meta type to show in the REST API, you must specify the schema for each array item in "show_in_rest.schema.items".' ), '5.3.0' );
	
				return false;
			}
		}
	
		$object_subtype = ! empty( $args['object_subtype'] ) ? $args['object_subtype'] : '';
		if ( $args['revisions_enabled'] ) {
			if ( 'post' !== $object_type ) {
				_doing_it_wrong( __FUNCTION__, __( 'Meta keys cannot enable revisions support unless the object type supports revisions.' ), '6.4.0' );
	
				return false;
			} elseif ( ! empty( $object_subtype ) && ! post_type_supports( $object_subtype, 'revisions' ) ) {
				_doing_it_wrong( __FUNCTION__, __( 'Meta keys cannot enable revisions support unless the object subtype supports revisions.' ), '6.4.0' );
	
				return false;
			}
		}
	
		// If `auth_callback` is not provided, fall back to `is_protected_meta()`.
		if ( empty( $args['auth_callback'] ) ) {
			if ( is_protected_meta( $meta_key, $object_type ) ) {
				$args['auth_callback'] = '__return_false';
			} else {
				$args['auth_callback'] = '__return_true';
			}
		}
	
		// Back-compat: old sanitize and auth callbacks are applied to all of an object type.
		if ( is_callable( $args['sanitize_callback'] ) ) {
			if ( ! empty( $object_subtype ) ) {
				add_filter( "sanitize_{$object_type}_meta_{$meta_key}_for_{$object_subtype}", $args['sanitize_callback'], 10, 4 );
			} else {
				add_filter( "sanitize_{$object_type}_meta_{$meta_key}", $args['sanitize_callback'], 10, 3 );
			}
		}
	
		if ( is_callable( $args['auth_callback'] ) ) {
			if ( ! empty( $object_subtype ) ) {
				add_filter( "auth_{$object_type}_meta_{$meta_key}_for_{$object_subtype}", $args['auth_callback'], 10, 6 );
			} else {
				add_filter( "auth_{$object_type}_meta_{$meta_key}", $args['auth_callback'], 10, 6 );
			}
		}
	
		if ( array_key_exists( 'default', $args ) ) {
			$schema = $args;
			if ( is_array( $args['show_in_rest'] ) && isset( $args['show_in_rest']['schema'] ) ) {
				$schema = array_merge( $schema, $args['show_in_rest']['schema'] );
			}
	
			$check = rest_validate_value_from_schema( $args['default'], $schema );
			if ( is_wp_error( $check ) ) {
				_doing_it_wrong( __FUNCTION__, __( 'When registering a default meta value the data must match the type provided.' ), '5.5.0' );
	
				return false;
			}
	
			if ( ! has_filter( "default_{$object_type}_metadata", 'filter_default_metadata' ) ) {
				add_filter( "default_{$object_type}_metadata", 'filter_default_metadata', 10, 5 );
			}
		}
	
		// Global registry only contains meta keys registered with the array of arguments added in 4.6.0.
		if ( ! $has_old_auth_cb && ! $has_old_sanitize_cb ) {
			unset( $args['object_subtype'] );
	
			$wp_meta_keys[ $object_type ][ $object_subtype ][ $meta_key ] = $args;
	
			return true;
		}
	
		return false;
	}
endif;

// wp-includes/meta.php (WP 6.8.6)
if( ! function_exists( 'get_meta_sql' ) ) :
	function get_meta_sql( $meta_query, $type, $primary_table, $primary_id_column, $context = null ) {
		$meta_query_obj = new WP_Meta_Query( $meta_query );
		return $meta_query_obj->get_sql( $type, $primary_table, $primary_id_column, $context );
	}
endif;

// wp-includes/meta.php (WP 6.8.6)
if( ! function_exists( 'sanitize_meta' ) ) :
	function sanitize_meta( $meta_key, $meta_value, $object_type, $object_subtype = '' ) {
		if ( ! empty( $object_subtype ) && has_filter( "sanitize_{$object_type}_meta_{$meta_key}_for_{$object_subtype}" ) ) {
	
			/**
			 * Filters the sanitization of a specific meta key of a specific meta type and subtype.
			 *
			 * The dynamic portions of the hook name, `$object_type`, `$meta_key`,
			 * and `$object_subtype`, refer to the metadata object type (comment, post, term, or user),
			 * the meta key value, and the object subtype respectively.
			 *
			 * @since 4.9.8
			 *
			 * @param mixed  $meta_value     Metadata value to sanitize.
			 * @param string $meta_key       Metadata key.
			 * @param string $object_type    Type of object metadata is for. Accepts 'post', 'comment', 'term', 'user',
			 *                               or any other object type with an associated meta table.
			 * @param string $object_subtype Object subtype.
			 */
			return apply_filters( "sanitize_{$object_type}_meta_{$meta_key}_for_{$object_subtype}", $meta_value, $meta_key, $object_type, $object_subtype );
		}
	
		/**
		 * Filters the sanitization of a specific meta key of a specific meta type.
		 *
		 * The dynamic portions of the hook name, `$meta_type`, and `$meta_key`,
		 * refer to the metadata object type (comment, post, term, or user) and the meta
		 * key value, respectively.
		 *
		 * @since 3.3.0
		 *
		 * @param mixed  $meta_value  Metadata value to sanitize.
		 * @param string $meta_key    Metadata key.
		 * @param string $object_type Type of object metadata is for. Accepts 'post', 'comment', 'term', 'user',
		 *                            or any other object type with an associated meta table.
		 */
		return apply_filters( "sanitize_{$object_type}_meta_{$meta_key}", $meta_value, $meta_key, $object_type );
	}
endif;

// wp-includes/meta.php (WP 6.8.6)
if( ! function_exists( '_get_meta_table' ) ) :
	function _get_meta_table( $type ) {
		global $wpdb;
	
		$table_name = $type . 'meta';
	
		if ( empty( $wpdb->$table_name ) ) {
			return false;
		}
	
		return $wpdb->$table_name;
	}
endif;

