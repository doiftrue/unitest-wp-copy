<?php

// ------------------auto-generated---------------------

// wp-includes/link-template.php (WP 6.8.3)
if( ! function_exists( 'plugins_url' ) ) :
	function plugins_url( $path = '', $plugin = '' ) {
	
		$path          = wp_normalize_path( $path );
		$plugin        = wp_normalize_path( $plugin );
		$mu_plugin_dir = wp_normalize_path( WPMU_PLUGIN_DIR );
	
		if ( ! empty( $plugin ) && str_starts_with( $plugin, $mu_plugin_dir ) ) {
			$url = WPMU_PLUGIN_URL;
		} else {
			$url = WP_PLUGIN_URL;
		}
	
		$url = set_url_scheme( $url );
	
		if ( ! empty( $plugin ) && is_string( $plugin ) ) {
			$folder = dirname( plugin_basename( $plugin ) );
			if ( '.' !== $folder ) {
				$url .= '/' . ltrim( $folder, '/' );
			}
		}
	
		if ( $path && is_string( $path ) ) {
			$url .= '/' . ltrim( $path, '/' );
		}
	
		/**
		 * Filters the URL to the plugins directory.
		 *
		 * @since 2.8.0
		 *
		 * @param string $url    The complete URL to the plugins directory including scheme and path.
		 * @param string $path   Path relative to the URL to the plugins directory. Blank string
		 *                       if no path is specified.
		 * @param string $plugin The plugin file path to be relative to. Blank string if no plugin
		 *                       is specified.
		 */
		return apply_filters( 'plugins_url', $url, $path, $plugin );
	}
endif;

