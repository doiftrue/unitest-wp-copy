<?php

// ------------------auto-generated---------------------

// wp-includes/ms-blogs.php (WP 6.5.8)
if( ! function_exists( 'ms_is_switched' ) ) :
	function ms_is_switched() {
		return ! empty( $GLOBALS['_wp_switched_stack'] );
	}
endif;

// wp-includes/ms-blogs.php (WP 6.5.8)
if( ! function_exists( 'clean_site_details_cache' ) ) :
	function clean_site_details_cache( $site_id = 0 ) {
		$site_id = (int) $site_id;
		if ( ! $site_id ) {
			$site_id = get_current_blog_id();
		}
	
		wp_cache_delete( $site_id, 'site-details' );
		wp_cache_delete( $site_id, 'blog-details' );
	}
endif;

