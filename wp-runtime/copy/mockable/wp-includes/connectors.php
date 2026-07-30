<?php

// ------------------auto-generated---------------------

// wp-includes/connectors.php (WP 7.0)
if( ! function_exists( 'wp_is_connector_registered' ) ) :
	function wp_is_connector_registered( string $id ): bool {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$registry = WP_Connector_Registry::get_instance();
		if ( null === $registry ) {
			return false;
		}
	
		return $registry->is_registered( $id );
	}
endif;

// wp-includes/connectors.php (WP 7.0)
if( ! function_exists( 'wp_get_connector' ) ) :
	function wp_get_connector( string $id ): ?array {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$registry = WP_Connector_Registry::get_instance();
		if ( null === $registry ) {
			return null;
		}
	
		return $registry->get_registered( $id );
	}
endif;

// wp-includes/connectors.php (WP 7.0)
if( ! function_exists( 'wp_get_connectors' ) ) :
	function wp_get_connectors(): array {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$registry = WP_Connector_Registry::get_instance();
		if ( null === $registry ) {
			return array();
		}
	
		return $registry->get_all_registered();
	}
endif;

