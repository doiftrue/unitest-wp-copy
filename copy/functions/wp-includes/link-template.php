<?php

// ------------------auto-generated---------------------

// wp-includes/link-template.php (WP 6.8.3)
if( ! function_exists( 'content_url' ) ) :
	function content_url( $path = '' ) {
		$url = set_url_scheme( WP_CONTENT_URL );
	
		if ( $path && is_string( $path ) ) {
			$url .= '/' . ltrim( $path, '/' );
		}
	
		/**
		 * Filters the URL to the content directory.
		 *
		 * @since 2.8.0
		 *
		 * @param string $url  The complete URL to the content directory including scheme and path.
		 * @param string $path Path relative to the URL to the content directory. Blank string
		 *                     if no path is specified.
		 */
		return apply_filters( 'content_url', $url, $path );
	}
endif;

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

// wp-includes/link-template.php (WP 6.8.3)
if( ! function_exists( 'set_url_scheme' ) ) :
	function set_url_scheme( $url, $scheme = null ) {
		$orig_scheme = $scheme;
	
		if ( ! $scheme ) {
			$scheme = is_ssl() ? 'https' : 'http';
		} elseif ( 'admin' === $scheme || 'login' === $scheme || 'login_post' === $scheme || 'rpc' === $scheme ) {
			$scheme = is_ssl() || force_ssl_admin() ? 'https' : 'http';
		} elseif ( 'http' !== $scheme && 'https' !== $scheme && 'relative' !== $scheme ) {
			$scheme = is_ssl() ? 'https' : 'http';
		}
	
		$url = trim( $url );
		if ( str_starts_with( $url, '//' ) ) {
			$url = 'http:' . $url;
		}
	
		if ( 'relative' === $scheme ) {
			$url = ltrim( preg_replace( '#^\w+://[^/]*#', '', $url ) );
			if ( '' !== $url && '/' === $url[0] ) {
				$url = '/' . ltrim( $url, "/ \t\n\r\0\x0B" );
			}
		} else {
			$url = preg_replace( '#^\w+://#', $scheme . '://', $url );
		}
	
		/**
		 * Filters the resulting URL after setting the scheme.
		 *
		 * @since 3.4.0
		 *
		 * @param string      $url         The complete URL including scheme and path.
		 * @param string      $scheme      Scheme applied to the URL. One of 'http', 'https', or 'relative'.
		 * @param string|null $orig_scheme Scheme requested for the URL. One of 'http', 'https', 'login',
		 *                                 'login_post', 'admin', 'relative', 'rest', 'rpc', or null.
		 */
		return apply_filters( 'set_url_scheme', $url, $scheme, $orig_scheme );
	}
endif;

