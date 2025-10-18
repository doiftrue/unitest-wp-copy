<?php

// ------------------auto-generated---------------------

// wp-includes/l10n.php (WP 6.8.3)
if( ! function_exists( 'get_locale' ) ) :
	function get_locale() {
		global $locale, $wp_local_package;
	
		if ( isset( $locale ) ) {
			/** This filter is documented in wp-includes/l10n.php */
			return apply_filters( 'locale', $locale );
		}
	
		if ( isset( $wp_local_package ) ) {
			$locale = $wp_local_package;
		}
	
		// WPLANG was defined in wp-config.
		if ( defined( 'WPLANG' ) ) {
			$locale = WPLANG;
		}
	
		// If multisite, check options.
		if ( is_multisite() ) {
			// Don't check blog option when installing.
			if ( wp_installing() ) {
				$ms_locale = WPCOPY_OPTION__WPLANG;
			} else {
				$ms_locale = WPCOPY_OPTION__WPLANG;
				if ( false === $ms_locale ) {
					$ms_locale = WPCOPY_OPTION__WPLANG;
				}
			}
	
			if ( false !== $ms_locale ) {
				$locale = $ms_locale;
			}
		} else {
			$db_locale = WPCOPY_OPTION__WPLANG;
			if ( false !== $db_locale ) {
				$locale = $db_locale;
			}
		}
	
		if ( empty( $locale ) ) {
			$locale = 'en_US';
		}
	
		/**
		 * Filters the locale ID of the WordPress installation.
		 *
		 * @since 1.5.0
		 *
		 * @param string $locale The locale ID.
		 */
		return apply_filters( 'locale', $locale );
	}
endif;

