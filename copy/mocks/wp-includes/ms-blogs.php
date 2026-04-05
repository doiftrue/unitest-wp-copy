<?php
/**
 * Mock implementations of WordPress functions.
 * Supports WP_Mock unit testing implementation.
 */

use WP_Mock\Functions\Handler;

if ( ! function_exists( 'switch_to_blog' ) ) :
	function switch_to_blog( $new_blog_id, $deprecated = null ) {
		if ( class_exists( Handler::class ) ) {
			return Handler::handlePredefinedReturnFunction( __FUNCTION__, func_get_args() );
		}

		$prev_blog_id = (int) ( $GLOBALS['blog_id'] ?? 1 );

		if ( ! isset( $GLOBALS['_wp_switched_stack'] ) || ! is_array( $GLOBALS['_wp_switched_stack'] ) ) {
			$GLOBALS['_wp_switched_stack'] = [];
		}

		$GLOBALS['_wp_switched_stack'][] = $prev_blog_id;
		$GLOBALS['blog_id']              = (int) $new_blog_id;
		$GLOBALS['current_blog_id']      = (int) $new_blog_id;
		$GLOBALS['switched']             = true;

		return true;
	}
endif;

if ( ! function_exists( 'restore_current_blog' ) ) :
	function restore_current_blog() {
		if ( class_exists( Handler::class ) ) {
			return Handler::handlePredefinedReturnFunction( __FUNCTION__, func_get_args() );
		}

		if ( empty( $GLOBALS['_wp_switched_stack'] ) || ! is_array( $GLOBALS['_wp_switched_stack'] ) ) {
			$GLOBALS['switched'] = false;
			return false;
		}

		$blog_id                  = array_pop( $GLOBALS['_wp_switched_stack'] );
		$GLOBALS['blog_id']       = (int) $blog_id;
		$GLOBALS['current_blog_id'] = (int) $blog_id;
		$GLOBALS['switched']      = ! empty( $GLOBALS['_wp_switched_stack'] );

		return true;
	}
endif;
