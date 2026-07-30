<?php

// ------------------auto-generated---------------------

// wp-includes/https-migration.php (WP 6.9.5)
if( ! function_exists( 'wp_should_replace_insecure_home_url' ) ) :
	function wp_should_replace_insecure_home_url() {
		$should_replace_insecure_home_url = wp_is_using_https()
			&& get_option( 'https_migration_required' )
			// For automatic replacement, both 'home' and 'siteurl' need to not only use HTTPS, they also need to be using
			// the same domain.
			&& wp_parse_url( home_url(), PHP_URL_HOST ) === wp_parse_url( site_url(), PHP_URL_HOST );
	
		/**
		 * Filters whether WordPress should replace old HTTP URLs to the site with their HTTPS counterpart.
		 *
		 * If a WordPress site had its URL changed from HTTP to HTTPS, by default this will return `true`. This filter can
		 * be used to disable that behavior, e.g. after having replaced URLs manually in the database.
		 *
		 * @since 5.7.0
		 *
		 * @param bool $should_replace_insecure_home_url Whether insecure HTTP URLs to the site should be replaced.
		 */
		return apply_filters( 'wp_should_replace_insecure_home_url', $should_replace_insecure_home_url );
	}
endif;

// wp-includes/https-migration.php (WP 6.9.5)
if( ! function_exists( 'wp_replace_insecure_home_url' ) ) :
	function wp_replace_insecure_home_url( $content ) {
		if ( ! wp_should_replace_insecure_home_url() ) {
			return $content;
		}
	
		$https_url = home_url( '', 'https' );
		$http_url  = str_replace( 'https://', 'http://', $https_url );
	
		// Also replace potentially escaped URL.
		$escaped_https_url = str_replace( '/', '\/', $https_url );
		$escaped_http_url  = str_replace( '/', '\/', $http_url );
	
		return str_replace(
			array(
				$http_url,
				$escaped_http_url,
			),
			array(
				$https_url,
				$escaped_https_url,
			),
			$content
		);
	}
endif;

