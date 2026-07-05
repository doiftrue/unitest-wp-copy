<?php

// ------------------auto-generated---------------------

// wp-includes/revision.php (WP 6.6.5)
if( ! function_exists( '_wp_get_post_revision_version' ) ) :
	function _wp_get_post_revision_version( $revision ) {
		if ( is_object( $revision ) ) {
			$revision = get_object_vars( $revision );
		} elseif ( ! is_array( $revision ) ) {
			return false;
		}
	
		if ( preg_match( '/^\d+-(?:autosave|revision)-v(\d+)$/', $revision['post_name'], $matches ) ) {
			return (int) $matches[1];
		}
	
		return 0;
	}
endif;

