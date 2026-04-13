<?php

// ------------------auto-generated---------------------

// wp-includes/taxonomy.php (WP 6.8.5)
if( ! function_exists( 'taxonomy_exists' ) ) :
	function taxonomy_exists( $taxonomy ) {
		global $wp_taxonomies;
	
		return is_string( $taxonomy ) && isset( $wp_taxonomies[ $taxonomy ] );
	}
endif;

