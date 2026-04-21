<?php
/**
 * This file contains adapted-functions that overlaps wp-core-copied
 * functions for current wp version.
 */

/**
 * INFO: This mock change `wp_load_alloption()` with `$GLOBALS['stub_wp_options']->blog_charset`.
 *
 * See: wp-includes/formatting.php (WP 6.5.8)
 */
if( ! function_exists( '_wp_specialchars' ) ) :
	function _wp_specialchars( $text, $quote_style = ENT_NOQUOTES, $charset = false, $double_encode = false ) {
		$text = (string) $text;

		if ( 0 === strlen( $text ) ) {
			return '';
		}

		// Don't bother if there are no specialchars - saves some processing.
		if ( ! preg_match( '/[&<>"\']/', $text ) ) {
			return $text;
		}

		// Account for the previous behavior of the function when the $quote_style is not an accepted value.
		if ( empty( $quote_style ) ) {
			$quote_style = ENT_NOQUOTES;
		} elseif ( ENT_XML1 === $quote_style ) {
			$quote_style = ENT_QUOTES | ENT_XML1;
		} elseif ( ! in_array( $quote_style, array( ENT_NOQUOTES, ENT_COMPAT, ENT_QUOTES, 'single', 'double' ), true ) ) {
			$quote_style = ENT_QUOTES;
		}

		// Store the site charset as a static to avoid multiple calls to wp_load_alloptions().
		if ( ! $charset ) {
			static $_charset = null;
			if ( ! isset( $_charset ) ) {
				$_charset   = $GLOBALS['stub_wp_options']->blog_charset;
			}
			$charset = $_charset;
		}

		if ( in_array( $charset, array( 'utf8', 'utf-8', 'UTF8' ), true ) ) {
			$charset = 'UTF-8';
		}

		$_quote_style = $quote_style;

		if ( 'double' === $quote_style ) {
			$quote_style  = ENT_COMPAT;
			$_quote_style = ENT_COMPAT;
		} elseif ( 'single' === $quote_style ) {
			$quote_style = ENT_NOQUOTES;
		}

		if ( ! $double_encode ) {
			/*
			 * Guarantee every &entity; is valid, convert &garbage; into &amp;garbage;
			 * This is required for PHP < 5.4.0 because ENT_HTML401 flag is unavailable.
			 */
			$text = wp_kses_normalize_entities( $text, ( $quote_style & ENT_XML1 ) ? 'xml' : 'html' );
		}

		$text = htmlspecialchars( $text, $quote_style, $charset, $double_encode );

		// Back-compat.
		if ( 'single' === $_quote_style ) {
			$text = str_replace( "'", '&#039;', $text );
		}

		return $text;
	}
endif;
