<?php

// ------------------auto-generated---------------------

// wp-includes/abilities-api.php (WP 7.1)
if( ! function_exists( '_wp_get_abilities_match_meta' ) ) :
	function _wp_get_abilities_match_meta( array $meta, array $conditions ): bool {
		foreach ( $conditions as $key => $value ) {
			if ( ! array_key_exists( $key, $meta ) ) {
				return false;
			}
	
			if ( is_array( $value ) ) {
				if ( ! is_array( $meta[ $key ] ) || ! _wp_get_abilities_match_meta( $meta[ $key ], $value ) ) {
					return false;
				}
			} elseif ( $meta[ $key ] !== $value ) {
				return false;
			}
		}
	
		return true;
	}
endif;

// wp-includes/abilities-api.php (WP 7.1)
if( ! function_exists( 'wp_register_ability' ) ) :
	function wp_register_ability( string $name, array $args ): ?WP_Ability {
		if ( ! doing_action( 'wp_abilities_api_init' ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				sprintf(
					/* translators: 1: wp_abilities_api_init, 2: string value of the ability name. */
					__( 'Abilities must be registered on the %1$s action. The ability %2$s was not registered.' ),
					'<code>wp_abilities_api_init</code>',
					'<code>' . esc_html( $name ) . '</code>'
				),
				'6.9.0'
			);
			return null;
		}
	
		$registry = WP_Abilities_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->register( $name, $args );
	}
endif;

// wp-includes/abilities-api.php (WP 7.1)
if( ! function_exists( 'wp_unregister_ability' ) ) :
	function wp_unregister_ability( string $name ): ?WP_Ability {
		$registry = WP_Abilities_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->unregister( $name );
	}
endif;

// wp-includes/abilities-api.php (WP 7.1)
if( ! function_exists( 'wp_has_ability' ) ) :
	function wp_has_ability( string $name ): bool {
		$registry = WP_Abilities_Registry::get_instance();
		if ( null === $registry ) {
			return false;
		}
	
		return $registry->is_registered( $name );
	}
endif;

// wp-includes/abilities-api.php (WP 7.1)
if( ! function_exists( 'wp_get_ability' ) ) :
	function wp_get_ability( string $name ): ?WP_Ability {
		$registry = WP_Abilities_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->get_registered( $name );
	}
endif;

// wp-includes/abilities-api.php (WP 7.1)
if( ! function_exists( 'wp_get_abilities' ) ) :
	function wp_get_abilities( array $args = array() ): array {
		$registry = WP_Abilities_Registry::get_instance();
		if ( null === $registry ) {
			return array();
		}
	
		$abilities = $registry->get_all_registered();
	
		$category              = isset( $args['category'] ) && is_string( $args['category'] ) ? $args['category'] : '';
		$namespace             = isset( $args['namespace'] ) && is_string( $args['namespace'] ) ? rtrim( $args['namespace'], '/' ) . '/' : '';
		$meta                  = isset( $args['meta'] ) && is_array( $args['meta'] ) ? $args['meta'] : array();
		$item_include_callback = isset( $args['item_include_callback'] ) && is_callable( $args['item_include_callback'] ) ? $args['item_include_callback'] : null;
		$result_callback       = isset( $args['result_callback'] ) && is_callable( $args['result_callback'] ) ? $args['result_callback'] : null;
	
		$matched = array();
	
		foreach ( $abilities as $name => $ability ) {
			// Step 1a: Filter by category.
			if ( '' !== $category && $ability->get_category() !== $category ) {
				continue;
			}
	
			// Step 1b: Filter by namespace prefix.
			if ( '' !== $namespace && ! str_starts_with( $ability->get_name(), $namespace ) ) {
				continue;
			}
	
			// Step 1c: Filter by meta key/value pairs (AND logic, supports nested arrays).
			if ( ! empty( $meta ) && ! _wp_get_abilities_match_meta( $ability->get_meta(), $meta ) ) {
				continue;
			}
	
			// Step 2: Caller-scoped per-item callback.
			$include = true;
			if ( null !== $item_include_callback ) {
				$include = (bool) call_user_func( $item_include_callback, $ability );
			}
	
			/**
			 * Filters whether an individual ability should be included in the result set.
			 *
			 * Fires after the declarative filters and the caller-scoped item_include_callback.
			 * Plugins can use this to enforce universal inclusion rules regardless of
			 * what the caller passed in $args.
			 *
			 * @since 7.1.0
			 *
			 * @param bool       $include Whether to include the ability. Default true (after declarative filters pass).
			 * @param WP_Ability $ability The ability instance being evaluated.
			 * @param array      $args    The full $args array passed to wp_get_abilities().
			 */
			$include = (bool) apply_filters( 'wp_get_abilities_item_include', $include, $ability, $args );
	
			if ( $include ) {
				$matched[ $name ] = $ability;
			}
		}
	
		// Step 4: Caller-scoped result callback.
		if ( null !== $result_callback ) {
			$matched = (array) call_user_func( $result_callback, $matched );
		}
	
		/**
		 * Filters the full list of matched abilities after all per-item filtering is complete.
		 *
		 * Fires after the caller-scoped result_callback. Plugins can use this to sort,
		 * paginate, or reshape the final result set universally.
		 *
		 * @since 7.1.0
		 *
		 * @param WP_Ability[] $matched The matched abilities after all filtering.
		 * @param array        $args    The full $args array passed to wp_get_abilities().
		 */
		return (array) apply_filters( 'wp_get_abilities_result', $matched, $args );
	}
endif;

// wp-includes/abilities-api.php (WP 7.1)
if( ! function_exists( 'wp_register_ability_category' ) ) :
	function wp_register_ability_category( string $slug, array $args ): ?WP_Ability_Category {
		if ( ! doing_action( 'wp_abilities_api_categories_init' ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				sprintf(
					/* translators: 1: wp_abilities_api_categories_init, 2: ability category slug. */
					__( 'Ability categories must be registered on the %1$s action. The ability category %2$s was not registered.' ),
					'<code>wp_abilities_api_categories_init</code>',
					'<code>' . esc_html( $slug ) . '</code>'
				),
				'6.9.0'
			);
			return null;
		}
	
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->register( $slug, $args );
	}
endif;

// wp-includes/abilities-api.php (WP 7.1)
if( ! function_exists( 'wp_unregister_ability_category' ) ) :
	function wp_unregister_ability_category( string $slug ): ?WP_Ability_Category {
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->unregister( $slug );
	}
endif;

// wp-includes/abilities-api.php (WP 7.1)
if( ! function_exists( 'wp_has_ability_category' ) ) :
	function wp_has_ability_category( string $slug ): bool {
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return false;
		}
	
		return $registry->is_registered( $slug );
	}
endif;

// wp-includes/abilities-api.php (WP 7.1)
if( ! function_exists( 'wp_get_ability_category' ) ) :
	function wp_get_ability_category( string $slug ): ?WP_Ability_Category {
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->get_registered( $slug );
	}
endif;

// wp-includes/abilities-api.php (WP 7.1)
if( ! function_exists( 'wp_get_ability_categories' ) ) :
	function wp_get_ability_categories(): array {
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return array();
		}
	
		return $registry->get_all_registered();
	}
endif;

