<?php

// ------------------auto-generated---------------------

// wp-includes/block-bindings.php (WP 7.0)
if( ! function_exists( 'get_all_registered_block_bindings_sources' ) ) :
	function get_all_registered_block_bindings_sources() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		return WP_Block_Bindings_Registry::get_instance()->get_all_registered();
	}
endif;

// wp-includes/block-bindings.php (WP 7.0)
if( ! function_exists( 'get_block_bindings_source' ) ) :
	function get_block_bindings_source( string $source_name ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		return WP_Block_Bindings_Registry::get_instance()->get_registered( $source_name );
	}
endif;

