<?php

// ------------------auto-generated---------------------

// wp-includes/taxonomy.php (WP 6.7.5)
if( ! function_exists( 'get_taxonomies' ) ) :
	function get_taxonomies( $args = array(), $output = 'names', $operator = 'and' ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_taxonomies;
	
		$field = ( 'names' === $output ) ? 'name' : false;
	
		return wp_filter_object_list( $wp_taxonomies, $args, $operator, $field );
	}
endif;

// wp-includes/taxonomy.php (WP 6.7.5)
if( ! function_exists( 'get_taxonomy' ) ) :
	function get_taxonomy( $taxonomy ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_taxonomies;
	
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return false;
		}
	
		return $wp_taxonomies[ $taxonomy ];
	}
endif;

// wp-includes/taxonomy.php (WP 6.7.5)
if( ! function_exists( 'taxonomy_exists' ) ) :
	function taxonomy_exists( $taxonomy ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_taxonomies;
	
		return is_string( $taxonomy ) && isset( $wp_taxonomies[ $taxonomy ] );
	}
endif;

// wp-includes/taxonomy.php (WP 6.7.5)
if( ! function_exists( 'is_taxonomy_hierarchical' ) ) :
	function is_taxonomy_hierarchical( $taxonomy ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return false;
		}
	
		$taxonomy = get_taxonomy( $taxonomy );
		return $taxonomy->hierarchical;
	}
endif;

// wp-includes/taxonomy.php (WP 6.7.5)
if( ! function_exists( 'is_taxonomy_viewable' ) ) :
	function is_taxonomy_viewable( $taxonomy ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( is_scalar( $taxonomy ) ) {
			$taxonomy = get_taxonomy( $taxonomy );
			if ( ! $taxonomy ) {
				return false;
			}
		}
	
		return $taxonomy->publicly_queryable;
	}
endif;

