<?php

// ------------------auto-generated---------------------

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'current_theme_supports' ) ) :
	function current_theme_supports( $feature, ...$args ) {
		global $_wp_theme_features;
	
		if ( 'custom-header-uploads' === $feature ) {
			return current_theme_supports( 'custom-header', 'uploads' );
		}
	
		if ( ! isset( $_wp_theme_features[ $feature ] ) ) {
			return false;
		}
	
		// If no args passed then no extra checks need to be performed.
		if ( ! $args ) {
			/** This filter is documented in wp-includes/theme.php */
			return apply_filters( "current_theme_supports-{$feature}", true, $args, $_wp_theme_features[ $feature ] ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
	
		switch ( $feature ) {
			case 'post-thumbnails':
				/*
				 * post-thumbnails can be registered for only certain content/post types
				 * by passing an array of types to add_theme_support().
				 * If no array was passed, then any type is accepted.
				 */
				if ( true === $_wp_theme_features[ $feature ] ) {  // Registered for all types.
					return true;
				}
				$content_type = $args[0];
				return in_array( $content_type, $_wp_theme_features[ $feature ][0], true );
	
			case 'html5':
			case 'post-formats':
				/*
				 * Specific post formats can be registered by passing an array of types
				 * to add_theme_support().
				 *
				 * Specific areas of HTML5 support *must* be passed via an array to add_theme_support().
				 */
				$type = $args[0];
				return in_array( $type, $_wp_theme_features[ $feature ][0], true );
	
			case 'custom-logo':
			case 'custom-header':
			case 'custom-background':
				// Specific capabilities can be registered by passing an array to add_theme_support().
				return ( isset( $_wp_theme_features[ $feature ][0][ $args[0] ] ) && $_wp_theme_features[ $feature ][0][ $args[0] ] );
		}
	
		/**
		 * Filters whether the active theme supports a specific feature.
		 *
		 * The dynamic portion of the hook name, `$feature`, refers to the specific
		 * theme feature. See add_theme_support() for the list of possible values.
		 *
		 * @since 3.4.0
		 *
		 * @param bool   $supports Whether the active theme supports the given feature. Default true.
		 * @param array  $args     Array of arguments for the feature.
		 * @param string $feature  The theme feature.
		 */
		return apply_filters( "current_theme_supports-{$feature}", true, $args, $_wp_theme_features[ $feature ] ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'add_theme_support' ) ) :
	function add_theme_support( $feature, ...$args ) {
		global $_wp_theme_features;
	
		if ( ! $args ) {
			$args = true;
		}
	
		switch ( $feature ) {
			case 'post-thumbnails':
				// All post types are already supported.
				if ( true === get_theme_support( 'post-thumbnails' ) ) {
					return;
				}
	
				/*
				 * Merge post types with any that already declared their support
				 * for post thumbnails.
				 */
				if ( isset( $args[0] ) && is_array( $args[0] ) && isset( $_wp_theme_features['post-thumbnails'] ) ) {
					$args[0] = array_unique( array_merge( $_wp_theme_features['post-thumbnails'][0], $args[0] ) );
				}
	
				break;
	
			case 'post-formats':
				if ( isset( $args[0] ) && is_array( $args[0] ) ) {
					$post_formats = get_post_format_slugs();
					unset( $post_formats['standard'] );
	
					$args[0] = array_intersect( $args[0], array_keys( $post_formats ) );
				} else {
					_doing_it_wrong(
						"add_theme_support( 'post-formats' )",
						__( 'You need to pass an array of post formats.' ),
						'5.6.0'
					);
					return false;
				}
				break;
	
			case 'html5':
				// You can't just pass 'html5', you need to pass an array of types.
				if ( empty( $args[0] ) || ! is_array( $args[0] ) ) {
					_doing_it_wrong(
						"add_theme_support( 'html5' )",
						__( 'You need to pass an array of types.' ),
						'3.6.1'
					);
	
					if ( ! empty( $args[0] ) && ! is_array( $args[0] ) ) {
						return false;
					}
	
					// Build an array of types for back-compat.
					$args = array( 0 => array( 'comment-list', 'comment-form', 'search-form' ) );
				}
	
				// Calling 'html5' again merges, rather than overwrites.
				if ( isset( $_wp_theme_features['html5'] ) ) {
					$args[0] = array_merge( $_wp_theme_features['html5'][0], $args[0] );
				}
				break;
	
			case 'custom-logo':
				if ( true === $args ) {
					$args = array( 0 => array() );
				}
				$defaults = array(
					'width'                => null,
					'height'               => null,
					'flex-width'           => false,
					'flex-height'          => false,
					'header-text'          => '',
					'unlink-homepage-logo' => false,
				);
				$args[0]  = wp_parse_args( array_intersect_key( $args[0], $defaults ), $defaults );
	
				// Allow full flexibility if no size is specified.
				if ( is_null( $args[0]['width'] ) && is_null( $args[0]['height'] ) ) {
					$args[0]['flex-width']  = true;
					$args[0]['flex-height'] = true;
				}
				break;
	
			case 'custom-header-uploads':
				return add_theme_support( 'custom-header', array( 'uploads' => true ) );
	
			case 'custom-header':
				if ( true === $args ) {
					$args = array( 0 => array() );
				}
	
				$defaults = array(
					'default-image'          => '',
					'random-default'         => false,
					'width'                  => 0,
					'height'                 => 0,
					'flex-height'            => false,
					'flex-width'             => false,
					'default-text-color'     => '',
					'header-text'            => true,
					'uploads'                => true,
					'wp-head-callback'       => '',
					'admin-head-callback'    => '',
					'admin-preview-callback' => '',
					'video'                  => false,
					'video-active-callback'  => 'is_front_page',
				);
	
				$jit = isset( $args[0]['__jit'] );
				unset( $args[0]['__jit'] );
	
				/*
				 * Merge in data from previous add_theme_support() calls.
				 * The first value registered wins. (A child theme is set up first.)
				 */
				if ( isset( $_wp_theme_features['custom-header'] ) ) {
					$args[0] = wp_parse_args( $_wp_theme_features['custom-header'][0], $args[0] );
				}
	
				/*
				 * Load in the defaults at the end, as we need to insure first one wins.
				 * This will cause all constants to be defined, as each arg will then be set to the default.
				 */
				if ( $jit ) {
					$args[0] = wp_parse_args( $args[0], $defaults );
				}
	
				/*
				 * If a constant was defined, use that value. Otherwise, define the constant to ensure
				 * the constant is always accurate (and is not defined later,  overriding our value).
				 * As stated above, the first value wins.
				 * Once we get to wp_loaded (just-in-time), define any constants we haven't already.
				 * Constants should be avoided. Don't reference them. This is just for backward compatibility.
				 */
	
				if ( defined( 'NO_HEADER_TEXT' ) ) {
					$args[0]['header-text'] = ! NO_HEADER_TEXT;
				} elseif ( isset( $args[0]['header-text'] ) ) {
					define( 'NO_HEADER_TEXT', empty( $args[0]['header-text'] ) );
				}
	
				if ( defined( 'HEADER_IMAGE_WIDTH' ) ) {
					$args[0]['width'] = (int) HEADER_IMAGE_WIDTH;
				} elseif ( isset( $args[0]['width'] ) ) {
					define( 'HEADER_IMAGE_WIDTH', (int) $args[0]['width'] );
				}
	
				if ( defined( 'HEADER_IMAGE_HEIGHT' ) ) {
					$args[0]['height'] = (int) HEADER_IMAGE_HEIGHT;
				} elseif ( isset( $args[0]['height'] ) ) {
					define( 'HEADER_IMAGE_HEIGHT', (int) $args[0]['height'] );
				}
	
				if ( defined( 'HEADER_TEXTCOLOR' ) ) {
					$args[0]['default-text-color'] = HEADER_TEXTCOLOR;
				} elseif ( isset( $args[0]['default-text-color'] ) ) {
					define( 'HEADER_TEXTCOLOR', $args[0]['default-text-color'] );
				}
	
				if ( defined( 'HEADER_IMAGE' ) ) {
					$args[0]['default-image'] = HEADER_IMAGE;
				} elseif ( isset( $args[0]['default-image'] ) ) {
					define( 'HEADER_IMAGE', $args[0]['default-image'] );
				}
	
				if ( $jit && ! empty( $args[0]['default-image'] ) ) {
					$args[0]['random-default'] = false;
				}
	
				/*
				 * If headers are supported, and we still don't have a defined width or height,
				 * we have implicit flex sizes.
				 */
				if ( $jit ) {
					if ( empty( $args[0]['width'] ) && empty( $args[0]['flex-width'] ) ) {
						$args[0]['flex-width'] = true;
					}
					if ( empty( $args[0]['height'] ) && empty( $args[0]['flex-height'] ) ) {
						$args[0]['flex-height'] = true;
					}
				}
	
				break;
	
			case 'custom-background':
				if ( true === $args ) {
					$args = array( 0 => array() );
				}
	
				$defaults = array(
					'default-image'          => '',
					'default-preset'         => 'default',
					'default-position-x'     => 'left',
					'default-position-y'     => 'top',
					'default-size'           => 'auto',
					'default-repeat'         => 'repeat',
					'default-attachment'     => 'scroll',
					'default-color'          => '',
					'wp-head-callback'       => '_custom_background_cb',
					'admin-head-callback'    => '',
					'admin-preview-callback' => '',
				);
	
				$jit = isset( $args[0]['__jit'] );
				unset( $args[0]['__jit'] );
	
				// Merge in data from previous add_theme_support() calls. The first value registered wins.
				if ( isset( $_wp_theme_features['custom-background'] ) ) {
					$args[0] = wp_parse_args( $_wp_theme_features['custom-background'][0], $args[0] );
				}
	
				if ( $jit ) {
					$args[0] = wp_parse_args( $args[0], $defaults );
				}
	
				if ( defined( 'BACKGROUND_COLOR' ) ) {
					$args[0]['default-color'] = BACKGROUND_COLOR;
				} elseif ( isset( $args[0]['default-color'] ) || $jit ) {
					define( 'BACKGROUND_COLOR', $args[0]['default-color'] );
				}
	
				if ( defined( 'BACKGROUND_IMAGE' ) ) {
					$args[0]['default-image'] = BACKGROUND_IMAGE;
				} elseif ( isset( $args[0]['default-image'] ) || $jit ) {
					define( 'BACKGROUND_IMAGE', $args[0]['default-image'] );
				}
	
				break;
	
			// Ensure that 'title-tag' is accessible in the admin.
			case 'title-tag':
				// Can be called in functions.php but must happen before wp_loaded, i.e. not in header.php.
				if ( did_action( 'wp_loaded' ) ) {
					_doing_it_wrong(
						"add_theme_support( 'title-tag' )",
						sprintf(
							/* translators: 1: title-tag, 2: wp_loaded */
							__( 'Theme support for %1$s should be registered before the %2$s hook.' ),
							'<code>title-tag</code>',
							'<code>wp_loaded</code>'
						),
						'4.1.0'
					);
	
					return false;
				}
		}
	
		$_wp_theme_features[ $feature ] = $args;
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'get_theme_support' ) ) :
	function get_theme_support( $feature, ...$args ) {
		global $_wp_theme_features;
	
		if ( ! isset( $_wp_theme_features[ $feature ] ) ) {
			return false;
		}
	
		if ( ! $args ) {
			return $_wp_theme_features[ $feature ];
		}
	
		switch ( $feature ) {
			case 'custom-logo':
			case 'custom-header':
			case 'custom-background':
				if ( isset( $_wp_theme_features[ $feature ][0][ $args[0] ] ) ) {
					return $_wp_theme_features[ $feature ][0][ $args[0] ];
				}
				return false;
	
			default:
				return $_wp_theme_features[ $feature ];
		}
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'remove_theme_support' ) ) :
	function remove_theme_support( $feature ) {
		// Do not remove internal registrations that are not used directly by themes.
		if ( in_array( $feature, array( 'editor-style', 'widgets', 'menus' ), true ) ) {
			return false;
		}
	
		return _remove_theme_support( $feature );
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( '_remove_theme_support' ) ) :
	function _remove_theme_support( $feature ) {
		global $_wp_theme_features;
	
		switch ( $feature ) {
			case 'custom-header-uploads':
				if ( ! isset( $_wp_theme_features['custom-header'] ) ) {
					return false;
				}
				add_theme_support( 'custom-header', array( 'uploads' => false ) );
				return; // Do not continue - custom-header-uploads no longer exists.
		}
	
		if ( ! isset( $_wp_theme_features[ $feature ] ) ) {
			return false;
		}
	
		switch ( $feature ) {
			case 'custom-header':
				if ( ! did_action( 'wp_loaded' ) ) {
					break;
				}
				$support = get_theme_support( 'custom-header' );
				if ( isset( $support[0]['wp-head-callback'] ) ) {
					remove_action( 'wp_head', $support[0]['wp-head-callback'] );
				}
				if ( isset( $GLOBALS['custom_image_header'] ) ) {
					remove_action( 'admin_menu', array( $GLOBALS['custom_image_header'], 'init' ) );
					unset( $GLOBALS['custom_image_header'] );
				}
				break;
	
			case 'custom-background':
				if ( ! did_action( 'wp_loaded' ) ) {
					break;
				}
				$support = get_theme_support( 'custom-background' );
				if ( isset( $support[0]['wp-head-callback'] ) ) {
					remove_action( 'wp_head', $support[0]['wp-head-callback'] );
				}
				remove_action( 'admin_menu', array( $GLOBALS['custom_background'], 'init' ) );
				unset( $GLOBALS['custom_background'] );
				break;
		}
	
		unset( $_wp_theme_features[ $feature ] );
	
		return true;
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'register_theme_feature' ) ) :
	function register_theme_feature( $feature, $args = array() ) {
		global $_wp_registered_theme_features;
	
		if ( ! is_array( $_wp_registered_theme_features ) ) {
			$_wp_registered_theme_features = array();
		}
	
		$defaults = array(
			'type'         => 'boolean',
			'variadic'     => false,
			'description'  => '',
			'show_in_rest' => false,
		);
	
		$args = wp_parse_args( $args, $defaults );
	
		if ( true === $args['show_in_rest'] ) {
			$args['show_in_rest'] = array();
		}
	
		if ( is_array( $args['show_in_rest'] ) ) {
			$args['show_in_rest'] = wp_parse_args(
				$args['show_in_rest'],
				array(
					'schema'           => array(),
					'name'             => $feature,
					'prepare_callback' => null,
				)
			);
		}
	
		if ( ! in_array( $args['type'], array( 'string', 'boolean', 'integer', 'number', 'array', 'object' ), true ) ) {
			return new WP_Error(
				'invalid_type',
				__( 'The feature "type" is not valid JSON Schema type.' )
			);
		}
	
		if ( true === $args['variadic'] && 'array' !== $args['type'] ) {
			return new WP_Error(
				'variadic_must_be_array',
				__( 'When registering a "variadic" theme feature, the "type" must be an "array".' )
			);
		}
	
		if ( false !== $args['show_in_rest'] && in_array( $args['type'], array( 'array', 'object' ), true ) ) {
			if ( ! is_array( $args['show_in_rest'] ) || empty( $args['show_in_rest']['schema'] ) ) {
				return new WP_Error(
					'missing_schema',
					__( 'When registering an "array" or "object" feature to show in the REST API, the feature\'s schema must also be defined.' )
				);
			}
	
			if ( 'array' === $args['type'] && ! isset( $args['show_in_rest']['schema']['items'] ) ) {
				return new WP_Error(
					'missing_schema_items',
					__( 'When registering an "array" feature, the feature\'s schema must include the "items" keyword.' )
				);
			}
	
			if ( 'object' === $args['type'] && ! isset( $args['show_in_rest']['schema']['properties'] ) ) {
				return new WP_Error(
					'missing_schema_properties',
					__( 'When registering an "object" feature, the feature\'s schema must include the "properties" keyword.' )
				);
			}
		}
	
		if ( is_array( $args['show_in_rest'] ) ) {
			if ( isset( $args['show_in_rest']['prepare_callback'] )
				&& ! is_callable( $args['show_in_rest']['prepare_callback'] )
			) {
				return new WP_Error(
					'invalid_rest_prepare_callback',
					sprintf(
						/* translators: %s: prepare_callback */
						__( 'The "%s" must be a callable function.' ),
						'prepare_callback'
					)
				);
			}
	
			$args['show_in_rest']['schema'] = wp_parse_args(
				$args['show_in_rest']['schema'],
				array(
					'description' => $args['description'],
					'type'        => $args['type'],
					'default'     => false,
				)
			);
	
			if ( is_bool( $args['show_in_rest']['schema']['default'] )
				&& ! in_array( 'boolean', (array) $args['show_in_rest']['schema']['type'], true )
			) {
				// Automatically include the "boolean" type when the default value is a boolean.
				$args['show_in_rest']['schema']['type'] = (array) $args['show_in_rest']['schema']['type'];
				array_unshift( $args['show_in_rest']['schema']['type'], 'boolean' );
			}
	
			$args['show_in_rest']['schema'] = rest_default_additional_properties_to_false( $args['show_in_rest']['schema'] );
		}
	
		$_wp_registered_theme_features[ $feature ] = $args;
	
		return true;
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'get_registered_theme_features' ) ) :
	function get_registered_theme_features() {
		global $_wp_registered_theme_features;
	
		if ( ! is_array( $_wp_registered_theme_features ) ) {
			return array();
		}
	
		return $_wp_registered_theme_features;
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'get_registered_theme_feature' ) ) :
	function get_registered_theme_feature( $feature ) {
		global $_wp_registered_theme_features;
	
		if ( ! is_array( $_wp_registered_theme_features ) ) {
			return null;
		}
	
		return isset( $_wp_registered_theme_features[ $feature ] ) ? $_wp_registered_theme_features[ $feature ] : null;
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'create_initial_theme_features' ) ) :
	function create_initial_theme_features() {
		register_theme_feature(
			'align-wide',
			array(
				'description'  => __( 'Whether theme opts in to wide alignment CSS class.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'automatic-feed-links',
			array(
				'description'  => __( 'Whether posts and comments RSS feed links are added to head.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'block-templates',
			array(
				'description'  => __( 'Whether a theme uses block-based templates.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'block-template-parts',
			array(
				'description'  => __( 'Whether a theme uses block-based template parts.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'custom-background',
			array(
				'description'  => __( 'Custom background if defined by the theme.' ),
				'type'         => 'object',
				'show_in_rest' => array(
					'schema' => array(
						'properties' => array(
							'default-image'      => array(
								'type'   => 'string',
								'format' => 'uri',
							),
							'default-preset'     => array(
								'type' => 'string',
								'enum' => array(
									'default',
									'fill',
									'fit',
									'repeat',
									'custom',
								),
							),
							'default-position-x' => array(
								'type' => 'string',
								'enum' => array(
									'left',
									'center',
									'right',
								),
							),
							'default-position-y' => array(
								'type' => 'string',
								'enum' => array(
									'left',
									'center',
									'right',
								),
							),
							'default-size'       => array(
								'type' => 'string',
								'enum' => array(
									'auto',
									'contain',
									'cover',
								),
							),
							'default-repeat'     => array(
								'type' => 'string',
								'enum' => array(
									'repeat-x',
									'repeat-y',
									'repeat',
									'no-repeat',
								),
							),
							'default-attachment' => array(
								'type' => 'string',
								'enum' => array(
									'scroll',
									'fixed',
								),
							),
							'default-color'      => array(
								'type' => 'string',
							),
						),
					),
				),
			)
		);
		register_theme_feature(
			'custom-header',
			array(
				'description'  => __( 'Custom header if defined by the theme.' ),
				'type'         => 'object',
				'show_in_rest' => array(
					'schema' => array(
						'properties' => array(
							'default-image'      => array(
								'type'   => 'string',
								'format' => 'uri',
							),
							'random-default'     => array(
								'type' => 'boolean',
							),
							'width'              => array(
								'type' => 'integer',
							),
							'height'             => array(
								'type' => 'integer',
							),
							'flex-height'        => array(
								'type' => 'boolean',
							),
							'flex-width'         => array(
								'type' => 'boolean',
							),
							'default-text-color' => array(
								'type' => 'string',
							),
							'header-text'        => array(
								'type' => 'boolean',
							),
							'uploads'            => array(
								'type' => 'boolean',
							),
							'video'              => array(
								'type' => 'boolean',
							),
						),
					),
				),
			)
		);
		register_theme_feature(
			'custom-logo',
			array(
				'type'         => 'object',
				'description'  => __( 'Custom logo if defined by the theme.' ),
				'show_in_rest' => array(
					'schema' => array(
						'properties' => array(
							'width'                => array(
								'type' => 'integer',
							),
							'height'               => array(
								'type' => 'integer',
							),
							'flex-width'           => array(
								'type' => 'boolean',
							),
							'flex-height'          => array(
								'type' => 'boolean',
							),
							'header-text'          => array(
								'type'  => 'array',
								'items' => array(
									'type' => 'string',
								),
							),
							'unlink-homepage-logo' => array(
								'type' => 'boolean',
							),
						),
					),
				),
			)
		);
		register_theme_feature(
			'customize-selective-refresh-widgets',
			array(
				'description'  => __( 'Whether the theme enables Selective Refresh for Widgets being managed with the Customizer.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'dark-editor-style',
			array(
				'description'  => __( 'Whether theme opts in to the dark editor style UI.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'disable-custom-colors',
			array(
				'description'  => __( 'Whether the theme disables custom colors.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'disable-custom-font-sizes',
			array(
				'description'  => __( 'Whether the theme disables custom font sizes.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'disable-custom-gradients',
			array(
				'description'  => __( 'Whether the theme disables custom gradients.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'disable-layout-styles',
			array(
				'description'  => __( 'Whether the theme disables generated layout styles.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'editor-color-palette',
			array(
				'type'         => 'array',
				'description'  => __( 'Custom color palette if defined by the theme.' ),
				'show_in_rest' => array(
					'schema' => array(
						'items' => array(
							'type'       => 'object',
							'properties' => array(
								'name'  => array(
									'type' => 'string',
								),
								'slug'  => array(
									'type' => 'string',
								),
								'color' => array(
									'type' => 'string',
								),
							),
						),
					),
				),
			)
		);
		register_theme_feature(
			'editor-font-sizes',
			array(
				'type'         => 'array',
				'description'  => __( 'Custom font sizes if defined by the theme.' ),
				'show_in_rest' => array(
					'schema' => array(
						'items' => array(
							'type'       => 'object',
							'properties' => array(
								'name' => array(
									'type' => 'string',
								),
								'size' => array(
									'type' => 'number',
								),
								'slug' => array(
									'type' => 'string',
								),
							),
						),
					),
				),
			)
		);
		register_theme_feature(
			'editor-gradient-presets',
			array(
				'type'         => 'array',
				'description'  => __( 'Custom gradient presets if defined by the theme.' ),
				'show_in_rest' => array(
					'schema' => array(
						'items' => array(
							'type'       => 'object',
							'properties' => array(
								'name'     => array(
									'type' => 'string',
								),
								'gradient' => array(
									'type' => 'string',
								),
								'slug'     => array(
									'type' => 'string',
								),
							),
						),
					),
				),
			)
		);
		register_theme_feature(
			'editor-styles',
			array(
				'description'  => __( 'Whether theme opts in to the editor styles CSS wrapper.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'html5',
			array(
				'type'         => 'array',
				'description'  => __( 'Allows use of HTML5 markup for search forms, comment forms, comment lists, gallery, and caption.' ),
				'show_in_rest' => array(
					'schema' => array(
						'items' => array(
							'type' => 'string',
							'enum' => array(
								'search-form',
								'comment-form',
								'comment-list',
								'gallery',
								'caption',
								'script',
								'style',
							),
						),
					),
				),
			)
		);
		register_theme_feature(
			'post-formats',
			array(
				'type'         => 'array',
				'description'  => __( 'Post formats supported.' ),
				'show_in_rest' => array(
					'name'             => 'formats',
					'schema'           => array(
						'items'   => array(
							'type' => 'string',
							'enum' => get_post_format_slugs(),
						),
						'default' => array( 'standard' ),
					),
					'prepare_callback' => static function ( $formats ) {
						$formats = is_array( $formats ) ? array_values( $formats[0] ) : array();
						$formats = array_merge( array( 'standard' ), $formats );
	
						return $formats;
					},
				),
			)
		);
		register_theme_feature(
			'post-thumbnails',
			array(
				'type'         => 'array',
				'description'  => __( 'The post types that support thumbnails or true if all post types are supported.' ),
				'show_in_rest' => array(
					'type'   => array( 'boolean', 'array' ),
					'schema' => array(
						'items' => array(
							'type' => 'string',
						),
					),
				),
			)
		);
		register_theme_feature(
			'responsive-embeds',
			array(
				'description'  => __( 'Whether the theme supports responsive embedded content.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'title-tag',
			array(
				'description'  => __( 'Whether the theme can manage the document title tag.' ),
				'show_in_rest' => true,
			)
		);
		register_theme_feature(
			'wp-block-styles',
			array(
				'description'  => __( 'Whether theme opts in to default WordPress block styles for viewing.' ),
				'show_in_rest' => true,
			)
		);
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'get_stylesheet' ) ) :
	function get_stylesheet() {
		/**
		 * Filters the name of current stylesheet.
		 *
		 * @since 1.5.0
		 *
		 * @param string $stylesheet Name of the current stylesheet.
		 */
		return apply_filters( 'stylesheet', $GLOBALS['stub_wp_options']->stylesheet );
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'get_template' ) ) :
	function get_template() {
		/**
		 * Filters the name of the active theme.
		 *
		 * @since 1.5.0
		 *
		 * @param string $template active theme's directory name.
		 */
		return apply_filters( 'template', $GLOBALS['stub_wp_options']->template );
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'get_stylesheet_uri' ) ) :
	function get_stylesheet_uri() {
		$stylesheet_dir_uri = get_stylesheet_directory_uri();
		$stylesheet_uri     = $stylesheet_dir_uri . '/style.css';
		/**
		 * Filters the URI of the active theme stylesheet.
		 *
		 * @since 1.5.0
		 *
		 * @param string $stylesheet_uri     Stylesheet URI for the active theme/child theme.
		 * @param string $stylesheet_dir_uri Stylesheet directory URI for the active theme/child theme.
		 */
		return apply_filters( 'stylesheet_uri', $stylesheet_uri, $stylesheet_dir_uri );
	}
endif;

// wp-includes/theme.php (WP 6.5.8)
if( ! function_exists( 'get_locale_stylesheet_uri' ) ) :
	function get_locale_stylesheet_uri() {
		global $wp_locale;
		$stylesheet_dir_uri = get_stylesheet_directory_uri();
		$dir                = get_stylesheet_directory();
		$locale             = get_locale();
		if ( file_exists( "$dir/$locale.css" ) ) {
			$stylesheet_uri = "$stylesheet_dir_uri/$locale.css";
		} elseif ( ! empty( $wp_locale->text_direction ) && file_exists( "$dir/{$wp_locale->text_direction}.css" ) ) {
			$stylesheet_uri = "$stylesheet_dir_uri/{$wp_locale->text_direction}.css";
		} else {
			$stylesheet_uri = '';
		}
		/**
		 * Filters the localized stylesheet URI.
		 *
		 * @since 2.1.0
		 *
		 * @param string $stylesheet_uri     Localized stylesheet URI.
		 * @param string $stylesheet_dir_uri Stylesheet directory URI.
		 */
		return apply_filters( 'locale_stylesheet_uri', $stylesheet_uri, $stylesheet_dir_uri );
	}
endif;

