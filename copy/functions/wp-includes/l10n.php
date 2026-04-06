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
				$ms_locale = $GLOBALS['stub_wp_options']->WPLANG;
			} else {
				$ms_locale = $GLOBALS['stub_wp_options']->WPLANG;
				if ( false === $ms_locale ) {
					$ms_locale = $GLOBALS['stub_wp_options']->WPLANG;
				}
			}
	
			if ( false !== $ms_locale ) {
				$locale = $ms_locale;
			}
		} else {
			$db_locale = $GLOBALS['stub_wp_options']->WPLANG;
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

// wp-includes/l10n.php (WP 6.8.3)
if( ! function_exists( 'before_last_bar' ) ) :
	function before_last_bar( $text ) {
		$last_bar = strrpos( $text, '|' );
		if ( false === $last_bar ) {
			return $text;
		} else {
			return substr( $text, 0, $last_bar );
		}
	}
endif;

// wp-includes/l10n.php (WP 6.8.3)
if( ! function_exists( '_n_noop' ) ) :
	function _n_noop( $singular, $plural, $domain = null ) {
		return array(
			0          => $singular,
			1          => $plural,
			'singular' => $singular,
			'plural'   => $plural,
			'context'  => null,
			'domain'   => $domain,
		);
	}
endif;

// wp-includes/l10n.php (WP 6.8.3)
if( ! function_exists( '_nx_noop' ) ) :
	function _nx_noop( $singular, $plural, $context, $domain = null ) {
		return array(
			0          => $singular,
			1          => $plural,
			2          => $context,
			'singular' => $singular,
			'plural'   => $plural,
			'context'  => $context,
			'domain'   => $domain,
		);
	}
endif;

// wp-includes/l10n.php (WP 6.8.3)
if( ! function_exists( 'translate_nooped_plural' ) ) :
	function translate_nooped_plural( $nooped_plural, $count, $domain = 'default' ) {
		if ( $nooped_plural['domain'] ) {
			$domain = $nooped_plural['domain'];
		}
	
		if ( $nooped_plural['context'] ) {
			return _nx( $nooped_plural['singular'], $nooped_plural['plural'], $count, $nooped_plural['context'], $domain );
		} else {
			return _n( $nooped_plural['singular'], $nooped_plural['plural'], $count, $domain );
		}
	}
endif;

// wp-includes/l10n.php (WP 6.8.3)
if( ! function_exists( 'is_rtl' ) ) :
	function is_rtl() {
		global $wp_locale;
		if ( ! ( $wp_locale instanceof WP_Locale ) ) {
			return false;
		}
		return $wp_locale->is_rtl();
	}
endif;

// wp-includes/l10n.php (WP 6.8.3)
if( ! function_exists( 'wp_get_list_item_separator' ) ) :
	function wp_get_list_item_separator() {
		global $wp_locale;
	
		if ( ! ( $wp_locale instanceof WP_Locale ) ) {
			// Default value of WP_Locale::get_list_item_separator().
			/* translators: Used between list items, there is a space after the comma. */
			return __( ', ' );
		}
	
		return $wp_locale->get_list_item_separator();
	}
endif;

// wp-includes/l10n.php (WP 6.8.3)
if( ! function_exists( 'wp_get_word_count_type' ) ) :
	function wp_get_word_count_type() {
		global $wp_locale;
	
		if ( ! ( $wp_locale instanceof WP_Locale ) ) {
			// Default value of WP_Locale::get_word_count_type().
			return 'words';
		}
	
		return $wp_locale->get_word_count_type();
	}
endif;

