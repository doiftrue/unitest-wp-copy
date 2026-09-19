<?php

// ------------------auto-generated---------------------

// wp-includes/view-config.php (WP 7.1.1)
if( ! function_exists( 'wp_get_entity_view_config_hook_name' ) ) :
	function wp_get_entity_view_config_hook_name( $kind, $name ) {
		return strtolower( "get_entity_view_config_{$kind}_{$name}" );
	}
endif;

