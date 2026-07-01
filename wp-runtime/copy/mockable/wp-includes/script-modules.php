<?php

// ------------------auto-generated---------------------

// wp-includes/script-modules.php (WP 6.7.5)
if( ! function_exists( 'wp_script_modules' ) ) :
	function wp_script_modules(): WP_Script_Modules {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_script_modules;
	
		if ( ! ( $wp_script_modules instanceof WP_Script_Modules ) ) {
			$wp_script_modules = new WP_Script_Modules();
		}
	
		return $wp_script_modules;
	}
endif;

