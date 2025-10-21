<?php

// ------------------auto-generated---------------------

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'plugin_basename' ) ) :
	function plugin_basename( $file ) {
		global $wp_plugin_paths;
	
		// $wp_plugin_paths contains normalized paths.
		$file = wp_normalize_path( $file );
	
		arsort( $wp_plugin_paths );
	
		foreach ( $wp_plugin_paths as $dir => $realdir ) {
			if ( str_starts_with( $file, $realdir ) ) {
				$file = $dir . substr( $file, strlen( $realdir ) );
			}
		}
	
		$plugin_dir    = wp_normalize_path( WP_PLUGIN_DIR );
		$mu_plugin_dir = wp_normalize_path( WPMU_PLUGIN_DIR );
	
		// Get relative path from plugins directory.
		$file = preg_replace( '#^' . preg_quote( $plugin_dir, '#' ) . '/|^' . preg_quote( $mu_plugin_dir, '#' ) . '/#', '', $file );
		$file = trim( $file, '/' );
		return $file;
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( 'wp_register_plugin_realpath' ) ) :
	function wp_register_plugin_realpath( $file ) {
		global $wp_plugin_paths;
	
		// Normalize, but store as static to avoid recalculation of a constant value.
		static $wp_plugin_path = null, $wpmu_plugin_path = null;
	
		if ( ! isset( $wp_plugin_path ) ) {
			$wp_plugin_path   = wp_normalize_path( WP_PLUGIN_DIR );
			$wpmu_plugin_path = wp_normalize_path( WPMU_PLUGIN_DIR );
		}
	
		$plugin_path     = wp_normalize_path( dirname( $file ) );
		$plugin_realpath = wp_normalize_path( dirname( realpath( $file ) ) );
	
		if ( $plugin_path === $wp_plugin_path || $plugin_path === $wpmu_plugin_path ) {
			return false;
		}
	
		if ( $plugin_path !== $plugin_realpath ) {
			$wp_plugin_paths[ $plugin_path ] = $plugin_realpath;
		}
	
		return true;
	}
endif;

// wp-includes/plugin.php (WP 6.8.3)
if( ! function_exists( '_wp_filter_build_unique_id' ) ) :
	function _wp_filter_build_unique_id( $hook_name, $callback, $priority ) {
		if ( is_string( $callback ) ) {
			return $callback;
		}
	
		if ( is_object( $callback ) ) {
			// Closures are currently implemented as objects.
			$callback = array( $callback, '' );
		} else {
			$callback = (array) $callback;
		}
	
		if ( is_object( $callback[0] ) ) {
			// Object class calling.
			return spl_object_hash( $callback[0] ) . $callback[1];
		} elseif ( is_string( $callback[0] ) ) {
			// Static calling.
			return $callback[0] . '::' . $callback[1];
		}
	}
endif;

