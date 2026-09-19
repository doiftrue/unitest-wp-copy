<?php

// ------------------auto-generated---------------------

// wp-includes/block-patterns.php (WP 7.0.5)
if( ! function_exists( 'wp_normalize_remote_block_pattern' ) ) :
	function wp_normalize_remote_block_pattern( $pattern ) {
		if ( isset( $pattern['block_types'] ) ) {
			$pattern['blockTypes'] = $pattern['block_types'];
			unset( $pattern['block_types'] );
		}
	
		if ( isset( $pattern['viewport_width'] ) ) {
			$pattern['viewportWidth'] = $pattern['viewport_width'];
			unset( $pattern['viewport_width'] );
		}
	
		return (array) $pattern;
	}
endif;

// wp-includes/block-patterns.php (WP 7.0.5)
if( ! function_exists( 'register_block_pattern_category' ) ) :
	function register_block_pattern_category( $category_name, $category_properties ) {
		return WP_Block_Pattern_Categories_Registry::get_instance()->register( $category_name, $category_properties );
	}
endif;

// wp-includes/block-patterns.php (WP 7.0.5)
if( ! function_exists( 'unregister_block_pattern_category' ) ) :
	function unregister_block_pattern_category( $category_name ) {
		return WP_Block_Pattern_Categories_Registry::get_instance()->unregister( $category_name );
	}
endif;

