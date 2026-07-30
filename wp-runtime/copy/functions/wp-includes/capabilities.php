<?php

// ------------------auto-generated---------------------

// wp-includes/capabilities.php (WP 6.8.6)
if( ! function_exists( 'wp_maybe_grant_resume_extensions_caps' ) ) :
	function wp_maybe_grant_resume_extensions_caps( $allcaps ) {
		// Even in a multisite, regular administrators should be able to resume plugins.
		if ( ! empty( $allcaps['activate_plugins'] ) ) {
			$allcaps['resume_plugins'] = true;
		}
	
		// Even in a multisite, regular administrators should be able to resume themes.
		if ( ! empty( $allcaps['switch_themes'] ) ) {
			$allcaps['resume_themes'] = true;
		}
	
		return $allcaps;
	}
endif;

// wp-includes/capabilities.php (WP 6.8.6)
if( ! function_exists( 'wp_maybe_grant_install_languages_cap' ) ) :
	function wp_maybe_grant_install_languages_cap( $allcaps ) {
		if ( ! empty( $allcaps['update_core'] ) || ! empty( $allcaps['install_plugins'] ) || ! empty( $allcaps['install_themes'] ) ) {
			$allcaps['install_languages'] = true;
		}
	
		return $allcaps;
	}
endif;

