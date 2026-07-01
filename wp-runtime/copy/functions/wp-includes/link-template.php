<?php

// ------------------auto-generated---------------------

// wp-includes/link-template.php (WP 6.6.5)
if( ! function_exists( 'home_url' ) ) :
	function home_url( $path = '', $scheme = null ) {
		return get_home_url( null, $path, $scheme );
	}
endif;

// wp-includes/link-template.php (WP 6.6.5)
if( ! function_exists( 'site_url' ) ) :
	function site_url( $path = '', $scheme = null ) {
		return get_site_url( null, $path, $scheme );
	}
endif;

// wp-includes/link-template.php (WP 6.6.5)
if( ! function_exists( 'admin_url' ) ) :
	function admin_url( $path = '', $scheme = 'admin' ) {
		return get_admin_url( null, $path, $scheme );
	}
endif;

// wp-includes/link-template.php (WP 6.6.5)
if( ! function_exists( '_navigation_markup' ) ) :
	function _navigation_markup( $links, $css_class = 'posts-navigation', $screen_reader_text = '', $aria_label = '' ) {
		if ( empty( $screen_reader_text ) ) {
			$screen_reader_text = /* translators: Hidden accessibility text. */ __( 'Posts navigation' );
		}
		if ( empty( $aria_label ) ) {
			$aria_label = $screen_reader_text;
		}
	
		$template = '
		<nav class="navigation %1$s" aria-label="%4$s">
			<h2 class="screen-reader-text">%2$s</h2>
			<div class="nav-links">%3$s</div>
		</nav>';
	
		/**
		 * Filters the navigation markup template.
		 *
		 * Note: The filtered template HTML must contain specifiers for the navigation
		 * class (%1$s), the screen-reader-text value (%2$s), placement of the navigation
		 * links (%3$s), and ARIA label text if screen-reader-text does not fit that (%4$s):
		 *
		 *     <nav class="navigation %1$s" aria-label="%4$s">
		 *         <h2 class="screen-reader-text">%2$s</h2>
		 *         <div class="nav-links">%3$s</div>
		 *     </nav>
		 *
		 * @since 4.4.0
		 *
		 * @param string $template  The default template.
		 * @param string $css_class The class passed by the calling function.
		 * @return string Navigation template.
		 */
		$template = apply_filters( 'navigation_markup_template', $template, $css_class );
	
		return sprintf( $template, sanitize_html_class( $css_class ), esc_html( $screen_reader_text ), $links, esc_attr( $aria_label ) );
	}
endif;

// wp-includes/link-template.php (WP 6.6.5)
if( ! function_exists( 'is_avatar_comment_type' ) ) :
	function is_avatar_comment_type( $comment_type ) {
		/**
		 * Filters the list of allowed comment types for retrieving avatars.
		 *
		 * @since 3.0.0
		 *
		 * @param array $types An array of content types. Default only contains 'comment'.
		 */
		$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
	
		return in_array( $comment_type, (array) $allowed_comment_types, true );
	}
endif;

// wp-includes/link-template.php (WP 6.6.5)
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

// wp-includes/link-template.php (WP 6.6.5)
if( ! function_exists( 'wp_is_internal_link' ) ) :
	function wp_is_internal_link( $link ) {
		$link = strtolower( $link );
		if ( in_array( wp_parse_url( $link, PHP_URL_SCHEME ), wp_allowed_protocols(), true ) ) {
			return in_array( wp_parse_url( $link, PHP_URL_HOST ), wp_internal_hosts(), true );
		}
		return false;
	}
endif;

// wp-includes/link-template.php (WP 6.6.5)
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

// wp-includes/link-template.php (WP 6.6.5)
if( ! function_exists( 'get_theme_file_uri' ) ) :
	function get_theme_file_uri( $file = '' ) {
		$file = ltrim( $file, '/' );
	
		$stylesheet_directory = get_stylesheet_directory();
	
		if ( empty( $file ) ) {
			$url = get_stylesheet_directory_uri();
		} elseif ( get_template_directory() !== $stylesheet_directory && file_exists( $stylesheet_directory . '/' . $file ) ) {
			$url = get_stylesheet_directory_uri() . '/' . $file;
		} else {
			$url = get_template_directory_uri() . '/' . $file;
		}
	
		/**
		 * Filters the URL to a file in the theme.
		 *
		 * @since 4.7.0
		 *
		 * @param string $url  The file URL.
		 * @param string $file The requested file to search for.
		 */
		return apply_filters( 'theme_file_uri', $url, $file );
	}
endif;

// wp-includes/link-template.php (WP 6.6.5)
if( ! function_exists( 'get_parent_theme_file_uri' ) ) :
	function get_parent_theme_file_uri( $file = '' ) {
		$file = ltrim( $file, '/' );
	
		if ( empty( $file ) ) {
			$url = get_template_directory_uri();
		} else {
			$url = get_template_directory_uri() . '/' . $file;
		}
	
		/**
		 * Filters the URL to a file in the parent theme.
		 *
		 * @since 4.7.0
		 *
		 * @param string $url  The file URL.
		 * @param string $file The requested file to search for.
		 */
		return apply_filters( 'parent_theme_file_uri', $url, $file );
	}
endif;

// wp-includes/link-template.php (WP 6.6.5)
if( ! function_exists( 'get_theme_file_path' ) ) :
	function get_theme_file_path( $file = '' ) {
		$file = ltrim( $file, '/' );
	
		$stylesheet_directory = get_stylesheet_directory();
		$template_directory   = get_template_directory();
	
		if ( empty( $file ) ) {
			$path = $stylesheet_directory;
		} elseif ( $stylesheet_directory !== $template_directory && file_exists( $stylesheet_directory . '/' . $file ) ) {
			$path = $stylesheet_directory . '/' . $file;
		} else {
			$path = $template_directory . '/' . $file;
		}
	
		/**
		 * Filters the path to a file in the theme.
		 *
		 * @since 4.7.0
		 *
		 * @param string $path The file path.
		 * @param string $file The requested file to search for.
		 */
		return apply_filters( 'theme_file_path', $path, $file );
	}
endif;

// wp-includes/link-template.php (WP 6.6.5)
if( ! function_exists( 'get_parent_theme_file_path' ) ) :
	function get_parent_theme_file_path( $file = '' ) {
		$file = ltrim( $file, '/' );
	
		if ( empty( $file ) ) {
			$path = get_template_directory();
		} else {
			$path = get_template_directory() . '/' . $file;
		}
	
		/**
		 * Filters the path to a file in the parent theme.
		 *
		 * @since 4.7.0
		 *
		 * @param string $path The file path.
		 * @param string $file The requested file to search for.
		 */
		return apply_filters( 'parent_theme_file_path', $path, $file );
	}
endif;

