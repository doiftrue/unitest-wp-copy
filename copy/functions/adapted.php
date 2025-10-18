<?php
/**
 * Copy of WP functions with a little adaptation to work as is.
 * Some not urgent dependencies removed.
 */

// wp-includes/formatting.php (WP 6.8.1)
if( ! function_exists( 'wp_allowed_protocols' ) ) :
function wp_allowed_protocols() {
	static $protocols = array();

	if ( empty( $protocols ) ) {
		$protocols = array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'irc6', 'ircs', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'sms', 'svn', 'tel', 'fax', 'xmpp', 'webcal', 'urn' );
	}

//	if ( ! did_action( 'wp_loaded' ) ) {
		/**
		 * Filters the list of protocols allowed in HTML attributes.
		 *
		 * @since 3.0.0
		 *
		 * @param string[] $protocols Array of allowed protocols e.g. 'http', 'ftp', 'tel', and more.
		 */
		$protocols = array_unique( (array) apply_filters( 'kses_allowed_protocols', $protocols ) );
//	}

	return $protocols;
}
endif;

// wp-includes/formatting.php (WP 6.8.3)
if( ! function_exists( 'capital_P_dangit' ) ) :
	function capital_P_dangit( $text ) {
		// Simple replacement for titles.
//		$current_filter = current_filter();
//		if ( 'the_title' === $current_filter || 'wp_title' === $current_filter ) {
//			return str_replace( 'Wordpress', 'WordPress', $text );
//		}
		// Still here? Use the more judicious replacement.
		static $dblq = false;
		if ( false === $dblq ) {
			$dblq = _x( '&#8220;', 'opening curly double quote' );
		}
		return str_replace(
			array( ' Wordpress', '&#8216;Wordpress', $dblq . 'Wordpress', '>Wordpress', '(Wordpress' ),
			array( ' WordPress', '&#8216;WordPress', $dblq . 'WordPress', '>WordPress', '(WordPress' ),
			$text
		);
	}
endif;

// wp-includes/l10n.php (WP 6.8.3)
if( ! function_exists( 'wp_get_word_count_type' ) ) :
	function wp_get_word_count_type() {
//		global $wp_locale;
//
//		if ( ! ( $wp_locale instanceof WP_Locale ) ) {
//			// Default value of WP_Locale::get_word_count_type().
			return 'words';
//		}
//
//		return $wp_locale->get_word_count_type();
	}
endif;
