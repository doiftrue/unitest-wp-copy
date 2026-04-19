<?php

// ------------------auto-generated---------------------

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'get_extended' ) ) :
	function get_extended( $post ) {
		// Match the new style more links.
		if ( preg_match( '/<!--more(.*?)?-->/', $post, $matches ) ) {
			list($main, $extended) = explode( $matches[0], $post, 2 );
			$more_text             = $matches[1];
		} else {
			$main      = $post;
			$extended  = '';
			$more_text = '';
		}
	
		// Leading and trailing whitespace.
		$main      = preg_replace( '/^[\s]*(.*)[\s]*$/', '\\1', $main );
		$extended  = preg_replace( '/^[\s]*(.*)[\s]*$/', '\\1', $extended );
		$more_text = preg_replace( '/^[\s]*(.*)[\s]*$/', '\\1', $more_text );
	
		return array(
			'main'      => $main,
			'extended'  => $extended,
			'more_text' => $more_text,
		);
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'get_post_statuses' ) ) :
	function get_post_statuses() {
		$status = array(
			'draft'   => __( 'Draft' ),
			'pending' => __( 'Pending Review' ),
			'private' => __( 'Private' ),
			'publish' => __( 'Published' ),
		);
	
		return $status;
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'get_page_statuses' ) ) :
	function get_page_statuses() {
		$status = array(
			'draft'   => __( 'Draft' ),
			'private' => __( 'Private' ),
			'publish' => __( 'Published' ),
		);
	
		return $status;
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( '_wp_privacy_statuses' ) ) :
	function _wp_privacy_statuses() {
		return array(
			'request-pending'   => _x( 'Pending', 'request status' ),      // Pending confirmation from user.
			'request-confirmed' => _x( 'Confirmed', 'request status' ),    // User has confirmed the action.
			'request-failed'    => _x( 'Failed', 'request status' ),       // User failed to confirm the action.
			'request-completed' => _x( 'Completed', 'request status' ),    // Admin has handled the request.
		);
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'register_post_status' ) ) :
	function register_post_status( $post_status, $args = array() ) {
		global $wp_post_statuses;
	
		if ( ! is_array( $wp_post_statuses ) ) {
			$wp_post_statuses = array();
		}
	
		// Args prefixed with an underscore are reserved for internal use.
		$defaults = array(
			'label'                     => false,
			'label_count'               => false,
			'exclude_from_search'       => null,
			'_builtin'                  => false,
			'public'                    => null,
			'internal'                  => null,
			'protected'                 => null,
			'private'                   => null,
			'publicly_queryable'        => null,
			'show_in_admin_status_list' => null,
			'show_in_admin_all_list'    => null,
			'date_floating'             => null,
		);
		$args     = wp_parse_args( $args, $defaults );
		$args     = (object) $args;
	
		$post_status = sanitize_key( $post_status );
		$args->name  = $post_status;
	
		// Set various defaults.
		if ( null === $args->public && null === $args->internal && null === $args->protected && null === $args->private ) {
			$args->internal = true;
		}
	
		if ( null === $args->public ) {
			$args->public = false;
		}
	
		if ( null === $args->private ) {
			$args->private = false;
		}
	
		if ( null === $args->protected ) {
			$args->protected = false;
		}
	
		if ( null === $args->internal ) {
			$args->internal = false;
		}
	
		if ( null === $args->publicly_queryable ) {
			$args->publicly_queryable = $args->public;
		}
	
		if ( null === $args->exclude_from_search ) {
			$args->exclude_from_search = $args->internal;
		}
	
		if ( null === $args->show_in_admin_all_list ) {
			$args->show_in_admin_all_list = ! $args->internal;
		}
	
		if ( null === $args->show_in_admin_status_list ) {
			$args->show_in_admin_status_list = ! $args->internal;
		}
	
		if ( null === $args->date_floating ) {
			$args->date_floating = false;
		}
	
		if ( false === $args->label ) {
			$args->label = $post_status;
		}
	
		if ( false === $args->label_count ) {
			// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralSingular,WordPress.WP.I18n.NonSingularStringLiteralPlural
			$args->label_count = _n_noop( $args->label, $args->label );
		}
	
		$wp_post_statuses[ $post_status ] = $args;
	
		return $args;
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'get_post_status_object' ) ) :
	function get_post_status_object( $post_status ) {
		global $wp_post_statuses;
	
		if ( ! is_string( $post_status ) || empty( $wp_post_statuses[ $post_status ] ) ) {
			return null;
		}
	
		return $wp_post_statuses[ $post_status ];
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'get_post_stati' ) ) :
	function get_post_stati( $args = array(), $output = 'names', $operator = 'and' ) {
		global $wp_post_statuses;
	
		$field = ( 'names' === $output ) ? 'name' : false;
	
		return wp_filter_object_list( $wp_post_statuses, $args, $operator, $field );
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'get_post_type_capabilities' ) ) :
	function get_post_type_capabilities( $args ) {
		if ( ! is_array( $args->capability_type ) ) {
			$args->capability_type = array( $args->capability_type, $args->capability_type . 's' );
		}
	
		// Singular base for meta capabilities, plural base for primitive capabilities.
		list( $singular_base, $plural_base ) = $args->capability_type;
	
		$default_capabilities = array(
			// Meta capabilities.
			'edit_post'          => 'edit_' . $singular_base,
			'read_post'          => 'read_' . $singular_base,
			'delete_post'        => 'delete_' . $singular_base,
			// Primitive capabilities used outside of map_meta_cap():
			'edit_posts'         => 'edit_' . $plural_base,
			'edit_others_posts'  => 'edit_others_' . $plural_base,
			'delete_posts'       => 'delete_' . $plural_base,
			'publish_posts'      => 'publish_' . $plural_base,
			'read_private_posts' => 'read_private_' . $plural_base,
		);
	
		// Primitive capabilities used within map_meta_cap():
		if ( $args->map_meta_cap ) {
			$default_capabilities_for_mapping = array(
				'read'                   => 'read',
				'delete_private_posts'   => 'delete_private_' . $plural_base,
				'delete_published_posts' => 'delete_published_' . $plural_base,
				'delete_others_posts'    => 'delete_others_' . $plural_base,
				'edit_private_posts'     => 'edit_private_' . $plural_base,
				'edit_published_posts'   => 'edit_published_' . $plural_base,
			);
			$default_capabilities             = array_merge( $default_capabilities, $default_capabilities_for_mapping );
		}
	
		$capabilities = array_merge( $default_capabilities, $args->capabilities );
	
		// Post creation capability simply maps to edit_posts by default:
		if ( ! isset( $capabilities['create_posts'] ) ) {
			$capabilities['create_posts'] = $capabilities['edit_posts'];
		}
	
		// Remember meta capabilities for future reference.
		if ( $args->map_meta_cap ) {
			_post_type_meta_capabilities( $capabilities );
		}
	
		return (object) $capabilities;
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( '_post_type_meta_capabilities' ) ) :
	function _post_type_meta_capabilities( $capabilities = null ) {
		global $post_type_meta_caps;
	
		foreach ( $capabilities as $core => $custom ) {
			if ( in_array( $core, array( 'read_post', 'delete_post', 'edit_post' ), true ) ) {
				$post_type_meta_caps[ $custom ] = $core;
			}
		}
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( '_get_custom_object_labels' ) ) :
	function _get_custom_object_labels( $data_object, $nohier_vs_hier_defaults ) {
		$data_object->labels = (array) $data_object->labels;
	
		if ( isset( $data_object->label ) && empty( $data_object->labels['name'] ) ) {
			$data_object->labels['name'] = $data_object->label;
		}
	
		if ( ! isset( $data_object->labels['singular_name'] ) && isset( $data_object->labels['name'] ) ) {
			$data_object->labels['singular_name'] = $data_object->labels['name'];
		}
	
		if ( ! isset( $data_object->labels['name_admin_bar'] ) ) {
			$data_object->labels['name_admin_bar'] =
				isset( $data_object->labels['singular_name'] )
				? $data_object->labels['singular_name']
				: $data_object->name;
		}
	
		if ( ! isset( $data_object->labels['menu_name'] ) && isset( $data_object->labels['name'] ) ) {
			$data_object->labels['menu_name'] = $data_object->labels['name'];
		}
	
		if ( ! isset( $data_object->labels['all_items'] ) && isset( $data_object->labels['menu_name'] ) ) {
			$data_object->labels['all_items'] = $data_object->labels['menu_name'];
		}
	
		if ( ! isset( $data_object->labels['archives'] ) && isset( $data_object->labels['all_items'] ) ) {
			$data_object->labels['archives'] = $data_object->labels['all_items'];
		}
	
		$defaults = array();
		foreach ( $nohier_vs_hier_defaults as $key => $value ) {
			$defaults[ $key ] = $data_object->hierarchical ? $value[1] : $value[0];
		}
	
		$labels              = array_merge( $defaults, $data_object->labels );
		$data_object->labels = (object) $data_object->labels;
	
		return (object) $labels;
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'add_post_type_support' ) ) :
	function add_post_type_support( $post_type, $feature, ...$args ) {
		global $_wp_post_type_features;
	
		$features = (array) $feature;
		foreach ( $features as $feature ) {
			if ( $args ) {
				$_wp_post_type_features[ $post_type ][ $feature ] = $args;
			} else {
				$_wp_post_type_features[ $post_type ][ $feature ] = true;
			}
		}
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'remove_post_type_support' ) ) :
	function remove_post_type_support( $post_type, $feature ) {
		global $_wp_post_type_features;
	
		unset( $_wp_post_type_features[ $post_type ][ $feature ] );
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'get_all_post_type_supports' ) ) :
	function get_all_post_type_supports( $post_type ) {
		global $_wp_post_type_features;
	
		if ( isset( $_wp_post_type_features[ $post_type ] ) ) {
			return $_wp_post_type_features[ $post_type ];
		}
	
		return array();
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'post_type_supports' ) ) :
	function post_type_supports( $post_type, $feature ) {
		global $_wp_post_type_features;
	
		return ( isset( $_wp_post_type_features[ $post_type ][ $feature ] ) );
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'get_post_types_by_support' ) ) :
	function get_post_types_by_support( $feature, $operator = 'and' ) {
		global $_wp_post_type_features;
	
		$features = array_fill_keys( (array) $feature, true );
	
		return array_keys( wp_filter_object_list( $_wp_post_type_features, $features, $operator ) );
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'get_post_mime_types' ) ) :
	function get_post_mime_types() {
		$post_mime_types = array(   // array( adj, noun )
			'image'       => array(
				__( 'Images' ),
				__( 'Manage Images' ),
				/* translators: %s: Number of images. */
				_n_noop(
					'Image <span class="count">(%s)</span>',
					'Images <span class="count">(%s)</span>'
				),
			),
			'audio'       => array(
				_x( 'Audio', 'file type group' ),
				__( 'Manage Audio' ),
				/* translators: %s: Number of audio files. */
				_n_noop(
					'Audio <span class="count">(%s)</span>',
					'Audio <span class="count">(%s)</span>'
				),
			),
			'video'       => array(
				_x( 'Video', 'file type group' ),
				__( 'Manage Video' ),
				/* translators: %s: Number of video files. */
				_n_noop(
					'Video <span class="count">(%s)</span>',
					'Video <span class="count">(%s)</span>'
				),
			),
			'document'    => array(
				__( 'Documents' ),
				__( 'Manage Documents' ),
				/* translators: %s: Number of documents. */
				_n_noop(
					'Document <span class="count">(%s)</span>',
					'Documents <span class="count">(%s)</span>'
				),
			),
			'spreadsheet' => array(
				__( 'Spreadsheets' ),
				__( 'Manage Spreadsheets' ),
				/* translators: %s: Number of spreadsheets. */
				_n_noop(
					'Spreadsheet <span class="count">(%s)</span>',
					'Spreadsheets <span class="count">(%s)</span>'
				),
			),
			'archive'     => array(
				_x( 'Archives', 'file type group' ),
				__( 'Manage Archives' ),
				/* translators: %s: Number of archives. */
				_n_noop(
					'Archive <span class="count">(%s)</span>',
					'Archives <span class="count">(%s)</span>'
				),
			),
		);
	
		$ext_types  = wp_get_ext_types();
		$mime_types = wp_get_mime_types();
	
		foreach ( $post_mime_types as $group => $labels ) {
			if ( in_array( $group, array( 'image', 'audio', 'video' ), true ) ) {
				continue;
			}
	
			if ( ! isset( $ext_types[ $group ] ) ) {
				unset( $post_mime_types[ $group ] );
				continue;
			}
	
			$group_mime_types = array();
			foreach ( $ext_types[ $group ] as $extension ) {
				foreach ( $mime_types as $exts => $mime ) {
					if ( preg_match( '!^(' . $exts . ')$!i', $extension ) ) {
						$group_mime_types[] = $mime;
						break;
					}
				}
			}
			$group_mime_types = implode( ',', array_unique( $group_mime_types ) );
	
			$post_mime_types[ $group_mime_types ] = $labels;
			unset( $post_mime_types[ $group ] );
		}
	
		/**
		 * Filters the default list of post mime types.
		 *
		 * @since 2.5.0
		 *
		 * @param array $post_mime_types Default list of post mime types.
		 */
		return apply_filters( 'post_mime_types', $post_mime_types );
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'wp_match_mime_types' ) ) :
	function wp_match_mime_types( $wildcard_mime_types, $real_mime_types ) {
		$matches = array();
		if ( is_string( $wildcard_mime_types ) ) {
			$wildcard_mime_types = array_map( 'trim', explode( ',', $wildcard_mime_types ) );
		}
		if ( is_string( $real_mime_types ) ) {
			$real_mime_types = array_map( 'trim', explode( ',', $real_mime_types ) );
		}
	
		$patternses = array();
		$wild       = '[-._a-z0-9]*';
	
		foreach ( (array) $wildcard_mime_types as $type ) {
			$mimes = array_map( 'trim', explode( ',', $type ) );
			foreach ( $mimes as $mime ) {
				$regex = str_replace( '__wildcard__', $wild, preg_quote( str_replace( '*', '__wildcard__', $mime ) ) );
	
				$patternses[][ $type ] = "^$regex$";
	
				if ( ! str_contains( $mime, '/' ) ) {
					$patternses[][ $type ] = "^$regex/";
					$patternses[][ $type ] = $regex;
				}
			}
		}
		asort( $patternses );
	
		foreach ( $patternses as $patterns ) {
			foreach ( $patterns as $type => $pattern ) {
				foreach ( (array) $real_mime_types as $real ) {
					if ( preg_match( "#$pattern#", $real )
						&& ( empty( $matches[ $type ] ) || false === array_search( $real, $matches[ $type ], true ) )
					) {
						$matches[ $type ][] = $real;
					}
				}
			}
		}
	
		return $matches;
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'wp_post_mime_type_where' ) ) :
	function wp_post_mime_type_where( $post_mime_types, $table_alias = '' ) {
		$where     = '';
		$wildcards = array( '', '%', '%/%' );
		if ( is_string( $post_mime_types ) ) {
			$post_mime_types = array_map( 'trim', explode( ',', $post_mime_types ) );
		}
	
		$where_clauses = array();
	
		foreach ( (array) $post_mime_types as $mime_type ) {
			$mime_type = preg_replace( '/\s/', '', $mime_type );
			$slashpos  = strpos( $mime_type, '/' );
			if ( false !== $slashpos ) {
				$mime_group    = preg_replace( '/[^-*.a-zA-Z0-9]/', '', substr( $mime_type, 0, $slashpos ) );
				$mime_subgroup = preg_replace( '/[^-*.+a-zA-Z0-9]/', '', substr( $mime_type, $slashpos + 1 ) );
				if ( empty( $mime_subgroup ) ) {
					$mime_subgroup = '*';
				} else {
					$mime_subgroup = str_replace( '/', '', $mime_subgroup );
				}
				$mime_pattern = "$mime_group/$mime_subgroup";
			} else {
				$mime_pattern = preg_replace( '/[^-*.a-zA-Z0-9]/', '', $mime_type );
				if ( ! str_contains( $mime_pattern, '*' ) ) {
					$mime_pattern .= '/*';
				}
			}
	
			$mime_pattern = preg_replace( '/\*+/', '%', $mime_pattern );
	
			if ( in_array( $mime_type, $wildcards, true ) ) {
				return '';
			}
	
			if ( str_contains( $mime_pattern, '%' ) ) {
				$where_clauses[] = empty( $table_alias ) ? "post_mime_type LIKE '$mime_pattern'" : "$table_alias.post_mime_type LIKE '$mime_pattern'";
			} else {
				$where_clauses[] = empty( $table_alias ) ? "post_mime_type = '$mime_pattern'" : "$table_alias.post_mime_type = '$mime_pattern'";
			}
		}
	
		if ( ! empty( $where_clauses ) ) {
			$where = ' AND (' . implode( ' OR ', $where_clauses ) . ') ';
		}
	
		return $where;
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'wp_resolve_post_date' ) ) :
	function wp_resolve_post_date( $post_date = '', $post_date_gmt = '' ) {
		// If the date is empty, set the date to now.
		if ( empty( $post_date ) || '0000-00-00 00:00:00' === $post_date ) {
			if ( empty( $post_date_gmt ) || '0000-00-00 00:00:00' === $post_date_gmt ) {
				$post_date = current_time( 'mysql' );
			} else {
				$post_date = get_date_from_gmt( $post_date_gmt );
			}
		}
	
		// Validate the date.
		preg_match( '/^(\d{4})-(\d{1,2})-(\d{1,2})/', $post_date, $matches );
	
		if ( empty( $matches ) || ! is_array( $matches ) || count( $matches ) < 4 ) {
			return false;
		}
	
		$valid_date = wp_checkdate( $matches[2], $matches[3], $matches[1], $post_date );
	
		if ( ! $valid_date ) {
			return false;
		}
		return $post_date;
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( '_truncate_post_slug' ) ) :
	function _truncate_post_slug( $slug, $length = 200 ) {
		if ( strlen( $slug ) > $length ) {
			$decoded_slug = urldecode( $slug );
			if ( $decoded_slug === $slug ) {
				$slug = substr( $slug, 0, $length );
			} else {
				$slug = utf8_uri_encode( $decoded_slug, $length, true );
			}
		}
	
		return rtrim( $slug, '-' );
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'get_page_children' ) ) :
	function get_page_children( $page_id, $pages ) {
		// Build a hash of ID -> children.
		$children = array();
		foreach ( (array) $pages as $page ) {
			$children[ (int) $page->post_parent ][] = $page;
		}
	
		$page_list = array();
	
		// Start the search by looking at immediate children.
		if ( isset( $children[ $page_id ] ) ) {
			// Always start at the end of the stack in order to preserve original `$pages` order.
			$to_look = array_reverse( $children[ $page_id ] );
	
			while ( $to_look ) {
				$p           = array_pop( $to_look );
				$page_list[] = $p;
				if ( isset( $children[ $p->ID ] ) ) {
					foreach ( array_reverse( $children[ $p->ID ] ) as $child ) {
						// Append to the `$to_look` stack to descend the tree.
						$to_look[] = $child;
					}
				}
			}
		}
	
		return $page_list;
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'get_page_hierarchy' ) ) :
	function get_page_hierarchy( &$pages, $page_id = 0 ) {
		if ( empty( $pages ) ) {
			return array();
		}
	
		$children = array();
		foreach ( (array) $pages as $p ) {
			$parent_id                = (int) $p->post_parent;
			$children[ $parent_id ][] = $p;
		}
	
		$result = array();
		_page_traverse_name( $page_id, $children, $result );
	
		return $result;
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( '_page_traverse_name' ) ) :
	function _page_traverse_name( $page_id, &$children, &$result ) {
		if ( isset( $children[ $page_id ] ) ) {
			foreach ( (array) $children[ $page_id ] as $child ) {
				$result[ $child->ID ] = $child->post_name;
				_page_traverse_name( $child->ID, $children, $result );
			}
		}
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'wp_untrash_post_set_previous_status' ) ) :
	function wp_untrash_post_set_previous_status( $new_status, $post_id, $previous_status ) {
		return $previous_status;
	}
endif;

// wp-includes/post.php (WP 6.9.4)
if( ! function_exists( 'use_block_editor_for_post_type' ) ) :
	function use_block_editor_for_post_type( $post_type ) {
		if ( ! post_type_exists( $post_type ) ) {
			return false;
		}
	
		if ( ! post_type_supports( $post_type, 'editor' ) ) {
			return false;
		}
	
		$post_type_object = get_post_type_object( $post_type );
		if ( $post_type_object && ! $post_type_object->show_in_rest ) {
			return false;
		}
	
		/**
		 * Filters whether a post is able to be edited in the block editor.
		 *
		 * @since 5.0.0
		 *
		 * @param bool   $use_block_editor  Whether the post type can be edited or not. Default true.
		 * @param string $post_type         The post type being checked.
		 */
		return apply_filters( 'use_block_editor_for_post_type', true, $post_type );
	}
endif;

