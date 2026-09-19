<?php

// ------------------auto-generated---------------------

// wp-includes/abilities-api.php (WP 7.0.5)
if( ! function_exists( 'wp_has_ability' ) ) :
	function wp_has_ability( string $name ): bool {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$registry = WP_Abilities_Registry::get_instance();
		if ( null === $registry ) {
			return false;
		}
	
		return $registry->is_registered( $name );
	}
endif;

// wp-includes/abilities-api.php (WP 7.0.5)
if( ! function_exists( 'wp_get_ability' ) ) :
	function wp_get_ability( string $name ): ?WP_Ability {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$registry = WP_Abilities_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->get_registered( $name );
	}
endif;

// wp-includes/abilities-api.php (WP 7.0.5)
if( ! function_exists( 'wp_has_ability_category' ) ) :
	function wp_has_ability_category( string $slug ): bool {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return false;
		}
	
		return $registry->is_registered( $slug );
	}
endif;

// wp-includes/abilities-api.php (WP 7.0.5)
if( ! function_exists( 'wp_get_ability_category' ) ) :
	function wp_get_ability_category( string $slug ): ?WP_Ability_Category {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->get_registered( $slug );
	}
endif;

// wp-includes/abilities-api.php (WP 7.0.5)
if( ! function_exists( 'wp_get_ability_categories' ) ) :
	function wp_get_ability_categories(): array {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$registry = WP_Ability_Categories_Registry::get_instance();
		if ( null === $registry ) {
			return array();
		}
	
		return $registry->get_all_registered();
	}
endif;

