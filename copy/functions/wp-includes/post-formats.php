<?php

// ------------------auto-generated---------------------

// wp-includes/post-formats.php (WP 6.9.4)
if( ! function_exists( 'get_post_format_strings' ) ) :
	function get_post_format_strings() {
		$strings = array(
			'standard' => _x( 'Standard', 'Post format' ), // Special case. Any value that evals to false will be considered standard.
			'aside'    => _x( 'Aside', 'Post format' ),
			'chat'     => _x( 'Chat', 'Post format' ),
			'gallery'  => _x( 'Gallery', 'Post format' ),
			'link'     => _x( 'Link', 'Post format' ),
			'image'    => _x( 'Image', 'Post format' ),
			'quote'    => _x( 'Quote', 'Post format' ),
			'status'   => _x( 'Status', 'Post format' ),
			'video'    => _x( 'Video', 'Post format' ),
			'audio'    => _x( 'Audio', 'Post format' ),
		);
		return $strings;
	}
endif;

// wp-includes/post-formats.php (WP 6.9.4)
if( ! function_exists( 'get_post_format_slugs' ) ) :
	function get_post_format_slugs() {
		$slugs = array_keys( get_post_format_strings() );
		return array_combine( $slugs, $slugs );
	}
endif;

// wp-includes/post-formats.php (WP 6.9.4)
if( ! function_exists( 'get_post_format_string' ) ) :
	function get_post_format_string( $slug ) {
		$strings = get_post_format_strings();
		if ( ! $slug ) {
			return $strings['standard'];
		} else {
			return ( isset( $strings[ $slug ] ) ) ? $strings[ $slug ] : '';
		}
	}
endif;

// wp-includes/post-formats.php (WP 6.9.4)
if( ! function_exists( '_post_format_get_term' ) ) :
	function _post_format_get_term( $term ) {
		if ( isset( $term->slug ) ) {
			$term->name = get_post_format_string( str_replace( 'post-format-', '', $term->slug ) );
		}
		return $term;
	}
endif;

// wp-includes/post-formats.php (WP 6.9.4)
if( ! function_exists( '_post_format_get_terms' ) ) :
	function _post_format_get_terms( $terms, $taxonomies, $args ) {
		if ( in_array( 'post_format', (array) $taxonomies, true ) ) {
			if ( isset( $args['fields'] ) && 'names' === $args['fields'] ) {
				foreach ( $terms as $order => $name ) {
					$terms[ $order ] = get_post_format_string( str_replace( 'post-format-', '', $name ) );
				}
			} else {
				foreach ( (array) $terms as $order => $term ) {
					if ( isset( $term->taxonomy ) && 'post_format' === $term->taxonomy ) {
						$terms[ $order ]->name = get_post_format_string( str_replace( 'post-format-', '', $term->slug ) );
					}
				}
			}
		}
		return $terms;
	}
endif;

// wp-includes/post-formats.php (WP 6.9.4)
if( ! function_exists( '_post_format_wp_get_object_terms' ) ) :
	function _post_format_wp_get_object_terms( $terms ) {
		foreach ( (array) $terms as $order => $term ) {
			if ( isset( $term->taxonomy ) && 'post_format' === $term->taxonomy ) {
				$terms[ $order ]->name = get_post_format_string( str_replace( 'post-format-', '', $term->slug ) );
			}
		}
		return $terms;
	}
endif;

