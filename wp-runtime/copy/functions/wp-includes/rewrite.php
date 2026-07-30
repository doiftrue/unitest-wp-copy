<?php

// ------------------auto-generated---------------------

// wp-includes/rewrite.php (WP 6.9.5)
if( ! function_exists( '_wp_filter_taxonomy_base' ) ) :
	function _wp_filter_taxonomy_base( $base ) {
		if ( ! empty( $base ) ) {
			$base = preg_replace( '|^/index\.php/|', '', $base );
			$base = trim( $base, '/' );
		}
		return $base;
	}
endif;

