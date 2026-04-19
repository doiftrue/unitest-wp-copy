<?php

// ------------------auto-generated---------------------

// wp-includes/taxonomy.php (WP 6.6.5)
if( ! function_exists( 'register_taxonomy_for_object_type' ) ) :
	function register_taxonomy_for_object_type( $taxonomy, $object_type ) {
		global $wp_taxonomies;
	
		if ( ! isset( $wp_taxonomies[ $taxonomy ] ) ) {
			return false;
		}
	
		if ( ! get_post_type_object( $object_type ) ) {
			return false;
		}
	
		if ( ! in_array( $object_type, $wp_taxonomies[ $taxonomy ]->object_type, true ) ) {
			$wp_taxonomies[ $taxonomy ]->object_type[] = $object_type;
		}
	
		// Filter out empties.
		$wp_taxonomies[ $taxonomy ]->object_type = array_filter( $wp_taxonomies[ $taxonomy ]->object_type );
	
		/**
		 * Fires after a taxonomy is registered for an object type.
		 *
		 * @since 5.1.0
		 *
		 * @param string $taxonomy    Taxonomy name.
		 * @param string $object_type Name of the object type.
		 */
		do_action( 'registered_taxonomy_for_object_type', $taxonomy, $object_type );
	
		return true;
	}
endif;

// wp-includes/taxonomy.php (WP 6.6.5)
if( ! function_exists( 'unregister_taxonomy_for_object_type' ) ) :
	function unregister_taxonomy_for_object_type( $taxonomy, $object_type ) {
		global $wp_taxonomies;
	
		if ( ! isset( $wp_taxonomies[ $taxonomy ] ) ) {
			return false;
		}
	
		if ( ! get_post_type_object( $object_type ) ) {
			return false;
		}
	
		$key = array_search( $object_type, $wp_taxonomies[ $taxonomy ]->object_type, true );
		if ( false === $key ) {
			return false;
		}
	
		unset( $wp_taxonomies[ $taxonomy ]->object_type[ $key ] );
	
		/**
		 * Fires after a taxonomy is unregistered for an object type.
		 *
		 * @since 5.1.0
		 *
		 * @param string $taxonomy    Taxonomy name.
		 * @param string $object_type Name of the object type.
		 */
		do_action( 'unregistered_taxonomy_for_object_type', $taxonomy, $object_type );
	
		return true;
	}
endif;

// wp-includes/taxonomy.php (WP 6.6.5)
if( ! function_exists( 'sanitize_term' ) ) :
	function sanitize_term( $term, $taxonomy, $context = 'display' ) {
		$fields = array( 'term_id', 'name', 'description', 'slug', 'count', 'parent', 'term_group', 'term_taxonomy_id', 'object_id' );
	
		$do_object = is_object( $term );
	
		$term_id = $do_object ? $term->term_id : ( isset( $term['term_id'] ) ? $term['term_id'] : 0 );
	
		foreach ( (array) $fields as $field ) {
			if ( $do_object ) {
				if ( isset( $term->$field ) ) {
					$term->$field = sanitize_term_field( $field, $term->$field, $term_id, $taxonomy, $context );
				}
			} else {
				if ( isset( $term[ $field ] ) ) {
					$term[ $field ] = sanitize_term_field( $field, $term[ $field ], $term_id, $taxonomy, $context );
				}
			}
		}
	
		if ( $do_object ) {
			$term->filter = $context;
		} else {
			$term['filter'] = $context;
		}
	
		return $term;
	}
endif;

