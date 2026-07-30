<?php

// ------------------auto-generated---------------------

// wp-includes/ms-functions.php (WP 6.9.5)
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

// wp-includes/ms-functions.php (WP 6.9.5)
if( ! function_exists( 'is_email_address_unsafe' ) ) :
	function is_email_address_unsafe( $user_email ) {
		$banned_names = get_site_option( 'banned_email_domains' );
		if ( $banned_names && ! is_array( $banned_names ) ) {
			$banned_names = explode( "\n", $banned_names );
		}
	
		$is_email_address_unsafe = false;
	
		if ( $banned_names && is_array( $banned_names ) && false !== strpos( $user_email, '@', 1 ) ) {
			$banned_names     = array_map( 'strtolower', $banned_names );
			$normalized_email = strtolower( $user_email );
	
			list( $email_local_part, $email_domain ) = explode( '@', $normalized_email );
	
			foreach ( $banned_names as $banned_domain ) {
				if ( ! $banned_domain ) {
					continue;
				}
	
				if ( $email_domain === $banned_domain ) {
					$is_email_address_unsafe = true;
					break;
				}
	
				if ( str_ends_with( $normalized_email, ".$banned_domain" ) ) {
					$is_email_address_unsafe = true;
					break;
				}
			}
		}
	
		/**
		 * Filters whether an email address is unsafe.
		 *
		 * @since 3.5.0
		 *
		 * @param bool   $is_email_address_unsafe Whether the email address is "unsafe". Default false.
		 * @param string $user_email              User email address.
		 */
		return apply_filters( 'is_email_address_unsafe', $is_email_address_unsafe, $user_email );
	}
endif;

// wp-includes/ms-functions.php (WP 6.9.5)
if( ! function_exists( 'check_upload_mimes' ) ) :
	function check_upload_mimes( $mimes ) {
		$site_exts  = explode( ' ', get_site_option( 'upload_filetypes', 'jpg jpeg png gif' ) );
		$site_mimes = array();
		foreach ( $site_exts as $ext ) {
			foreach ( $mimes as $ext_pattern => $mime ) {
				if ( '' !== $ext && str_contains( $ext_pattern, $ext ) ) {
					$site_mimes[ $ext_pattern ] = $mime;
				}
			}
		}
		return $site_mimes;
	}
endif;

// wp-includes/ms-functions.php (WP 6.9.5)
if( ! function_exists( 'upload_is_file_too_big' ) ) :
	function upload_is_file_too_big( $upload ) {
		if ( ! is_array( $upload ) || defined( 'WP_IMPORTING' ) || get_site_option( 'upload_space_check_disabled' ) ) {
			return $upload;
		}
	
		if ( strlen( $upload['bits'] ) > ( KB_IN_BYTES * get_site_option( 'fileupload_maxk', 1500 ) ) ) {
			/* translators: %s: Maximum allowed file size in kilobytes. */
			return sprintf( __( 'This file is too big. Files must be less than %s KB in size.' ) . '<br />', get_site_option( 'fileupload_maxk', 1500 ) );
		}
	
		return $upload;
	}
endif;

// wp-includes/ms-functions.php (WP 6.9.5)
if( ! function_exists( 'users_can_register_signup_filter' ) ) :
	function users_can_register_signup_filter() {
		$registration = get_site_option( 'registration' );
		return ( 'all' === $registration || 'user' === $registration );
	}
endif;

// wp-includes/ms-functions.php (WP 6.9.5)
if( ! function_exists( 'get_space_allowed' ) ) :
	function get_space_allowed() {
		$space_allowed = get_option( 'blog_upload_space' );
	
		if ( ! is_numeric( $space_allowed ) ) {
			$space_allowed = get_site_option( 'blog_upload_space' );
		}
	
		if ( ! is_numeric( $space_allowed ) ) {
			$space_allowed = 100;
		}
	
		/**
		 * Filters the upload quota for the current site.
		 *
		 * @since 3.7.0
		 *
		 * @param int $space_allowed Upload quota in megabytes for the current blog.
		 */
		return apply_filters( 'get_space_allowed', $space_allowed );
	}
endif;

// wp-includes/ms-functions.php (WP 6.9.5)
if( ! function_exists( 'force_ssl_content' ) ) :
	function force_ssl_content( $force = null ) {
		static $forced_content = false;
	
		if ( ! is_null( $force ) ) {
			$old_forced     = $forced_content;
			$forced_content = (bool) $force;
			return $old_forced;
		}
	
		return $forced_content;
	}
endif;

// wp-includes/ms-functions.php (WP 6.9.5)
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

