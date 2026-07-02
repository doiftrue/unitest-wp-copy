<?php
/**
 * Mock implementations of WordPress functions from wp-includes/theme.php.
 * Supports WP_Mock unit testing implementation.
 */

use Unitest_WP_Copy\WP_Mock_Utils;

if ( ! function_exists( 'get_stylesheet_directory' ) ) :
	function get_stylesheet_directory() {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		$stylesheet     = get_stylesheet();
		$content_dir    = defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';
		$theme_root     = wp_normalize_path( $content_dir . '/themes' );
		$stylesheet_dir = "$theme_root/$stylesheet";

		return apply_filters( 'stylesheet_directory', $stylesheet_dir, $stylesheet, $theme_root );
	}
endif;

if ( ! function_exists( 'get_stylesheet_directory_uri' ) ) :
	function get_stylesheet_directory_uri() {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		$stylesheet         = str_replace( '%2F', '/', rawurlencode( get_stylesheet() ) );
		$theme_root_uri     = wp_normalize_path( home_url() . '/wp-content/themes' );
		$stylesheet_dir_uri = "$theme_root_uri/$stylesheet";

		return apply_filters( 'stylesheet_directory_uri', $stylesheet_dir_uri, $stylesheet, $theme_root_uri );
	}
endif;

if ( ! function_exists( 'get_template_directory' ) ) :
	function get_template_directory() {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		$template     = get_template();
		$content_dir  = defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';
		$theme_root   = wp_normalize_path( $content_dir . '/themes' );
		$template_dir = "$theme_root/$template";

		return apply_filters( 'template_directory', $template_dir, $template, $theme_root );
	}
endif;

if ( ! function_exists( 'get_template_directory_uri' ) ) :
	function get_template_directory_uri() {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		$template         = str_replace( '%2F', '/', rawurlencode( get_template() ) );
		$theme_root_uri   = wp_normalize_path( home_url() . '/wp-content/themes' );
		$template_dir_uri = "$theme_root_uri/$template";

		return apply_filters( 'template_directory_uri', $template_dir_uri, $template, $theme_root_uri );
	}
endif;
