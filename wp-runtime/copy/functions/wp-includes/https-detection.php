<?php

// ------------------auto-generated---------------------

// wp-includes/https-detection.php (WP 7.0)
if( ! function_exists( 'wp_is_using_https' ) ) :
	function wp_is_using_https() {
		if ( ! wp_is_home_url_using_https() ) {
			return false;
		}
	
		return wp_is_site_url_using_https();
	}
endif;

// wp-includes/https-detection.php (WP 7.0)
if( ! function_exists( 'wp_is_home_url_using_https' ) ) :
	function wp_is_home_url_using_https() {
		return 'https' === wp_parse_url( home_url(), PHP_URL_SCHEME );
	}
endif;

// wp-includes/https-detection.php (WP 7.0)
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

