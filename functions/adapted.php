<?php
/**
 * Copy of WP functions with a little adaptation to work as is.
 * Some not urgent dependencies removed.
 */

// wp-includes/formatting.php (WP 6.8.1)
function wp_allowed_protocols() {
	static $protocols = array();

	if ( empty( $protocols ) ) {
		$protocols = array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'irc6', 'ircs', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'sms', 'svn', 'tel', 'fax', 'xmpp', 'webcal', 'urn' );
	}

	//if ( ! did_action( 'wp_loaded' ) ) {
		/**
		 * Filters the list of protocols allowed in HTML attributes.
		 *
		 * @since 3.0.0
		 *
		 * @param string[] $protocols Array of allowed protocols e.g. 'http', 'ftp', 'tel', and more.
		 */
		$protocols = array_unique( (array) apply_filters( 'kses_allowed_protocols', $protocols ) );
	//}

	return $protocols;
}
