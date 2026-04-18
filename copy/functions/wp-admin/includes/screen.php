<?php

// ------------------auto-generated---------------------

// wp-admin/includes/screen.php (WP 6.9.4)
if( ! function_exists( 'get_column_headers' ) ) :
	function get_column_headers( $screen ) {
		static $column_headers = array();
	
		if ( is_string( $screen ) ) {
			$screen = convert_to_screen( $screen );
		}
	
		if ( ! isset( $column_headers[ $screen->id ] ) ) {
			/**
			 * Filters the column headers for a list table on a specific screen.
			 *
			 * The dynamic portion of the hook name, `$screen->id`, refers to the
			 * ID of a specific screen. For example, the screen ID for the Posts
			 * list table is edit-post, so the filter for that screen would be
			 * manage_edit-post_columns.
			 *
			 * @since 3.0.0
			 *
			 * @param string[] $columns The column header labels keyed by column ID.
			 */
			$column_headers[ $screen->id ] = apply_filters( "manage_{$screen->id}_columns", array() );
		}
	
		return $column_headers[ $screen->id ];
	}
endif;

// wp-admin/includes/screen.php (WP 6.9.4)
if( ! function_exists( 'add_screen_option' ) ) :
	function add_screen_option( $option, $args = array() ) {
		$current_screen = get_current_screen();
	
		if ( ! $current_screen ) {
			return;
		}
	
		$current_screen->add_option( $option, $args );
	}
endif;

// wp-admin/includes/screen.php (WP 6.9.4)
if( ! function_exists( 'get_current_screen' ) ) :
	function get_current_screen() {
		global $current_screen;
	
		if ( ! isset( $current_screen ) ) {
			return null;
		}
	
		return $current_screen;
	}
endif;

// wp-admin/includes/screen.php (WP 6.9.4)
if( ! function_exists( 'set_current_screen' ) ) :
	function set_current_screen( $hook_name = '' ) {
		WP_Screen::get( $hook_name )->set_current_screen();
	}
endif;

