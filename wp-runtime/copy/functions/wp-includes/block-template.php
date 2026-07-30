<?php

// ------------------auto-generated---------------------

// wp-includes/block-template.php (WP 7.0.2)
if( ! function_exists( '_block_template_add_skip_link' ) ) :
	function _block_template_add_skip_link( string $template_html ): string {
		// Anonymous subclass of WP_HTML_Tag_Processor to access protected bookmark spans.
		$processor = new class( $template_html ) extends WP_HTML_Tag_Processor {
			/**
			 * Inserts text before the current token.
			 *
			 * @param string $text Text to insert.
			 */
			public function insert_before( string $text ) {
				$this->set_bookmark( 'here' );
				$this->lexical_updates[] = new WP_HTML_Text_Replacement( $this->bookmarks['here']->start, 0, $text );
			}
		};
	
		// Find and bookmark the first DIV.wp-site-blocks.
		if (
			! $processor->next_tag(
				array(
					'tag_name'   => 'DIV',
					'class_name' => 'wp-site-blocks',
				)
			)
		) {
			return $template_html;
		}
		$processor->set_bookmark( 'skip_link_insertion_point' );
	
		// Ensure the MAIN element has an ID.
		if ( ! $processor->next_tag( 'MAIN' ) ) {
			return $template_html;
		}
	
		$skip_link_target_id = $processor->get_attribute( 'id' );
		if ( ! is_string( $skip_link_target_id ) || '' === $skip_link_target_id ) {
			$skip_link_target_id = 'wp--skip-link--target';
			$processor->set_attribute( 'id', $skip_link_target_id );
		}
	
		// Seek back to the bookmarked insertion point.
		$processor->seek( 'skip_link_insertion_point' );
	
		$skip_link = sprintf(
			'<a class="skip-link screen-reader-text" id="wp-skip-link" href="%s">%s</a>',
			esc_url( '#' . $skip_link_target_id ),
			/* translators: Hidden accessibility text. */
			esc_html__( 'Skip to content' )
		);
		$processor->insert_before( $skip_link );
	
		return $processor->get_updated_html();
	}
endif;

// wp-includes/block-template.php (WP 7.0.2)
if( ! function_exists( '_block_template_viewport_meta_tag' ) ) :
	function _block_template_viewport_meta_tag() {
		echo '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";
	}
endif;

// wp-includes/block-template.php (WP 7.0.2)
if( ! function_exists( '_strip_template_file_suffix' ) ) :
	function _strip_template_file_suffix( $template_file ) {
		return preg_replace( '/\.(php|html)$/', '', $template_file );
	}
endif;

// wp-includes/block-template.php (WP 7.0.2)
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

