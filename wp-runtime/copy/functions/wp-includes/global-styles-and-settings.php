<?php

// ------------------auto-generated---------------------

// wp-includes/global-styles-and-settings.php (WP 6.5.8)
if( ! function_exists( 'wp_get_block_name_from_theme_json_path' ) ) :
	function wp_get_block_name_from_theme_json_path( $path ) {
		// Block name is expected to be the third item after 'styles' and 'blocks'.
		if (
			count( $path ) >= 3
			&& 'styles' === $path[0]
			&& 'blocks' === $path[1]
			&& str_contains( $path[2], '/' )
		) {
			return $path[2];
		}
	
		/*
		 * As fallback and for backward compatibility, allow any core block to be
		 * at any position.
		 */
		$result = array_values(
			array_filter(
				$path,
				static function ( $item ) {
					if ( str_contains( $item, 'core/' ) ) {
						return true;
					}
					return false;
				}
			)
		);
		if ( isset( $result[0] ) ) {
			return $result[0];
		}
		return '';
	}
endif;

