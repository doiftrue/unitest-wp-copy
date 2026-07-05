<?php

// ------------------auto-generated---------------------

// wp-includes/comment.php (WP 7.0)
if( ! function_exists( 'wp_cache_set_comments_last_changed' ) ) :
	function wp_cache_set_comments_last_changed() {
		wp_cache_set_last_changed( 'comment' );
	}
endif;

// wp-includes/comment.php (WP 7.0)
if( ! function_exists( 'wp_register_comment_personal_data_exporter' ) ) :
	function wp_register_comment_personal_data_exporter( $exporters ) {
		$exporters['wordpress-comments'] = array(
			'exporter_friendly_name' => __( 'WordPress Comments' ),
			'callback'               => 'wp_comments_personal_data_exporter',
		);
	
		return $exporters;
	}
endif;

// wp-includes/comment.php (WP 7.0)
if( ! function_exists( 'wp_register_comment_personal_data_eraser' ) ) :
	function wp_register_comment_personal_data_eraser( $erasers ) {
		$erasers['wordpress-comments'] = array(
			'eraser_friendly_name' => __( 'WordPress Comments' ),
			'callback'             => 'wp_comments_personal_data_eraser',
		);
	
		return $erasers;
	}
endif;

// wp-includes/comment.php (WP 7.0)
if( ! function_exists( '_clear_modified_cache_on_transition_comment_status' ) ) :
	function _clear_modified_cache_on_transition_comment_status( $new_status, $old_status ) {
		if ( 'approved' === $new_status || 'approved' === $old_status ) {
			$data = array();
			foreach ( array( 'server', 'gmt', 'blog' ) as $timezone ) {
				$data[] = "lastcommentmodified:$timezone";
			}
			wp_cache_delete_multiple( $data, 'timeinfo' );
		}
	}
endif;

// wp-includes/comment.php (WP 7.0)
if( ! function_exists( 'get_comment_statuses' ) ) :
	function get_comment_statuses() {
		$status = array(
			'hold'    => __( 'Unapproved' ),
			'approve' => _x( 'Approved', 'comment status' ),
			'spam'    => _x( 'Spam', 'comment status' ),
			'trash'   => _x( 'Trash', 'comment status' ),
		);
	
		return $status;
	}
endif;

// wp-includes/comment.php (WP 7.0)
if( ! function_exists( 'separate_comments' ) ) :
	function separate_comments( &$comments ) {
		$comments_by_type = array(
			'comment'   => array(),
			'trackback' => array(),
			'pingback'  => array(),
			'pings'     => array(),
		);
	
		$count = count( $comments );
	
		for ( $i = 0; $i < $count; $i++ ) {
			$type = $comments[ $i ]->comment_type;
	
			if ( empty( $type ) ) {
				$type = 'comment';
			}
	
			$comments_by_type[ $type ][] = &$comments[ $i ];
	
			if ( 'trackback' === $type || 'pingback' === $type ) {
				$comments_by_type['pings'][] = &$comments[ $i ];
			}
		}
	
		return $comments_by_type;
	}
endif;

// wp-includes/comment.php (WP 7.0)
if( ! function_exists( 'clean_comment_cache' ) ) :
	function clean_comment_cache( $ids ) {
		$comment_ids = (array) $ids;
		wp_cache_delete_multiple( $comment_ids, 'comment' );
		foreach ( $comment_ids as $id ) {
			/**
			 * Fires immediately after a comment has been removed from the object cache.
			 *
			 * @since 4.5.0
			 *
			 * @param int $id Comment ID.
			 */
			do_action( 'clean_comment_cache', $id );
		}
	
		wp_cache_set_comments_last_changed();
	}
endif;

// wp-includes/comment.php (WP 7.0)
if( ! function_exists( 'wp_throttle_comment_flood' ) ) :
	function wp_throttle_comment_flood( $block, $time_lastcomment, $time_newcomment ) {
		if ( $block ) { // A plugin has already blocked... we'll let that decision stand.
			return $block;
		}
		if ( ( $time_newcomment - $time_lastcomment ) < 15 ) {
			return true;
		}
		return false;
	}
endif;

// wp-includes/comment.php (WP 7.0)
if( ! function_exists( 'wp_filter_comment' ) ) :
	function wp_filter_comment( $commentdata ) {
		if ( isset( $commentdata['user_ID'] ) ) {
			/**
			 * Filters the comment author's user ID before it is set.
			 *
			 * The first time this filter is evaluated, `user_ID` is checked
			 * (for back-compat), followed by the standard `user_id` value.
			 *
			 * @since 1.5.0
			 *
			 * @param int $user_id The comment author's user ID.
			 */
			$commentdata['user_id'] = apply_filters( 'pre_user_id', $commentdata['user_ID'] );
		} elseif ( isset( $commentdata['user_id'] ) ) {
			/** This filter is documented in wp-includes/comment.php */
			$commentdata['user_id'] = apply_filters( 'pre_user_id', $commentdata['user_id'] );
		}
	
		/**
		 * Filters the comment author's browser user agent before it is set.
		 *
		 * @since 1.5.0
		 *
		 * @param string $comment_agent The comment author's browser user agent.
		 */
		$commentdata['comment_agent'] = apply_filters( 'pre_comment_user_agent', ( $commentdata['comment_agent'] ?? '' ) );
		/** This filter is documented in wp-includes/comment.php */
		$commentdata['comment_author'] = apply_filters( 'pre_comment_author_name', $commentdata['comment_author'] );
		/**
		 * Filters the comment content before it is set.
		 *
		 * @since 1.5.0
		 *
		 * @param string $comment_content The comment content.
		 */
		$commentdata['comment_content'] = apply_filters( 'pre_comment_content', $commentdata['comment_content'] );
		/**
		 * Filters the comment author's IP address before it is set.
		 *
		 * @since 1.5.0
		 *
		 * @param string $comment_author_ip The comment author's IP address.
		 */
		$commentdata['comment_author_IP'] = apply_filters( 'pre_comment_user_ip', $commentdata['comment_author_IP'] );
		/** This filter is documented in wp-includes/comment.php */
		$commentdata['comment_author_url'] = apply_filters( 'pre_comment_author_url', $commentdata['comment_author_url'] );
		/** This filter is documented in wp-includes/comment.php */
		$commentdata['comment_author_email'] = apply_filters( 'pre_comment_author_email', $commentdata['comment_author_email'] );
	
		$commentdata['filtered'] = true;
	
		return $commentdata;
	}
endif;

