<?php

// ------------------auto-generated---------------------

// wp-includes/post.php (WP 6.8.5)
if( ! function_exists( 'post_type_exists' ) ) :
	function post_type_exists( $post_type ) {
		return (bool) get_post_type_object( $post_type );
	}
endif;

// wp-includes/post.php (WP 6.8.5)
if( ! function_exists( 'get_post_type_object' ) ) :
	function get_post_type_object( $post_type ) {
		global $wp_post_types;
	
		if ( ! is_scalar( $post_type ) || empty( $wp_post_types[ $post_type ] ) ) {
			return null;
		}
	
		return $wp_post_types[ $post_type ];
	}
endif;

