<?php
/**
 * Runtime-adapted WordPress option functions.
 * Supports WP_Mock unit testing implementation.
 */

use Unitest_WP_Copy\WP_Mock_Utils;

/**
 * Retrieves an option from the in-memory runtime store.
 *
 * Known differences from WordPress core:
 * - Options exist only in $GLOBALS['stub_wp_options']; there is no database, cache, or saving.
 * - Values are returned with the same PHP type that was stored. WordPress usually returns
 *   database values as strings.
 * - Deprecated option names and special installation/setup behavior are not supported.
 * - Options are shared by all tests in the current PHP process and must be restored after use.
 * - Stored options have priority over WP_Mock handlers. This prevents a broad get_option()
 *   mock from changing runtime settings used by nested function calls.
 * - WP_Mock handles only options missing from the store. To override a stored option, change
 *   $GLOBALS['stub_wp_options'] or use its pre_option_* / option_* filter.
 *
 * Priorities:
 *   1. `pre_option_*` filters;
 *   2. Value from `$GLOBALS['stub_wp_options']`;
 *   3. WP_Mock handler for not existing option;
 *   4. `default_option_*` filter and $default_value.
 *
 * TODO: Consider a dedicated resettable in-memory option repository that can provide
 *       closer WordPress type/lifecycle semantics and be shared by retrieval and
 *       future mutation APIs without introducing database coupling.
 */
if ( ! function_exists( 'get_option' ) ) :
	function get_option( $option, $default_value = false ) {
		if ( is_scalar( $option ) ) {
			$option = trim( (string) $option );
		}

		if ( ! $option || ! is_string( $option ) ) {
			return false;
		}

		$pre = apply_filters( "pre_option_{$option}", false, $option, $default_value );
		$pre = apply_filters( 'pre_option', $pre, $option, $default_value );
		if ( false !== $pre ) {
			return $pre;
		}

		$options        = (array) ( $GLOBALS['stub_wp_options'] ?? [] );
		$passed_default = func_num_args() > 1;

		if ( array_key_exists( $option, $options ) ) {
			$value = $options[ $option ];
			if ( in_array( $option, [ 'siteurl', 'home', 'category_base', 'tag_base' ], true ) ) {
				$value = untrailingslashit( $value );
			}

			return apply_filters( "option_{$option}", maybe_unserialize( $value ), $option );
		}

		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		return apply_filters( "default_option_{$option}", $default_value, $option, $passed_default );
	}
endif;

/**
 * Retrieves a current-network option from the in-memory runtime store.
 *
 * Priorities:
 *   1. `pre_site_option_*` filters;
 *   2. Value from `$GLOBALS['stub_wp_site_options']`;
 *   3. WP_Mock handler for not existing option;
 *   4. `default_site_option_*` filter and $default_value.
 */
if ( ! function_exists( 'get_site_option' ) ) :
	function get_site_option( $option, $default_value = false, $deprecated = true ) {
		if ( ! is_multisite() ) {
			return get_option( $option, $default_value );
		}

		if ( is_scalar( $option ) ) {
			$option = trim( (string) $option );
		}

		if ( ! $option || ! is_string( $option ) ) {
			return false;
		}

		$network_id = (int) ( $GLOBALS['current_network_id'] ?? 1 );
		$pre        = apply_filters( "pre_site_option_{$option}", false, $option, $network_id, $default_value );
		$pre        = apply_filters( 'pre_site_option', $pre, $option, $network_id, $default_value );
		if ( false !== $pre ) {
			return $pre;
		}

		$options        = (array) ( $GLOBALS['stub_wp_site_options'] ?? [] );
		$passed_default = func_num_args() > 1;

		if ( array_key_exists( $option, $options ) ) {
			$value = $options[ $option ];

			return apply_filters( "site_option_{$option}", $value, $option, $network_id );
		}

		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		return apply_filters( "default_site_option_{$option}", $default_value, $option, $network_id, $passed_default );
	}
endif;
