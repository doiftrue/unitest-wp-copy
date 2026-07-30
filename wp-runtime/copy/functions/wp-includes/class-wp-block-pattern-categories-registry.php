<?php

// ------------------auto-generated---------------------

// wp-includes/class-wp-block-pattern-categories-registry.php (WP 6.6.5)
if( ! function_exists( 'register_block_pattern_category' ) ) :
	function register_block_pattern_category( $category_name, $category_properties ) {
		return WP_Block_Pattern_Categories_Registry::get_instance()->register( $category_name, $category_properties );
	}
endif;

// wp-includes/class-wp-block-pattern-categories-registry.php (WP 6.6.5)
if( ! function_exists( 'unregister_block_pattern_category' ) ) :
	function unregister_block_pattern_category( $category_name ) {
		return WP_Block_Pattern_Categories_Registry::get_instance()->unregister( $category_name );
	}
endif;

