<?php
/**
 * Mock implementations of WordPress i18n functions.
 * Supports WP_Mock unit testing implementation.
 */

if ( ! function_exists( '__' ) ) :
	function __( $text, $domain = 'default' ) {
		return wp_mock_has_handler( __FUNCTION__ )
			? wp_mock_call( __FUNCTION__, func_get_args() )
			: $text;
	}
endif;

if ( ! function_exists( '_e' ) ) :
	function _e( $text, $domain = 'default' ) {
		if ( wp_mock_has_handler( __FUNCTION__ ) ) {
			wp_mock_echo_call( __FUNCTION__, func_get_args() );
			return;
		}

		echo $text;
	}
endif;

if ( ! function_exists( '_x' ) ) :
	function _x( $text, $context, $domain = 'default' ) {
		return wp_mock_has_handler( __FUNCTION__ )
			? wp_mock_call( __FUNCTION__, func_get_args() )
			: $text;
	}
endif;

if ( ! function_exists( '_n' ) ) :
	function _n( $single, $plural, $number, $domain = 'default' ) {
		if ( wp_mock_has_handler( __FUNCTION__ ) ) {
			return wp_mock_call( __FUNCTION__, func_get_args() );
		}

		return $number <= 1 ? $single : $plural;
	}
endif;

if ( ! function_exists( '_nx' ) ) :
	function _nx( $single, $plural, $number, $context, $domain = 'default' ) {
		if ( wp_mock_has_handler( __FUNCTION__ ) ) {
			return wp_mock_call( __FUNCTION__, func_get_args() );
		}

		return $number <= 1 ? $single : $plural;
	}
endif;

if ( ! function_exists( 'esc_html__' ) ) :
	function esc_html__( $text, $domain = 'default' ) {
		return esc_html( __( $text, $domain ) );
	}
endif;

if ( ! function_exists( 'esc_html_e' ) ) :
	function esc_html_e( $text, $domain = 'default' ) {
		echo esc_html( _e( $text, $domain ) );
	}
endif;

if ( ! function_exists( 'esc_html_x' ) ) :
	function esc_html_x( $text, $context, $domain = 'default' ) {
		return esc_html( _x( $text, $domain ) );
	}
endif;

if ( ! function_exists( 'esc_attr__' ) ) :
	function esc_attr__( $text, $domain = 'default' ) {
		return esc_attr( __( $text, $domain ) );
	}
endif;

if ( ! function_exists( 'esc_attr_e' ) ) :
	function esc_attr_e( $text, $domain = 'default' ) {
		echo esc_attr( _e( $text, $domain ) );
	}
endif;

if ( ! function_exists( 'esc_attr_x' ) ) :
	function esc_attr_x( $text, $context, $domain = 'default' ) {
		return esc_attr( _x( $text, $domain ) );
	}
endif;
