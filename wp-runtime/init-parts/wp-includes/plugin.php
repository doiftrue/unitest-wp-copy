<?php
/**
 * Copy of init part of wp-includes/plugin.php from WordPress 6.8.3
 */

/** @var WP_Hook[] $wp_filter */
global $wp_filter;

/** @var int[] $wp_actions */
global $wp_actions;

/** @var int[] $wp_filters */
global $wp_filters;

/** @var string[] $wp_current_filter */
global $wp_current_filter;

if ( $wp_filter ) {
	$wp_filter = WP_Hook::build_preinitialized_hooks( $wp_filter );
} else {
	$wp_filter = array();
}

if ( ! isset( $wp_actions ) ) {
	$wp_actions = array();
}

if ( ! isset( $wp_filters ) ) {
	$wp_filters = array();
}

if ( ! isset( $wp_current_filter ) ) {
	$wp_current_filter = array();
}
