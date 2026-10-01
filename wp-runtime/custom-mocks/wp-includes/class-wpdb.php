<?php
/**
 * Non-querying wpdb adapter used by SQL-building WordPress utilities.
 *
 * WordPress methods are provided by the auto-generated wpdb__Copied_Methods
 * trait. _real_escape() is runtime-adapted to work without a DB connection.
 */

namespace Unitest_WP_Copy;

#[AllowDynamicProperties]
class wpdb__Runtime {

	use wpdb__Copied_Methods;

	public string $base_prefix = 'wp_';
	public string $prefix = 'wp_';
	public int $blogid = 1;
	public int $siteid = 1;
	public string $posts = 'wp_posts';
	public string $comments = 'wp_comments';
	public string $users = 'wp_users';
	public string $blogs = 'wp_blogs';
	public string $postmeta = 'wp_postmeta';
	public string $commentmeta = 'wp_commentmeta';
	public string $termmeta = 'wp_termmeta';
	public string $usermeta = 'wp_usermeta';
	public string $blogmeta = 'wp_blogmeta';
	public string $sitemeta = 'wp_sitemeta';

	private bool $allow_unsafe_unquoted_parameters = true;

	/** Custom-adapted from WordPress 7.1 for runtime multisite prefix switching. */
	public function get_blog_prefix( $blog_id = null ): string {
		$blog_id = null === $blog_id ? $this->blogid : (int) $blog_id;

		return $blog_id > 1 ? "{$this->base_prefix}{$blog_id}_" : $this->base_prefix;
	}

	/** Custom-adapted from WordPress 7.1 for the reduced runtime table set. */
	public function set_blog_id( $blog_id, $network_id = 0 ): int {
		if ( ! empty( $network_id ) ) {
			$this->siteid = (int) $network_id;
		}

		$old_blog_id  = $this->blogid;
		$this->blogid = (int) $blog_id;
		$this->prefix = $this->get_blog_prefix();

		foreach ( [ 'posts', 'comments', 'postmeta', 'commentmeta', 'termmeta' ] as $table ) {
			$this->$table = $this->prefix . $table;
		}

		return $old_blog_id;
	}

	/** Custom-adapted from WordPress 7.1 to work without a database connection. */
	public function _real_escape( $data ) {
		if ( ! is_scalar( $data ) ) {
			return '';
		}

		// Runtime adaptation: this adapter never owns a database connection.
		return $this->add_placeholder_escape( addslashes( $data ) );
	}

}
