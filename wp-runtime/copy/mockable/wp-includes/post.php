<?php

// ------------------auto-generated---------------------

// wp-includes/post.php (WP 6.6.5)
if( ! function_exists( 'is_post_type_hierarchical' ) ) :
	function is_post_type_hierarchical( $post_type ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( ! post_type_exists( $post_type ) ) {
			return false;
		}
	
		$post_type = get_post_type_object( $post_type );
		return $post_type->hierarchical;
	}
endif;

// wp-includes/post.php (WP 6.6.5)
if( ! function_exists( 'post_type_exists' ) ) :
	function post_type_exists( $post_type ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		return (bool) get_post_type_object( $post_type );
	}
endif;

// wp-includes/post.php (WP 6.6.5)
if( ! function_exists( 'get_post_type_object' ) ) :
	function get_post_type_object( $post_type ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_post_types;
	
		if ( ! is_scalar( $post_type ) || empty( $wp_post_types[ $post_type ] ) ) {
			return null;
		}
	
		return $wp_post_types[ $post_type ];
	}
endif;

// wp-includes/post.php (WP 6.6.5)
if( ! function_exists( 'get_post_types' ) ) :
	function get_post_types( $args = array(), $output = 'names', $operator = 'and' ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_post_types;
	
		$field = ( 'names' === $output ) ? 'name' : false;
	
		return wp_filter_object_list( $wp_post_types, $args, $operator, $field );
	}
endif;

// wp-includes/post.php (WP 6.6.5)
if( ! function_exists( 'is_post_type_viewable' ) ) :
	function is_post_type_viewable( $post_type ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( is_scalar( $post_type ) ) {
			$post_type = get_post_type_object( $post_type );
	
			if ( ! $post_type ) {
				return false;
			}
		}
	
		if ( ! is_object( $post_type ) ) {
			return false;
		}
	
		$is_viewable = $post_type->publicly_queryable || ( $post_type->_builtin && $post_type->public );
	
		/**
		 * Filters whether a post type is considered "viewable".
		 *
		 * The returned filtered value must be a boolean type to ensure
		 * `is_post_type_viewable()` only returns a boolean. This strictness
		 * is by design to maintain backwards-compatibility and guard against
		 * potential type errors in PHP 8.1+. Non-boolean values (even falsey
		 * and truthy values) will result in the function returning false.
		 *
		 * @since 5.9.0
		 *
		 * @param bool         $is_viewable Whether the post type is "viewable" (strict type).
		 * @param WP_Post_Type $post_type   Post type object.
		 */
		return true === apply_filters( 'is_post_type_viewable', $is_viewable, $post_type );
	}
endif;

// wp-includes/post.php (WP 6.6.5)
if( ! function_exists( 'is_post_status_viewable' ) ) :
	function is_post_status_viewable( $post_status ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( is_scalar( $post_status ) ) {
			$post_status = get_post_status_object( $post_status );
	
			if ( ! $post_status ) {
				return false;
			}
		}
	
		if (
			! is_object( $post_status ) ||
			$post_status->internal ||
			$post_status->protected
		) {
			return false;
		}
	
		$is_viewable = $post_status->publicly_queryable || ( $post_status->_builtin && $post_status->public );
	
		/**
		 * Filters whether a post status is considered "viewable".
		 *
		 * The returned filtered value must be a boolean type to ensure
		 * `is_post_status_viewable()` only returns a boolean. This strictness
		 * is by design to maintain backwards-compatibility and guard against
		 * potential type errors in PHP 8.1+. Non-boolean values (even falsey
		 * and truthy values) will result in the function returning false.
		 *
		 * @since 5.9.0
		 *
		 * @param bool     $is_viewable Whether the post status is "viewable" (strict type).
		 * @param stdClass $post_status Post status object.
		 */
		return true === apply_filters( 'is_post_status_viewable', $is_viewable, $post_status );
	}
endif;

