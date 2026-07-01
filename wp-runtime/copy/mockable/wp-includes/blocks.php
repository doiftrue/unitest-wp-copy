<?php

// ------------------auto-generated---------------------

// wp-includes/blocks.php (WP 6.9.4)
if( ! function_exists( 'get_dynamic_block_names' ) ) :
	function get_dynamic_block_names() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$dynamic_block_names = array();
	
		$block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
		foreach ( $block_types as $block_type ) {
			if ( $block_type->is_dynamic() ) {
				$dynamic_block_names[] = $block_type->name;
			}
		}
	
		return $dynamic_block_names;
	}
endif;

// wp-includes/blocks.php (WP 6.9.4)
if( ! function_exists( 'get_hooked_blocks' ) ) :
	function get_hooked_blocks() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$block_types   = WP_Block_Type_Registry::get_instance()->get_all_registered();
		$hooked_blocks = array();
		foreach ( $block_types as $block_type ) {
			if ( ! ( $block_type instanceof WP_Block_Type ) || ! is_array( $block_type->block_hooks ) ) {
				continue;
			}
			foreach ( $block_type->block_hooks as $anchor_block_type => $relative_position ) {
				if ( ! isset( $hooked_blocks[ $anchor_block_type ] ) ) {
					$hooked_blocks[ $anchor_block_type ] = array();
				}
				if ( ! isset( $hooked_blocks[ $anchor_block_type ][ $relative_position ] ) ) {
					$hooked_blocks[ $anchor_block_type ][ $relative_position ] = array();
				}
				$hooked_blocks[ $anchor_block_type ][ $relative_position ][] = $block_type->name;
			}
		}
	
		return $hooked_blocks;
	}
endif;

