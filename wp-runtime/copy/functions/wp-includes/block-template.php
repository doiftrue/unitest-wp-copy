<?php

// ------------------auto-generated---------------------

// wp-includes/block-template.php (WP 6.5.8)
if( ! function_exists( '_block_template_viewport_meta_tag' ) ) :
	function _block_template_viewport_meta_tag() {
		echo '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";
	}
endif;

// wp-includes/block-template.php (WP 6.5.8)
if( ! function_exists( '_strip_template_file_suffix' ) ) :
	function _strip_template_file_suffix( $template_file ) {
		return preg_replace( '/\.(php|html)$/', '', $template_file );
	}
endif;

// wp-includes/block-template.php (WP 6.5.8)
if( ! function_exists( '_block_template_render_without_post_block_context' ) ) :
	function _block_template_render_without_post_block_context( $context ) {
		/*
		 * When loading a template directly and not through a page that resolves it,
		 * the top-level post ID and type context get set to that of the template.
		 * Templates are just the structure of a site, and they should not be available
		 * as post context because blocks like Post Content would recurse infinitely.
		 */
		if ( isset( $context['postType'] ) && 'wp_template' === $context['postType'] ) {
			unset( $context['postId'] );
			unset( $context['postType'] );
		}
	
		return $context;
	}
endif;

