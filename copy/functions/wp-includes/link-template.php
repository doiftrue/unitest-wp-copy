<?php

// ------------------auto-generated---------------------

// wp-includes/link-template.php (WP 6.8.3)
if( ! function_exists( 'home_url' ) ) :
	function home_url( $path = '', $scheme = null ) {
		return get_home_url( null, $path, $scheme );
	}
endif;

// wp-includes/link-template.php (WP 6.8.3)
if( ! function_exists( 'get_home_url' ) ) :
	function get_home_url( $blog_id = null, $path = '', $scheme = null ) {
		$orig_scheme = $scheme;
	
		if ( empty( $blog_id ) || ! is_multisite() ) {
			$url = $GLOBALS['stub_wp_options']->home;
		} else {
			switch_to_blog( $blog_id );
			$url = $GLOBALS['stub_wp_options']->home;
			restore_current_blog();
		}
	
		if ( ! in_array( $scheme, array( 'http', 'https', 'relative' ), true ) ) {
			if ( is_ssl() ) {
				$scheme = 'https';
			} else {
				$scheme = parse_url( $url, PHP_URL_SCHEME );
			}
		}
	
		$url = set_url_scheme( $url, $scheme );
	
		if ( $path && is_string( $path ) ) {
			$url .= '/' . ltrim( $path, '/' );
		}
	
		/**
		 * Filters the home URL.
		 *
		 * @since 3.0.0
		 *
		 * @param string      $url         The complete home URL including scheme and path.
		 * @param string      $path        Path relative to the home URL. Blank string if no path is specified.
		 * @param string|null $orig_scheme Scheme to give the home URL context. Accepts 'http', 'https',
		 *                                 'relative', 'rest', or null.
		 * @param int|null    $blog_id     Site ID, or null for the current site.
		 */
		return apply_filters( 'home_url', $url, $path, $orig_scheme, $blog_id );
	}
endif;

// wp-includes/link-template.php (WP 6.8.3)
if( ! function_exists( 'includes_url' ) ) :
	function includes_url( $path = '', $scheme = null ) {
		$url = site_url( '/' . WPINC . '/', $scheme );
	
		if ( $path && is_string( $path ) ) {
			$url .= ltrim( $path, '/' );
		}
	
		/**
		 * Filters the URL to the includes directory.
		 *
		 * @since 2.8.0
		 * @since 5.8.0 The `$scheme` parameter was added.
		 *
		 * @param string      $url    The complete URL to the includes directory including scheme and path.
		 * @param string      $path   Path relative to the URL to the wp-includes directory. Blank string
		 *                            if no path is specified.
		 * @param string|null $scheme Scheme to give the includes URL context. Accepts
		 *                            'http', 'https', 'relative', or null. Default null.
		 */
		return apply_filters( 'includes_url', $url, $path, $scheme );
	}
endif;

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

// wp-includes/link-template.php (WP 6.8.3)
if( ! function_exists( 'wp_internal_hosts' ) ) :
	function wp_internal_hosts() {
		static $internal_hosts;
	
		if ( empty( $internal_hosts ) ) {
			/**
			 * Filters the array of URL hosts which are considered internal.
			 *
			 * @since 6.2.0
			 *
			 * @param string[] $internal_hosts An array of internal URL hostnames.
			 */
			$internal_hosts = apply_filters(
				'wp_internal_hosts',
				array(
					wp_parse_url( home_url(), PHP_URL_HOST ),
				)
			);
			$internal_hosts = array_unique(
				array_map( 'strtolower', (array) $internal_hosts )
			);
		}
	
		return $internal_hosts;
	}
endif;

// wp-includes/link-template.php (WP 6.8.3)
if( ! function_exists( 'wp_is_internal_link' ) ) :
	function wp_is_internal_link( $link ) {
		$link = strtolower( $link );
		if ( in_array( wp_parse_url( $link, PHP_URL_SCHEME ), wp_allowed_protocols(), true ) ) {
			return in_array( wp_parse_url( $link, PHP_URL_HOST ), wp_internal_hosts(), true );
		}
		return false;
	}
endif;

