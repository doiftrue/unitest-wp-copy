<?php

// ------------------auto-generated---------------------

// wp-includes/block-bindings.php (WP 6.5.8)
if( ! function_exists( 'register_block_bindings_source' ) ) :
	function register_block_bindings_source( string $source_name, array $source_properties ) {
		return WP_Block_Bindings_Registry::get_instance()->register( $source_name, $source_properties );
	}
endif;

// wp-includes/block-bindings.php (WP 6.5.8)
if( ! function_exists( 'unregister_block_bindings_source' ) ) :
	function unregister_block_bindings_source( string $source_name ) {
		return WP_Block_Bindings_Registry::get_instance()->unregister( $source_name );
	}
endif;

