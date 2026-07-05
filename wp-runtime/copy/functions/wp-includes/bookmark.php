<?php

// ------------------auto-generated---------------------

// wp-includes/bookmark.php (WP 7.0)
if( ! function_exists( 'sanitize_bookmark' ) ) :
	function sanitize_bookmark( $bookmark, $context = 'display' ) {
		$fields = array(
			'link_id',
			'link_url',
			'link_name',
			'link_image',
			'link_target',
			'link_category',
			'link_description',
			'link_visible',
			'link_owner',
			'link_rating',
			'link_updated',
			'link_rel',
			'link_notes',
			'link_rss',
		);
	
		if ( is_object( $bookmark ) ) {
			$do_object = true;
			$link_id   = $bookmark->link_id;
		} else {
			$do_object = false;
			$link_id   = $bookmark['link_id'];
		}
	
		foreach ( $fields as $field ) {
			if ( $do_object ) {
				if ( isset( $bookmark->$field ) ) {
					$bookmark->$field = sanitize_bookmark_field( $field, $bookmark->$field, $link_id, $context );
				}
			} else {
				if ( isset( $bookmark[ $field ] ) ) {
					$bookmark[ $field ] = sanitize_bookmark_field( $field, $bookmark[ $field ], $link_id, $context );
				}
			}
		}
	
		return $bookmark;
	}
endif;

// wp-includes/bookmark.php (WP 7.0)
if( ! function_exists( 'sanitize_bookmark_field' ) ) :
	function sanitize_bookmark_field( $field, $value, $bookmark_id, $context ) {
		$int_fields = array( 'link_id', 'link_rating' );
		if ( in_array( $field, $int_fields, true ) ) {
			$value = (int) $value;
		}
	
		switch ( $field ) {
			case 'link_category': // array( ints )
				$value = array_map( 'absint', (array) $value );
				/*
				 * We return here so that the categories aren't filtered.
				 * The 'link_category' filter is for the name of a link category, not an array of a link's link categories.
				 */
				return $value;
	
			case 'link_visible': // bool stored as Y|N
				$value = preg_replace( '/[^YNyn]/', '', $value );
				break;
			case 'link_target': // "enum"
				$targets = array( '_top', '_blank' );
				if ( ! in_array( $value, $targets, true ) ) {
					$value = '';
				}
				break;
		}
	
		if ( 'raw' === $context ) {
			return $value;
		}
	
		if ( 'edit' === $context ) {
			/** This filter is documented in wp-includes/post.php */
			$value = apply_filters( "edit_{$field}", $value, $bookmark_id );
	
			if ( 'link_notes' === $field ) {
				$value = esc_html( $value ); // textarea_escaped
			} else {
				$value = esc_attr( $value );
			}
		} elseif ( 'db' === $context ) {
			/** This filter is documented in wp-includes/post.php */
			$value = apply_filters( "pre_{$field}", $value );
		} else {
			/** This filter is documented in wp-includes/post.php */
			$value = apply_filters( "{$field}", $value, $bookmark_id, $context );
	
			if ( 'attribute' === $context ) {
				$value = esc_attr( $value );
			} elseif ( 'js' === $context ) {
				$value = esc_js( $value );
			}
		}
	
		// Restore the type for integer fields after esc_attr().
		if ( in_array( $field, $int_fields, true ) ) {
			$value = (int) $value;
		}
	
		return $value;
	}
endif;

