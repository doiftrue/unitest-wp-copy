<?php

// ------------------auto-generated---------------------

// wp-includes/widgets.php (WP 6.8.5)
if( ! function_exists( 'wp_parse_widget_id' ) ) :
	function wp_parse_widget_id( $id ) {
		$parsed = array();
	
		if ( preg_match( '/^(.+)-(\d+)$/', $id, $matches ) ) {
			$parsed['id_base'] = $matches[1];
			$parsed['number']  = (int) $matches[2];
		} else {
			// Likely an old single widget.
			$parsed['id_base'] = $id;
		}
	
		return $parsed;
	}
endif;

// wp-includes/widgets.php (WP 6.8.5)
if( ! function_exists( '_get_widget_id_base' ) ) :
	function _get_widget_id_base( $id ) {
		return preg_replace( '/-[0-9]+$/', '', $id );
	}
endif;

