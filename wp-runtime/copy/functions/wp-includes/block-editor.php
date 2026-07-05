<?php

// ------------------auto-generated---------------------

// wp-includes/block-editor.php (WP 7.0)
if( ! function_exists( 'wp_get_first_block' ) ) :
	function wp_get_first_block( $blocks, $block_name ) {
		foreach ( $blocks as $block ) {
			if ( $block_name === $block['blockName'] ) {
				return $block;
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$found_block = wp_get_first_block( $block['innerBlocks'], $block_name );
	
				if ( ! empty( $found_block ) ) {
					return $found_block;
				}
			}
		}
	
		return array();
	}
endif;

// wp-includes/block-editor.php (WP 7.0)
if( ! function_exists( 'get_default_block_categories' ) ) :
	function get_default_block_categories() {
		return array(
			array(
				'slug'  => 'text',
				'title' => _x( 'Text', 'block category' ),
				'icon'  => null,
			),
			array(
				'slug'  => 'media',
				'title' => _x( 'Media', 'block category' ),
				'icon'  => null,
			),
			array(
				'slug'  => 'design',
				'title' => _x( 'Design', 'block category' ),
				'icon'  => null,
			),
			array(
				'slug'  => 'widgets',
				'title' => _x( 'Widgets', 'block category' ),
				'icon'  => null,
			),
			array(
				'slug'  => 'theme',
				'title' => _x( 'Theme', 'block category' ),
				'icon'  => null,
			),
			array(
				'slug'  => 'embed',
				'title' => _x( 'Embeds', 'block category' ),
				'icon'  => null,
			),
			array(
				'slug'  => 'reusable',
				'title' => _x( 'Patterns', 'block category' ),
				'icon'  => null,
			),
		);
	}
endif;

// wp-includes/block-editor.php (WP 7.0)
if( ! function_exists( 'get_allowed_block_types' ) ) :
	function get_allowed_block_types( $block_editor_context ) {
		$allowed_block_types = true;
	
		/**
		 * Filters the allowed block types for all editor types.
		 *
		 * @since 5.8.0
		 *
		 * @param bool|string[]           $allowed_block_types  Array of block type slugs, or boolean to enable/disable all.
		 *                                                      Default true (all registered block types supported).
		 * @param WP_Block_Editor_Context $block_editor_context The current block editor context.
		 */
		$allowed_block_types = apply_filters( 'allowed_block_types_all', $allowed_block_types, $block_editor_context );
	
		if ( ! empty( $block_editor_context->post ) ) {
			$post = $block_editor_context->post;
	
			/**
			 * Filters the allowed block types for the editor.
			 *
			 * @since 5.0.0
			 * @deprecated 5.8.0 Use the {@see 'allowed_block_types_all'} filter instead.
			 *
			 * @param bool|string[] $allowed_block_types Array of block type slugs, or boolean to enable/disable all.
			 *                                           Default true (all registered block types supported)
			 * @param WP_Post       $post                The post resource data.
			 */
			$allowed_block_types = apply_filters_deprecated( 'allowed_block_types', array( $allowed_block_types, $post ), '5.8.0', 'allowed_block_types_all' );
		}
	
		return $allowed_block_types;
	}
endif;

