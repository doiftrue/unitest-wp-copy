<?php

// ------------------auto-generated---------------------

// wp-includes/sitemaps.php (WP 6.9.4)
if( ! function_exists( 'wp_sitemaps_get_max_urls' ) ) :
	function wp_sitemaps_get_max_urls( $object_type ) {
		/**
		 * Filters the maximum number of URLs displayed on a sitemap.
		 *
		 * @since 5.5.0
		 *
		 * @param int    $max_urls    The maximum number of URLs included in a sitemap. Default 2000.
		 * @param string $object_type Object type for sitemap to be filtered (e.g. 'post', 'term', 'user').
		 */
		return apply_filters( 'wp_sitemaps_max_urls', 2000, $object_type );
	}
endif;

