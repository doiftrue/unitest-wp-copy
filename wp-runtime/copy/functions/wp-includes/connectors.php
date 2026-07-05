<?php

// ------------------auto-generated---------------------

// wp-includes/connectors.php (WP 7.0)
if( ! function_exists( '_wp_connectors_mask_api_key' ) ) :
	function _wp_connectors_mask_api_key( string $key ): string {
		if ( strlen( $key ) <= 4 ) {
			return $key;
		}
	
		return str_repeat( "\u{2022}", min( strlen( $key ) - 4, 16 ) ) . substr( $key, -4 );
	}
endif;

