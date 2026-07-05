<?php

// ------------------auto-generated---------------------

// wp-includes/block-bindings.php (WP 7.0)
if( ! function_exists( 'get_block_bindings_supported_attributes' ) ) :
	function get_block_bindings_supported_attributes( $block_type ) {
		$block_bindings_supported_attributes = array(
			'core/paragraph'          => array( 'content' ),
			'core/heading'            => array( 'content' ),
			'core/image'              => array( 'id', 'url', 'title', 'alt', 'caption' ),
			'core/button'             => array( 'url', 'text', 'linkTarget', 'rel' ),
			'core/post-date'          => array( 'datetime' ),
			'core/navigation-link'    => array( 'url' ),
			'core/navigation-submenu' => array( 'url' ),
		);
	
		$supported_block_attributes =
			isset( $block_type, $block_bindings_supported_attributes[ $block_type ] ) ?
				$block_bindings_supported_attributes[ $block_type ] :
				array();
	
		/**
		 * Filters the supported block attributes for block bindings.
		 *
		 * @since 6.9.0
		 *
		 * @param string[] $supported_block_attributes The block's attributes that are supported by block bindings.
		 * @param string   $block_type                 The block type whose attributes are being filtered.
		 */
		$supported_block_attributes = apply_filters(
			'block_bindings_supported_attributes',
			$supported_block_attributes,
			$block_type
		);
	
		/**
		 * Filters the supported block attributes for block bindings.
		 *
		 * The dynamic portion of the hook name, `$block_type`, refers to the block type
		 * whose attributes are being filtered.
		 *
		 * @since 6.9.0
		 *
		 * @param string[] $supported_block_attributes The block's attributes that are supported by block bindings.
		 */
		$supported_block_attributes = apply_filters(
			"block_bindings_supported_attributes_{$block_type}",
			$supported_block_attributes
		);
	
		return $supported_block_attributes;
	}
endif;

// wp-includes/block-bindings.php (WP 7.0)
if( ! function_exists( 'register_block_bindings_source' ) ) :
	function register_block_bindings_source( string $source_name, array $source_properties ) {
		return WP_Block_Bindings_Registry::get_instance()->register( $source_name, $source_properties );
	}
endif;

// wp-includes/block-bindings.php (WP 7.0)
if( ! function_exists( 'unregister_block_bindings_source' ) ) :
	function unregister_block_bindings_source( string $source_name ) {
		return WP_Block_Bindings_Registry::get_instance()->unregister( $source_name );
	}
endif;

// wp-includes/block-bindings.php (WP 7.0)
if( ! function_exists( 'get_all_registered_block_bindings_sources' ) ) :
	function get_all_registered_block_bindings_sources() {
		return WP_Block_Bindings_Registry::get_instance()->get_all_registered();
	}
endif;

// wp-includes/block-bindings.php (WP 7.0)
if( ! function_exists( 'get_block_bindings_source' ) ) :
	function get_block_bindings_source( string $source_name ) {
		return WP_Block_Bindings_Registry::get_instance()->get_registered( $source_name );
	}
endif;