// wp-includes/taxonomy.php (WP 6.6.5)
if( ! function_exists( 'sanitize_term_field' ) ) :
	function sanitize_term_field( $field, $value, $term_id, $taxonomy, $context ) {
		$int_fields = array( 'parent', 'term_id', 'count', 'term_group', 'term_taxonomy_id', 'object_id' );
		if ( in_array( $field, $int_fields, true ) ) {
			$value = (int) $value;
			if ( $value < 0 ) {
				$value = 0;
			}
		}
	
		$context = strtolower( $context );
	
		if ( 'raw' === $context ) {
			return $value;
		}
	
		if ( 'edit' === $context ) {
	
			/**
			 * Filters a term field to edit before it is sanitized.
			 *
			 * The dynamic portion of the hook name, `$field`, refers to the term field.
			 *
			 * @since 2.3.0
			 *
			 * @param mixed $value     Value of the term field.
			 * @param int   $term_id   Term ID.
			 * @param string $taxonomy Taxonomy slug.
			 */
			$value = apply_filters( "edit_term_{$field}", $value, $term_id, $taxonomy );
	
			/**
			 * Filters the taxonomy field to edit before it is sanitized.
			 *
			 * The dynamic portions of the filter name, `$taxonomy` and `$field`, refer
			 * to the taxonomy slug and taxonomy field, respectively.
			 *
			 * @since 2.3.0
			 *
			 * @param mixed $value   Value of the taxonomy field to edit.
			 * @param int   $term_id Term ID.
			 */
			$value = apply_filters( "edit_{$taxonomy}_{$field}", $value, $term_id );
	
			if ( 'description' === $field ) {
				$value = esc_html( $value ); // textarea_escaped
			} else {
				$value = esc_attr( $value );
			}
		} elseif ( 'db' === $context ) {
	
			/**
			 * Filters a term field value before it is sanitized.
			 *
			 * The dynamic portion of the hook name, `$field`, refers to the term field.
			 *
			 * @since 2.3.0
			 *
			 * @param mixed  $value    Value of the term field.
			 * @param string $taxonomy Taxonomy slug.
			 */
			$value = apply_filters( "pre_term_{$field}", $value, $taxonomy );
	
			/**
			 * Filters a taxonomy field before it is sanitized.
			 *
			 * The dynamic portions of the filter name, `$taxonomy` and `$field`, refer
			 * to the taxonomy slug and field name, respectively.
			 *
			 * @since 2.3.0
			 *
			 * @param mixed $value Value of the taxonomy field.
			 */
			$value = apply_filters( "pre_{$taxonomy}_{$field}", $value );
	
			// Back compat filters.
			if ( 'slug' === $field ) {
				/**
				 * Filters the category nicename before it is sanitized.
				 *
				 * Use the {@see 'pre_$taxonomy_$field'} hook instead.
				 *
				 * @since 2.0.3
				 *
				 * @param string $value The category nicename.
				 */
				$value = apply_filters( 'pre_category_nicename', $value );
			}
		} elseif ( 'rss' === $context ) {
	
			/**
			 * Filters the term field for use in RSS.
			 *
			 * The dynamic portion of the hook name, `$field`, refers to the term field.
			 *
			 * @since 2.3.0
			 *
			 * @param mixed  $value    Value of the term field.
			 * @param string $taxonomy Taxonomy slug.
			 */
			$value = apply_filters( "term_{$field}_rss", $value, $taxonomy );
	
			/**
			 * Filters the taxonomy field for use in RSS.
			 *
			 * The dynamic portions of the hook name, `$taxonomy`, and `$field`, refer
			 * to the taxonomy slug and field name, respectively.
			 *
			 * @since 2.3.0
			 *
			 * @param mixed $value Value of the taxonomy field.
			 */
			$value = apply_filters( "{$taxonomy}_{$field}_rss", $value );
		} else {
			// Use display filters by default.
	
			/**
			 * Filters the term field sanitized for display.
			 *
			 * The dynamic portion of the hook name, `$field`, refers to the term field name.
			 *
			 * @since 2.3.0
			 *
			 * @param mixed  $value    Value of the term field.
			 * @param int    $term_id  Term ID.
			 * @param string $taxonomy Taxonomy slug.
			 * @param string $context  Context to retrieve the term field value.
			 */
			$value = apply_filters( "term_{$field}", $value, $term_id, $taxonomy, $context );
	
			/**
			 * Filters the taxonomy field sanitized for display.
			 *
			 * The dynamic portions of the filter name, `$taxonomy`, and `$field`, refer
			 * to the taxonomy slug and taxonomy field, respectively.
			 *
			 * @since 2.3.0
			 *
			 * @param mixed  $value   Value of the taxonomy field.
			 * @param int    $term_id Term ID.
			 * @param string $context Context to retrieve the taxonomy field value.
			 */
			$value = apply_filters( "{$taxonomy}_{$field}", $value, $term_id, $context );
		}
	
		if ( 'attribute' === $context ) {
			$value = esc_attr( $value );
		} elseif ( 'js' === $context ) {
			$value = esc_js( $value );
		}
	
		// Restore the type for integer fields after esc_attr().
		if ( in_array( $field, $int_fields, true ) ) {
			$value = (int) $value;
		}
	
		return $value;
	}
endif;

