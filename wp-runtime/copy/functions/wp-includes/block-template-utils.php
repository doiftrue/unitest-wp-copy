<?php

// ------------------auto-generated---------------------

// wp-includes/block-template-utils.php (WP 6.7.5)
if( ! function_exists( '_inject_theme_attribute_in_template_part_block' ) ) :
	function _inject_theme_attribute_in_template_part_block( &$block ) {
		if (
			'core/template-part' === $block['blockName'] &&
			! isset( $block['attrs']['theme'] )
		) {
			$block['attrs']['theme'] = get_stylesheet();
		}
	}
endif;

// wp-includes/block-template-utils.php (WP 6.7.5)
if( ! function_exists( '_remove_theme_attribute_from_template_part_block' ) ) :
	function _remove_theme_attribute_from_template_part_block( &$block ) {
		if (
			'core/template-part' === $block['blockName'] &&
			isset( $block['attrs']['theme'] )
		) {
			unset( $block['attrs']['theme'] );
		}
	}
endif;

// wp-includes/block-template-utils.php (WP 6.7.5)
if( ! function_exists( 'get_template_hierarchy' ) ) :
	function get_template_hierarchy( $slug, $is_custom = false, $template_prefix = '' ) {
		if ( 'index' === $slug ) {
			/** This filter is documented in wp-includes/template.php */
			return apply_filters( 'index_template_hierarchy', array( 'index' ) );
		}
		if ( $is_custom ) {
			/** This filter is documented in wp-includes/template.php */
			return apply_filters( 'page_template_hierarchy', array( 'page', 'singular', 'index' ) );
		}
		if ( 'front-page' === $slug ) {
			/** This filter is documented in wp-includes/template.php */
			return apply_filters( 'frontpage_template_hierarchy', array( 'front-page', 'home', 'index' ) );
		}
	
		$matches = array();
	
		$template_hierarchy = array( $slug );
		// Most default templates don't have `$template_prefix` assigned.
		if ( ! empty( $template_prefix ) ) {
			list( $type ) = explode( '-', $template_prefix );
			// We need these checks because we always add the `$slug` above.
			if ( ! in_array( $template_prefix, array( $slug, $type ), true ) ) {
				$template_hierarchy[] = $template_prefix;
			}
			if ( $slug !== $type ) {
				$template_hierarchy[] = $type;
			}
		} elseif ( preg_match( '/^(author|category|archive|tag|page)-.+$/', $slug, $matches ) ) {
			$template_hierarchy[] = $matches[1];
		} elseif ( preg_match( '/^(taxonomy|single)-(.+)$/', $slug, $matches ) ) {
			$type           = $matches[1];
			$slug_remaining = $matches[2];
	
			$items = 'single' === $type ? get_post_types() : get_taxonomies();
			foreach ( $items as $item ) {
				if ( ! str_starts_with( $slug_remaining, $item ) ) {
						continue;
				}
	
				// If $slug_remaining is equal to $post_type or $taxonomy we have
				// the single-$post_type template or the taxonomy-$taxonomy template.
				if ( $slug_remaining === $item ) {
					$template_hierarchy[] = $type;
					break;
				}
	
				// If $slug_remaining is single-$post_type-$slug template.
				if ( strlen( $slug_remaining ) > strlen( $item ) + 1 ) {
					$template_hierarchy[] = "$type-$item";
					$template_hierarchy[] = $type;
					break;
				}
			}
		}
		// Handle `archive` template.
		if (
			str_starts_with( $slug, 'author' ) ||
			str_starts_with( $slug, 'taxonomy' ) ||
			str_starts_with( $slug, 'category' ) ||
			str_starts_with( $slug, 'tag' ) ||
			'date' === $slug
		) {
			$template_hierarchy[] = 'archive';
		}
		// Handle `single` template.
		if ( 'attachment' === $slug ) {
			$template_hierarchy[] = 'single';
		}
		// Handle `singular` template.
		if (
			str_starts_with( $slug, 'single' ) ||
			str_starts_with( $slug, 'page' ) ||
			'attachment' === $slug
		) {
			$template_hierarchy[] = 'singular';
		}
		$template_hierarchy[] = 'index';
	
		$template_type = '';
		if ( ! empty( $template_prefix ) ) {
			list( $template_type ) = explode( '-', $template_prefix );
		} else {
			list( $template_type ) = explode( '-', $slug );
		}
		$valid_template_types = array( '404', 'archive', 'attachment', 'author', 'category', 'date', 'embed', 'frontpage', 'home', 'index', 'page', 'paged', 'privacypolicy', 'search', 'single', 'singular', 'tag', 'taxonomy' );
		if ( in_array( $template_type, $valid_template_types, true ) ) {
			/** This filter is documented in wp-includes/template.php */
			return apply_filters( "{$template_type}_template_hierarchy", $template_hierarchy );
		}
		return $template_hierarchy;
	}
