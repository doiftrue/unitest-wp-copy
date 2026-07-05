<?php

// ------------------auto-generated---------------------

// wp-includes/category.php (WP 6.8.5)
if( ! function_exists( 'sanitize_category' ) ) :
	function sanitize_category( $category, $context = 'display' ) {
		return sanitize_term( $category, 'category', $context );
	}
endif;

// wp-includes/category.php (WP 6.8.5)
if( ! function_exists( 'sanitize_category_field' ) ) :
	function sanitize_category_field( $field, $value, $cat_id, $context ) {
		return sanitize_term_field( $field, $value, $cat_id, 'category', $context );
	}
endif;

// wp-includes/category.php (WP 6.8.5)
if( ! function_exists( '_make_cat_compat' ) ) :
	function _make_cat_compat( &$category ) {
		if ( is_object( $category ) && ! is_wp_error( $category ) ) {
			$category->cat_ID               = $category->term_id;
			$category->category_count       = $category->count;
			$category->category_description = $category->description;
			$category->cat_name             = $category->name;
			$category->category_nicename    = $category->slug;
			$category->category_parent      = $category->parent;
		} elseif ( is_array( $category ) && isset( $category['term_id'] ) ) {
			$category['cat_ID']               = &$category['term_id'];
			$category['category_count']       = &$category['count'];
			$category['category_description'] = &$category['description'];
			$category['cat_name']             = &$category['name'];
			$category['category_nicename']    = &$category['slug'];
			$category['category_parent']      = &$category['parent'];
		}
	}
endif;

