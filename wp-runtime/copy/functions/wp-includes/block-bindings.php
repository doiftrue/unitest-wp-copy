<?php

// ------------------auto-generated---------------------

// wp-includes/block-bindings.php (WP 6.8.5)
if( ! function_exists( 'register_block_bindings_source' ) ) :
	function register_block_bindings_source( string $source_name, array $source_properties ) {
		return WP_Block_Bindings_Registry::get_instance()->register( $source_name, $source_properties );
	}
endif;

// wp-includes/block-bindings.php (WP 6.8.5)
if( ! function_exists( 'unregister_block_bindings_source' ) ) :
	function unregister_block_bindings_source( string $source_name ) {
		return WP_Block_Bindings_Registry::get_instance()->unregister( $source_name );
	}
endif;

// wp-includes/block-bindings.php (WP 6.8.5)
if( ! function_exists( 'get_all_registered_block_bindings_sources' ) ) :
	function get_all_registered_block_bindings_sources() {
		return WP_Block_Bindings_Registry::get_instance()->get_all_registered();
	}
endif;

// wp-includes/block-bindings.php (WP 6.8.5)
if( ! function_exists( 'get_block_bindings_source' ) ) :
	function get_block_bindings_source( string $source_name ) {
		return WP_Block_Bindings_Registry::get_instance()->get_registered( $source_name );
	}
endif;

