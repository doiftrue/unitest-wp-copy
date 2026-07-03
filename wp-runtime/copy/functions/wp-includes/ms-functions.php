<?php

// ------------------auto-generated---------------------

// wp-includes/ms-functions.php (WP 6.5.8)
if( ! function_exists( 'get_subdirectory_reserved_names' ) ) :
	function get_subdirectory_reserved_names() {
		$names = array(
			'page',
			'comments',
			'blog',
			'files',
			'feed',
			'wp-admin',
			'wp-content',
			'wp-includes',
			'wp-json',
			'embed',
		);
	
		/**
		 * Filters reserved site names on a sub-directory Multisite installation.
		 *
		 * @since 3.0.0
		 * @since 4.4.0 'wp-admin', 'wp-content', 'wp-includes', 'wp-json', and 'embed' were added
		 *              to the reserved names list.
		 *
		 * @param string[] $subdirectory_reserved_names Array of reserved names.
		 */
		return apply_filters( 'subdirectory_reserved_names', $names );
	}
endif;

// wp-includes/ms-functions.php (WP 6.5.8)
if( ! function_exists( 'force_ssl_content' ) ) :
	function force_ssl_content( $force = '' ) {
		static $forced_content = false;
	
		if ( ! $force ) {
			$old_forced     = $forced_content;
			$forced_content = $force;
			return $old_forced;
		}
	
		return $forced_content;
	}
endif;

// wp-includes/ms-functions.php (WP 6.5.8)
if( ! function_exists( 'filter_SSL' ) ) :
	function filter_SSL( $url ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		if ( ! is_string( $url ) ) {
			return get_bloginfo( 'url' ); // Return home site URL with proper scheme.
		}
	
		if ( force_ssl_content() && is_ssl() ) {
			$url = set_url_scheme( $url, 'https' );
		}
	
		return $url;
	}
endif;