endif;

// wp-includes/block-template-utils.php (WP 6.7.5)
if( ! function_exists( 'get_allowed_block_template_part_areas' ) ) :
	function get_allowed_block_template_part_areas() {
		$default_area_definitions = array(
			array(
				'area'        => WP_TEMPLATE_PART_AREA_UNCATEGORIZED,
				'label'       => _x( 'General', 'template part area' ),
				'description' => __(
					'General templates often perform a specific role like displaying post content, and are not tied to any particular area.'
				),
				'icon'        => 'layout',
				'area_tag'    => 'div',
			),
			array(
				'area'        => WP_TEMPLATE_PART_AREA_HEADER,
				'label'       => _x( 'Header', 'template part area' ),
				'description' => __(
					'The Header template defines a page area that typically contains a title, logo, and main navigation.'
				),
				'icon'        => 'header',
				'area_tag'    => 'header',
			),
			array(
				'area'        => WP_TEMPLATE_PART_AREA_FOOTER,
				'label'       => _x( 'Footer', 'template part area' ),
				'description' => __(
					'The Footer template defines a page area that typically contains site credits, social links, or any other combination of blocks.'
				),
				'icon'        => 'footer',
				'area_tag'    => 'footer',
			),
		);
	
		/**
		 * Filters the list of allowed template part area values.
		 *
		 * @since 5.9.0
		 *
		 * @param array[] $default_area_definitions {
		 *     The allowed template part area values.
		 *
		 *     @type array ...$0 {
		 *         Data for the template part area.
		 *
		 *         @type string $area        Template part area name.
		 *         @type string $label       Template part area label.
		 *         @type string $description Template part area description.
		 *         @type string $icon        Template part area icon.
		 *         @type string $area_tag    Template part area tag.
		 *     }
		 * }
		 */
		return apply_filters( 'default_wp_template_part_areas', $default_area_definitions );
	}
endif;

// wp-includes/block-template-utils.php (WP 6.7.5)
if( ! function_exists( '_filter_block_template_part_area' ) ) :
	function _filter_block_template_part_area( $type ) {
		$allowed_areas = array_map(
			static function ( $item ) {
				return $item['area'];
			},
			get_allowed_block_template_part_areas()
		);
		if ( in_array( $type, $allowed_areas, true ) ) {
			return $type;
		}
	
		$warning_message = sprintf(
			/* translators: %1$s: Template area type, %2$s: the uncategorized template area value. */
			__( '"%1$s" is not a supported wp_template_part area value and has been added as "%2$s".' ),
			$type,
			WP_TEMPLATE_PART_AREA_UNCATEGORIZED
		);
		wp_trigger_error( __FUNCTION__, $warning_message );
		return WP_TEMPLATE_PART_AREA_UNCATEGORIZED;
	}
endif;

