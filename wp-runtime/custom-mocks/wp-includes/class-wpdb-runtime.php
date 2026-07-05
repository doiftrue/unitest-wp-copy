<?php
/**
 * Non-querying wpdb adapter used by SQL-building WordPress utilities.
 *
 * WordPress methods are provided by the auto-generated wpdb__Copied_Methods
 * trait. _real_escape() is runtime-adapted to work without a DB connection.
 */

namespace Unitest_WP_Copy;

class WPDB_Runtime {

	use wpdb__Copied_Methods;

	public string $postmeta = 'wp_postmeta';
	public string $commentmeta = 'wp_commentmeta';
	public string $termmeta = 'wp_termmeta';
	public string $usermeta = 'wp_usermeta';
	public string $blogmeta = 'wp_blogmeta';
	public string $sitemeta = 'wp_sitemeta';

	private bool $allow_unsafe_unquoted_parameters = true;

	/** Custom-adapted from WordPress 7.0 to work without a database connection. */
	public function _real_escape( $data ) {
		if ( ! is_scalar( $data ) ) {
			return '';
		}

		// Runtime adaptation: this adapter never owns a database connection.
		return $this->add_placeholder_escape( addslashes( $data ) );
	}

}
