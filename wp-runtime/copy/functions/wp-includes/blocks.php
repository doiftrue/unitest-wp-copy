<?php

// ------------------auto-generated---------------------

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'remove_block_asset_path_prefix' ) ) :
	function remove_block_asset_path_prefix( $asset_handle_or_path ) {
		$path_prefix = 'file:';
		if ( ! str_starts_with( $asset_handle_or_path, $path_prefix ) ) {
			return $asset_handle_or_path;
		}
		$path = substr(
			$asset_handle_or_path,
			strlen( $path_prefix )
		);
		if ( str_starts_with( $path, './' ) ) {
			$path = substr( $path, 2 );
		}
		return $path;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'generate_block_asset_handle' ) ) :
	function generate_block_asset_handle( $block_name, $field_name, $index = 0 ) {
		if ( str_starts_with( $block_name, 'core/' ) ) {
			$asset_handle = str_replace( 'core/', 'wp-block-', $block_name );
			if ( str_starts_with( $field_name, 'editor' ) ) {
				$asset_handle .= '-editor';
			}
			if ( str_starts_with( $field_name, 'view' ) ) {
				$asset_handle .= '-view';
			}
			if ( str_ends_with( strtolower( $field_name ), 'scriptmodule' ) ) {
				$asset_handle .= '-script-module';
			}
			if ( $index > 0 ) {
				$asset_handle .= '-' . ( $index + 1 );
			}
			return $asset_handle;
		}
	
		$field_mappings = array(
			'editorScript'     => 'editor-script',
			'editorStyle'      => 'editor-style',
			'script'           => 'script',
			'style'            => 'style',
			'viewScript'       => 'view-script',
			'viewScriptModule' => 'view-script-module',
			'viewStyle'        => 'view-style',
		);
		$asset_handle   = str_replace( '/', '-', $block_name ) .
			'-' . $field_mappings[ $field_name ];
		if ( $index > 0 ) {
			$asset_handle .= '-' . ( $index + 1 );
		}
		return $asset_handle;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'unregister_block_type' ) ) :
	function unregister_block_type( $name ) {
		return WP_Block_Type_Registry::get_instance()->unregister( $name );
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'insert_hooked_blocks' ) ) :
	function insert_hooked_blocks( &$parsed_anchor_block, $relative_position, $hooked_blocks, $context ) {
		$anchor_block_type  = $parsed_anchor_block['blockName'];
		$hooked_block_types = isset( $hooked_blocks[ $anchor_block_type ][ $relative_position ] )
			? $hooked_blocks[ $anchor_block_type ][ $relative_position ]
			: array();
	
		/**
		 * Filters the list of hooked block types for a given anchor block type and relative position.
		 *
		 * @since 6.4.0
		 *
		 * @param string[]                        $hooked_block_types The list of hooked block types.
		 * @param string                          $relative_position  The relative position of the hooked blocks.
		 *                                                            Can be one of 'before', 'after', 'first_child', or 'last_child'.
		 * @param string                          $anchor_block_type  The anchor block type.
		 * @param WP_Block_Template|WP_Post|array $context            The block template, template part, `wp_navigation` post type,
		 *                                                            or pattern that the anchor block belongs to.
		 */
		$hooked_block_types = apply_filters( 'hooked_block_types', $hooked_block_types, $relative_position, $anchor_block_type, $context );
	
		$markup = '';
		foreach ( $hooked_block_types as $hooked_block_type ) {
			$parsed_hooked_block = array(
				'blockName'    => $hooked_block_type,
				'attrs'        => array(),
				'innerBlocks'  => array(),
				'innerContent' => array(),
			);
	
			/**
			 * Filters the parsed block array for a given hooked block.
			 *
			 * @since 6.5.0
			 *
			 * @param array|null                      $parsed_hooked_block The parsed block array for the given hooked block type, or null to suppress the block.
			 * @param string                          $hooked_block_type   The hooked block type name.
			 * @param string                          $relative_position   The relative position of the hooked block.
			 * @param array                           $parsed_anchor_block The anchor block, in parsed block array format.
			 * @param WP_Block_Template|WP_Post|array $context             The block template, template part, `wp_navigation` post type,
			 *                                                             or pattern that the anchor block belongs to.
			 */
			$parsed_hooked_block = apply_filters( 'hooked_block', $parsed_hooked_block, $hooked_block_type, $relative_position, $parsed_anchor_block, $context );
	
			/**
			 * Filters the parsed block array for a given hooked block.
			 *
			 * The dynamic portion of the hook name, `$hooked_block_type`, refers to the block type name of the specific hooked block.
			 *
			 * @since 6.5.0
			 *
			 * @param array|null                      $parsed_hooked_block The parsed block array for the given hooked block type, or null to suppress the block.
			 * @param string                          $hooked_block_type   The hooked block type name.
			 * @param string                          $relative_position   The relative position of the hooked block.
			 * @param array                           $parsed_anchor_block The anchor block, in parsed block array format.
			 * @param WP_Block_Template|WP_Post|array $context             The block template, template part, `wp_navigation` post type,
			 *                                                             or pattern that the anchor block belongs to.
			 */
			$parsed_hooked_block = apply_filters( "hooked_block_{$hooked_block_type}", $parsed_hooked_block, $hooked_block_type, $relative_position, $parsed_anchor_block, $context );
	
			if ( null === $parsed_hooked_block ) {
				continue;
			}
	
			// It's possible that the filter returned a block of a different type, so we explicitly
			// look for the original `$hooked_block_type` in the `ignoredHookedBlocks` metadata.
			if (
				! isset( $parsed_anchor_block['attrs']['metadata']['ignoredHookedBlocks'] ) ||
				! in_array( $hooked_block_type, $parsed_anchor_block['attrs']['metadata']['ignoredHookedBlocks'], true )
			) {
				$markup .= serialize_block( $parsed_hooked_block );
			}
		}
	
		return $markup;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'set_ignored_hooked_blocks_metadata' ) ) :
	function set_ignored_hooked_blocks_metadata( &$parsed_anchor_block, $relative_position, $hooked_blocks, $context ) {
		$anchor_block_type  = $parsed_anchor_block['blockName'];
		$hooked_block_types = isset( $hooked_blocks[ $anchor_block_type ][ $relative_position ] )
			? $hooked_blocks[ $anchor_block_type ][ $relative_position ]
			: array();
	
		/** This filter is documented in wp-includes/blocks.php */
		$hooked_block_types = apply_filters( 'hooked_block_types', $hooked_block_types, $relative_position, $anchor_block_type, $context );
		if ( empty( $hooked_block_types ) ) {
			return '';
		}
	
		foreach ( $hooked_block_types as $index => $hooked_block_type ) {
			$parsed_hooked_block = array(
				'blockName'    => $hooked_block_type,
				'attrs'        => array(),
				'innerBlocks'  => array(),
				'innerContent' => array(),
			);
	
			/** This filter is documented in wp-includes/blocks.php */
			$parsed_hooked_block = apply_filters( 'hooked_block', $parsed_hooked_block, $hooked_block_type, $relative_position, $parsed_anchor_block, $context );
	
			/** This filter is documented in wp-includes/blocks.php */
			$parsed_hooked_block = apply_filters( "hooked_block_{$hooked_block_type}", $parsed_hooked_block, $hooked_block_type, $relative_position, $parsed_anchor_block, $context );
	
			if ( null === $parsed_hooked_block ) {
				unset( $hooked_block_types[ $index ] );
			}
		}
	
		$previously_ignored_hooked_blocks = isset( $parsed_anchor_block['attrs']['metadata']['ignoredHookedBlocks'] )
			? $parsed_anchor_block['attrs']['metadata']['ignoredHookedBlocks']
			: array();
	
		$parsed_anchor_block['attrs']['metadata']['ignoredHookedBlocks'] = array_unique(
			array_merge(
				$previously_ignored_hooked_blocks,
				$hooked_block_types
			)
		);
	
		// Markup for the hooked blocks has already been created (in `insert_hooked_blocks`).
		return '';
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'make_after_block_visitor' ) ) :
	function make_after_block_visitor( $hooked_blocks, $context, $callback = 'insert_hooked_blocks' ) {
		/**
		 * Injects hooked blocks after the given block, and returns the serialized markup.
		 *
		 * Append the markup for any blocks hooked `after` the given block and as its parent's
		 * `last_child`, respectively, to the serialized markup for the given block.
		 *
		 * @param array $block        The block to inject the hooked blocks after. Passed by reference.
		 * @param array $parent_block The parent block of the given block. Passed by reference. Default null.
		 * @param array $next         The next sibling block of the given block. Default null.
		 * @return string The serialized markup for the given block, with the markup for any hooked blocks appended to it.
		 */
		return function ( &$block, &$parent_block = null, $next = null ) use ( $hooked_blocks, $context, $callback ) {
			$markup = call_user_func_array(
				$callback,
				array( &$block, 'after', $hooked_blocks, $context )
			);
	
			if ( $parent_block && ! $next ) {
				// Candidate for last-child insertion.
				$markup .= call_user_func_array(
					$callback,
					array( &$parent_block, 'last_child', $hooked_blocks, $context )
				);
			}
	
			return $markup;
		};
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'serialize_block_attributes' ) ) :
	function serialize_block_attributes( $block_attributes ) {
		$encoded_attributes = wp_json_encode( $block_attributes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		$encoded_attributes = preg_replace( '/--/', '\\u002d\\u002d', $encoded_attributes );
		$encoded_attributes = preg_replace( '/</', '\\u003c', $encoded_attributes );
		$encoded_attributes = preg_replace( '/>/', '\\u003e', $encoded_attributes );
		$encoded_attributes = preg_replace( '/&/', '\\u0026', $encoded_attributes );
		// Regex: /\\"/
		$encoded_attributes = preg_replace( '/\\\\"/', '\\u0022', $encoded_attributes );
	
		return $encoded_attributes;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'strip_core_block_namespace' ) ) :
	function strip_core_block_namespace( $block_name = null ) {
		if ( is_string( $block_name ) && str_starts_with( $block_name, 'core/' ) ) {
			return substr( $block_name, 5 );
		}
	
		return $block_name;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'get_comment_delimited_block_content' ) ) :
	function get_comment_delimited_block_content( $block_name, $block_attributes, $block_content ) {
		if ( is_null( $block_name ) ) {
			return $block_content;
		}
	
		$serialized_block_name = strip_core_block_namespace( $block_name );
		$serialized_attributes = empty( $block_attributes ) ? '' : serialize_block_attributes( $block_attributes ) . ' ';
	
		if ( empty( $block_content ) ) {
			return sprintf( '<!-- wp:%s %s/-->', $serialized_block_name, $serialized_attributes );
		}
	
		return sprintf(
			'<!-- wp:%s %s-->%s<!-- /wp:%s -->',
			$serialized_block_name,
			$serialized_attributes,
			$block_content,
			$serialized_block_name
		);
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'serialize_block' ) ) :
	function serialize_block( $block ) {
		$block_content = '';
	
		$index = 0;
		foreach ( $block['innerContent'] as $chunk ) {
			$block_content .= is_string( $chunk ) ? $chunk : serialize_block( $block['innerBlocks'][ $index++ ] );
		}
	
		if ( ! is_array( $block['attrs'] ) ) {
			$block['attrs'] = array();
		}
	
		return get_comment_delimited_block_content(
			$block['blockName'],
			$block['attrs'],
			$block_content
		);
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'serialize_blocks' ) ) :
	function serialize_blocks( $blocks ) {
		return implode( '', array_map( 'serialize_block', $blocks ) );
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'traverse_and_serialize_block' ) ) :
	function traverse_and_serialize_block( $block, $pre_callback = null, $post_callback = null ) {
		$block_content = '';
		$block_index   = 0;
	
		foreach ( $block['innerContent'] as $chunk ) {
			if ( is_string( $chunk ) ) {
				$block_content .= $chunk;
			} else {
				$inner_block = $block['innerBlocks'][ $block_index ];
	
				if ( is_callable( $pre_callback ) ) {
					$prev = 0 === $block_index
						? null
						: $block['innerBlocks'][ $block_index - 1 ];
	
					$block_content .= call_user_func_array(
						$pre_callback,
						array( &$inner_block, &$block, $prev )
					);
				}
	
				if ( is_callable( $post_callback ) ) {
					$next = count( $block['innerBlocks'] ) - 1 === $block_index
						? null
						: $block['innerBlocks'][ $block_index + 1 ];
	
					$post_markup = call_user_func_array(
						$post_callback,
						array( &$inner_block, &$block, $next )
					);
				}
	
				$block_content .= traverse_and_serialize_block( $inner_block, $pre_callback, $post_callback );
				$block_content .= isset( $post_markup ) ? $post_markup : '';
	
				++$block_index;
			}
		}
	
		if ( ! is_array( $block['attrs'] ) ) {
			$block['attrs'] = array();
		}
	
		return get_comment_delimited_block_content(
			$block['blockName'],
			$block['attrs'],
			$block_content
		);
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'traverse_and_serialize_blocks' ) ) :
	function traverse_and_serialize_blocks( $blocks, $pre_callback = null, $post_callback = null ) {
		$result       = '';
		$parent_block = null; // At the top level, there is no parent block to pass to the callbacks; yet the callbacks expect a reference.
	
		foreach ( $blocks as $index => $block ) {
			if ( is_callable( $pre_callback ) ) {
				$prev = 0 === $index
					? null
					: $blocks[ $index - 1 ];
	
				$result .= call_user_func_array(
					$pre_callback,
					array( &$block, &$parent_block, $prev )
				);
			}
	
			if ( is_callable( $post_callback ) ) {
				$next = count( $blocks ) - 1 === $index
					? null
					: $blocks[ $index + 1 ];
	
				$post_markup = call_user_func_array(
					$post_callback,
					array( &$block, &$parent_block, $next )
				);
			}
	
			$result .= traverse_and_serialize_block( $block, $pre_callback, $post_callback );
			$result .= isset( $post_markup ) ? $post_markup : '';
		}
	
		return $result;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'filter_block_content' ) ) :
	function filter_block_content( $text, $allowed_html = 'post', $allowed_protocols = array() ) {
		$result = '';
	
		if ( str_contains( $text, '<!--' ) && str_contains( $text, '--->' ) ) {
			$text = preg_replace_callback( '%<!--(.*?)--->%', '_filter_block_content_callback', $text );
		}
	
		$blocks = parse_blocks( $text );
		foreach ( $blocks as $block ) {
			$block   = filter_block_kses( $block, $allowed_html, $allowed_protocols );
			$result .= serialize_block( $block );
		}
	
		return $result;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( '_filter_block_content_callback' ) ) :
	function _filter_block_content_callback( $matches ) {
		return '<!--' . rtrim( $matches[1], '-' ) . '-->';
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'filter_block_kses' ) ) :
	function filter_block_kses( $block, $allowed_html, $allowed_protocols = array() ) {
		$block['attrs'] = filter_block_kses_value( $block['attrs'], $allowed_html, $allowed_protocols, $block );
	
		if ( is_array( $block['innerBlocks'] ) ) {
			foreach ( $block['innerBlocks'] as $i => $inner_block ) {
				$block['innerBlocks'][ $i ] = filter_block_kses( $inner_block, $allowed_html, $allowed_protocols );
			}
		}
	
		return $block;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'filter_block_kses_value' ) ) :
	function filter_block_kses_value( $value, $allowed_html, $allowed_protocols = array(), $block_context = null ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $key => $inner_value ) {
				$filtered_key   = filter_block_kses_value( $key, $allowed_html, $allowed_protocols, $block_context );
				$filtered_value = filter_block_kses_value( $inner_value, $allowed_html, $allowed_protocols, $block_context );
	
				if ( isset( $block_context['blockName'] ) && 'core/template-part' === $block_context['blockName'] ) {
					$filtered_value = filter_block_core_template_part_attributes( $filtered_value, $filtered_key, $allowed_html );
				}
	
				if ( $filtered_key !== $key ) {
					unset( $value[ $key ] );
				}
	
				$value[ $filtered_key ] = $filtered_value;
			}
		} elseif ( is_string( $value ) ) {
			return wp_kses( $value, $allowed_html, $allowed_protocols );
		}
	
		return $value;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'filter_block_core_template_part_attributes' ) ) :
	function filter_block_core_template_part_attributes( $attribute_value, $attribute_name, $allowed_html ) {
		if ( empty( $attribute_value ) || 'tagName' !== $attribute_name ) {
			return $attribute_value;
		}
		if ( ! is_array( $allowed_html ) ) {
			$allowed_html = wp_kses_allowed_html( $allowed_html );
		}
		return isset( $allowed_html[ $attribute_value ] ) ? $attribute_value : '';
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'excerpt_remove_footnotes' ) ) :
	function excerpt_remove_footnotes( $content ) {
		if ( ! str_contains( $content, 'data-fn=' ) ) {
			return $content;
		}
	
		return preg_replace(
			'_<sup data-fn="[^"]+" class="[^"]+">\s*<a href="[^"]+" id="[^"]+">\d+</a>\s*</sup>_',
			'',
			$content
		);
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'parse_blocks' ) ) :
	function parse_blocks( $content ) {
		/**
		 * Filter to allow plugins to replace the server-side block parser.
		 *
		 * @since 5.0.0
		 *
		 * @param string $parser_class Name of block parser class.
		 */
		$parser_class = apply_filters( 'block_parser_class', 'WP_Block_Parser' );
	
		$parser = new $parser_class();
		return $parser->parse( $content );
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( '_restore_wpautop_hook' ) ) :
	function _restore_wpautop_hook( $content ) {
		$current_priority = has_filter( 'the_content', '_restore_wpautop_hook' );
	
		add_filter( 'the_content', 'wpautop', $current_priority - 1 );
		remove_filter( 'the_content', '_restore_wpautop_hook', $current_priority );
	
		return $content;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'register_block_style' ) ) :
	function register_block_style( $block_name, $style_properties ) {
		return WP_Block_Styles_Registry::get_instance()->register( $block_name, $style_properties );
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'unregister_block_style' ) ) :
	function unregister_block_style( $block_name, $block_style_name ) {
		return WP_Block_Styles_Registry::get_instance()->unregister( $block_name, $block_style_name );
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'block_has_support' ) ) :
	function block_has_support( $block_type, $feature, $default_value = false ) {
		$block_support = $default_value;
		if ( $block_type instanceof WP_Block_Type ) {
			if ( is_array( $feature ) && count( $feature ) === 1 ) {
				$feature = $feature[0];
			}
	
			if ( is_array( $feature ) ) {
				$block_support = _wp_array_get( $block_type->supports, $feature, $default_value );
			} elseif ( isset( $block_type->supports[ $feature ] ) ) {
				$block_support = $block_type->supports[ $feature ];
			}
		}
	
		return true === $block_support || is_array( $block_support );
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'wp_migrate_old_typography_shape' ) ) :
	function wp_migrate_old_typography_shape( $metadata ) {
		if ( ! isset( $metadata['supports'] ) ) {
			return $metadata;
		}
	
		$typography_keys = array(
			'__experimentalFontFamily',
			'__experimentalFontStyle',
			'__experimentalFontWeight',
			'__experimentalLetterSpacing',
			'__experimentalTextDecoration',
			'__experimentalTextTransform',
			'fontSize',
			'lineHeight',
		);
	
		foreach ( $typography_keys as $typography_key ) {
			$support_for_key = isset( $metadata['supports'][ $typography_key ] ) ? $metadata['supports'][ $typography_key ] : null;
	
			if ( null !== $support_for_key ) {
				_doing_it_wrong(
					'register_block_type_from_metadata()',
					sprintf(
						/* translators: 1: Block type, 2: Typography supports key, e.g: fontSize, lineHeight, etc. 3: block.json, 4: Old metadata key, 5: New metadata key. */
						__( 'Block "%1$s" is declaring %2$s support in %3$s file under %4$s. %2$s support is now declared under %5$s.' ),
						$metadata['name'],
						"<code>$typography_key</code>",
						'<code>block.json</code>',
						"<code>supports.$typography_key</code>",
						"<code>supports.typography.$typography_key</code>"
					),
					'5.8.0'
				);
	
				_wp_array_set( $metadata['supports'], array( 'typography', $typography_key ), $support_for_key );
				unset( $metadata['supports'][ $typography_key ] );
			}
		}
	
		return $metadata;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'get_query_pagination_arrow' ) ) :
	function get_query_pagination_arrow( $block, $is_next ) {
		$arrow_map = array(
			'none'    => '',
			'arrow'   => array(
				'next'     => '→',
				'previous' => '←',
			),
			'chevron' => array(
				'next'     => '»',
				'previous' => '«',
			),
		);
		if ( ! empty( $block->context['paginationArrow'] ) && array_key_exists( $block->context['paginationArrow'], $arrow_map ) && ! empty( $arrow_map[ $block->context['paginationArrow'] ] ) ) {
			$pagination_type = $is_next ? 'next' : 'previous';
			$arrow_attribute = $block->context['paginationArrow'];
			$arrow           = $arrow_map[ $block->context['paginationArrow'] ][ $pagination_type ];
			$arrow_classes   = "wp-block-query-pagination-$pagination_type-arrow is-arrow-$arrow_attribute";
			return "<span class='$arrow_classes' aria-hidden='true'>$arrow</span>";
		}
		return null;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( 'get_comments_pagination_arrow' ) ) :
	function get_comments_pagination_arrow( $block, $pagination_type = 'next' ) {
		$arrow_map = array(
			'none'    => '',
			'arrow'   => array(
				'next'     => '→',
				'previous' => '←',
			),
			'chevron' => array(
				'next'     => '»',
				'previous' => '«',
			),
		);
		if ( ! empty( $block->context['comments/paginationArrow'] ) && ! empty( $arrow_map[ $block->context['comments/paginationArrow'] ][ $pagination_type ] ) ) {
			$arrow_attribute = $block->context['comments/paginationArrow'];
			$arrow           = $arrow_map[ $block->context['comments/paginationArrow'] ][ $pagination_type ];
			$arrow_classes   = "wp-block-comments-pagination-$pagination_type-arrow is-arrow-$arrow_attribute";
			return "<span class='$arrow_classes' aria-hidden='true'>$arrow</span>";
		}
		return null;
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( '_wp_filter_post_meta_footnotes' ) ) :
	function _wp_filter_post_meta_footnotes( $footnotes ) {
		$footnotes_decoded = json_decode( $footnotes, true );
		if ( ! is_array( $footnotes_decoded ) ) {
			return '';
		}
		$footnotes_sanitized = array();
		foreach ( $footnotes_decoded as $footnote ) {
			if ( ! empty( $footnote['content'] ) && ! empty( $footnote['id'] ) ) {
				$footnotes_sanitized[] = array(
					'id'      => sanitize_key( $footnote['id'] ),
					'content' => wp_unslash( wp_filter_post_kses( wp_slash( $footnote['content'] ) ) ),
				);
			}
		}
		return wp_json_encode( $footnotes_sanitized );
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( '_wp_footnotes_kses_init_filters' ) ) :
	function _wp_footnotes_kses_init_filters() {
		add_filter( 'sanitize_post_meta_footnotes', '_wp_filter_post_meta_footnotes' );
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( '_wp_footnotes_remove_filters' ) ) :
	function _wp_footnotes_remove_filters() {
		remove_filter( 'sanitize_post_meta_footnotes', '_wp_filter_post_meta_footnotes' );
	}
endif;

// wp-includes/blocks.php (WP 6.5.8)
if( ! function_exists( '_wp_footnotes_force_filtered_html_on_import_filter' ) ) :
	function _wp_footnotes_force_filtered_html_on_import_filter( $arg ) {
		// If `force_filtered_html_on_import` is true, we need to init the global styles kses filters.
		if ( $arg ) {
			_wp_footnotes_kses_init_filters();
		}
		return $arg;
	}
endif;