// wp-includes/block-template-utils.php (WP 6.7.5)
if( ! function_exists( 'get_default_block_template_types' ) ) :
	function get_default_block_template_types() {
		$default_template_types = array(
			'index'          => array(
				'title'       => _x( 'Index', 'Template name' ),
				'description' => __( 'Used as a fallback template for all pages when a more specific template is not defined.' ),
			),
			'home'           => array(
				'title'       => _x( 'Blog Home', 'Template name' ),
				'description' => __( 'Displays the latest posts as either the site homepage or as the "Posts page" as defined under reading settings. If it exists, the Front Page template overrides this template when posts are shown on the homepage.' ),
			),
			'front-page'     => array(
				'title'       => _x( 'Front Page', 'Template name' ),
				'description' => __( 'Displays your site\'s homepage, whether it is set to display latest posts or a static page. The Front Page template takes precedence over all templates.' ),
			),
			'singular'       => array(
				'title'       => _x( 'Single Entries', 'Template name' ),
				'description' => __( 'Displays any single entry, such as a post or a page. This template will serve as a fallback when a more specific template (e.g. Single Post, Page, or Attachment) cannot be found.' ),
			),
			'single'         => array(
				'title'       => _x( 'Single Posts', 'Template name' ),
				'description' => __( 'Displays a single post on your website unless a custom template has been applied to that post or a dedicated template exists.' ),
			),
			'page'           => array(
				'title'       => _x( 'Pages', 'Template name' ),
				'description' => __( 'Displays a static page unless a custom template has been applied to that page or a dedicated template exists.' ),
			),
			'archive'        => array(
				'title'       => _x( 'All Archives', 'Template name' ),
				'description' => __( 'Displays any archive, including posts by a single author, category, tag, taxonomy, custom post type, and date. This template will serve as a fallback when more specific templates (e.g. Category or Tag) cannot be found.' ),
			),
			'author'         => array(
				'title'       => _x( 'Author Archives', 'Template name' ),
				'description' => __( 'Displays a single author\'s post archive. This template will serve as a fallback when a more specific template (e.g. Author: Admin) cannot be found.' ),
			),
			'category'       => array(
				'title'       => _x( 'Category Archives', 'Template name' ),
				'description' => __( 'Displays a post category archive. This template will serve as a fallback when a more specific template (e.g. Category: Recipes) cannot be found.' ),
			),
			'taxonomy'       => array(
				'title'       => _x( 'Taxonomy', 'Template name' ),
				'description' => __( 'Displays a custom taxonomy archive. Like categories and tags, taxonomies have terms which you use to classify things. For example: a taxonomy named "Art" can have multiple terms, such as "Modern" and "18th Century." This template will serve as a fallback when a more specific template (e.g. Taxonomy: Art) cannot be found.' ),
			),
			'date'           => array(
				'title'       => _x( 'Date Archives', 'Template name' ),
				'description' => __( 'Displays a post archive when a specific date is visited (e.g., example.com/2023/).' ),
			),
			'tag'            => array(
				'title'       => _x( 'Tag Archives', 'Template name' ),
				'description' => __( 'Displays a post tag archive. This template will serve as a fallback when a more specific template (e.g. Tag: Pizza) cannot be found.' ),
			),
			'attachment'     => array(
				'title'       => __( 'Attachment Pages' ),
				'description' => __( 'Displays when a visitor views the dedicated page that exists for any media attachment.' ),
			),
			'search'         => array(
				'title'       => _x( 'Search Results', 'Template name' ),
				'description' => __( 'Displays when a visitor performs a search on your website.' ),
			),
			'privacy-policy' => array(
				'title'       => __( 'Privacy Policy' ),
				'description' => __( 'Displays your site\'s Privacy Policy page.' ),
			),
			'404'            => array(
				'title'       => _x( 'Page: 404', 'Template name' ),
				'description' => __( 'Displays when a visitor views a non-existent page, such as a dead link or a mistyped URL.' ),
			),
		);
	
		/**
		 * Filters the list of default template types.
		 *
		 * @since 5.9.0
		 *
		 * @param array[] $default_template_types {
		 *     The default template types.
		 *
		 *     @type array ...$0 {
		 *         Data for the template type.
		 *
		 *         @type string $title       Template type title.
		 *         @type string $description Template type description.
		 *    }
		 * }
		 */
		return apply_filters( 'default_template_types', $default_template_types );
	}
endif;

// wp-includes/block-template-utils.php (WP 6.7.5)
if( ! function_exists( '_flatten_blocks' ) ) :
	function _flatten_blocks( &$blocks ) {
		$all_blocks = array();
		$queue      = array();
		foreach ( $blocks as &$block ) {
			$queue[] = &$block;
		}
	
		while ( count( $queue ) > 0 ) {
			$block = &$queue[0];
			array_shift( $queue );
			$all_blocks[] = &$block;
	
			if ( ! empty( $block['innerBlocks'] ) ) {
				foreach ( $block['innerBlocks'] as &$inner_block ) {
					$queue[] = &$inner_block;
				}
			}
		}
	
		return $all_blocks;
	}
endif;

