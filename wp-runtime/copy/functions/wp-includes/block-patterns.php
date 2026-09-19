<?php

// ------------------auto-generated---------------------

// wp-includes/block-patterns.php (WP 6.5.11)
if( ! function_exists( 'wp_normalize_remote_block_pattern' ) ) :
	function wp_normalize_remote_block_pattern( $pattern ) {
		if ( isset( $pattern['block_types'] ) ) {
			$pattern['blockTypes'] = $pattern['block_types'];
			unset( $pattern['block_types'] );
		}
	
		if ( isset( $pattern['viewport_width'] ) ) {
			$pattern['viewportWidth'] = $pattern['viewport_width'];
			unset( $pattern['viewport_width'] );
		}
	
		return (array) $pattern;
	}
endif;

