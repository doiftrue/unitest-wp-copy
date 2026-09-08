<?php

// ------------------auto-generated---------------------

// wp-includes/abilities-api.php (WP 6.9.7)
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

// wp-includes/abilities-api.php (WP 6.9.7)
if( ! function_exists( 'wp_unregister_ability' ) ) :
	function wp_unregister_ability( string $name ): ?WP_Ability {
		$registry = WP_Abilities_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->unregister( $name );
	}
endif;

// wp-includes/abilities-api.php (WP 6.9.7)
if( ! function_exists( 'wp_has_ability' ) ) :
	function wp_has_ability( string $name ): bool {
		$registry = WP_Abilities_Registry::get_instance();
		if ( null === $registry ) {
			return false;
		}
	
		return $registry->is_registered( $name );
	}
endif;

// wp-includes/abilities-api.php (WP 6.9.7)
if( ! function_exists( 'wp_get_ability' ) ) :
	function wp_get_ability( string $name ): ?WP_Ability {
		$registry = WP_Abilities_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->get_registered( $name );
	}
endif;

// wp-includes/abilities-api.php (WP 6.9.7)
if( ! function_exists( 'wp_get_abilities' ) ) :
	function wp_get_abilities(): array {
		$registry = WP_Abilities_Registry::get_instance();
		if ( null === $registry ) {
			return array();
		}
	
		return $registry->get_all_registered();
	}
endif;

// wp-includes/abilities-api.php (WP 6.9.7)
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

// wp-includes/abilities-api.php (WP 6.9.7)
if( ! function_exists( 'wp_unregister_ability_category' ) ) :
	function wp_unregister_ability_category( string $slug ): ?WP_Ability_Category {
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->unregister( $slug );
	}
endif;

// wp-includes/abilities-api.php (WP 6.9.7)
if( ! function_exists( 'wp_has_ability_category' ) ) :
	function wp_has_ability_category( string $slug ): bool {
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return false;
		}
	
		return $registry->is_registered( $slug );
	}
endif;

// wp-includes/abilities-api.php (WP 6.9.7)
if( ! function_exists( 'wp_get_ability_category' ) ) :
	function wp_get_ability_category( string $slug ): ?WP_Ability_Category {
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->get_registered( $slug );
	}
endif;

// wp-includes/abilities-api.php (WP 6.9.7)
if( ! function_exists( 'wp_get_ability_categories' ) ) :
	function wp_get_ability_categories(): array {
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return array();
		}
	
		return $registry->get_all_registered();
	}
endif;

