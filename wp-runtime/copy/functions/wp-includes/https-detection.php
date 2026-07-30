<?php

// ------------------auto-generated---------------------

// wp-includes/https-detection.php (WP 6.8.6)
if( ! function_exists( 'wp_is_using_https' ) ) :
	function wp_is_using_https() {
		if ( ! wp_is_home_url_using_https() ) {
			return false;
		}
	
		return wp_is_site_url_using_https();
	}
endif;

// wp-includes/https-detection.php (WP 6.8.6)
if( ! function_exists( 'wp_is_home_url_using_https' ) ) :
	function wp_is_home_url_using_https() {
		return 'https' === wp_parse_url( home_url(), PHP_URL_SCHEME );
	}
endif;

// wp-includes/https-detection.php (WP 6.8.6)
if( ! function_exists( 'wp_is_site_url_using_https' ) ) :
	function wp_is_site_url_using_https() {
		/*
		 * Use direct option access for 'siteurl' and manually run the 'site_url'
		 * filter because `site_url()` will adjust the scheme based on what the
		 * current request is using.
		 */
		/** This filter is documented in wp-includes/link-template.php */
		$site_url = apply_filters( 'site_url', get_option( 'siteurl' ), '', null, null );
	
		return 'https' === wp_parse_url( $site_url, PHP_URL_SCHEME );
	}
endif;

// wp-includes/https-detection.php (WP 6.8.6)
if( ! function_exists( 'wp_is_local_html_output' ) ) :
	function wp_is_local_html_output( $html ) {
		// 1. Check if HTML includes the site's Really Simple Discovery link.
		if ( has_action( 'wp_head', 'rsd_link' ) ) {
			$pattern = preg_replace( '#^https?:(?=//)#', '', esc_url( site_url( 'xmlrpc.php?rsd', 'rpc' ) ) ); // See rsd_link().
			return str_contains( $html, $pattern );
		}
	
		// 2. Check if HTML includes the site's REST API link.
		if ( has_action( 'wp_head', 'rest_output_link_wp_head' ) ) {
			// Try both HTTPS and HTTP since the URL depends on context.
			$pattern = preg_replace( '#^https?:(?=//)#', '', esc_url( get_rest_url() ) ); // See rest_output_link_wp_head().
			return str_contains( $html, $pattern );
		}
	
		// Otherwise the result cannot be determined.
		return null;
	}
endif;

