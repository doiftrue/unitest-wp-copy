<?php

if ( ! function_exists( 'add_filter' ) ) :
	function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		// No operation performed
	}
endif;

if ( ! function_exists( 'apply_filters' ) ) :
	function apply_filters( $tag, $value ) {
		return $value;
	}
endif;

if ( ! function_exists( 'add_action' ) ) :
	function add_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		// No operation performed
	}
endif;

if ( ! function_exists( 'do_action' ) ) :
	function do_action( $tag, ...$args ) {
		// No operation performed
	}
endif;

if ( ! function_exists( '_x' ) ) :
	function _x( $text, $context, $domain = 'default' ) {
		return $text;
	}
endif;

if ( ! function_exists( '__' ) ) :
	function __( $text, $domain = 'default' ) {
		return $text;
	}
endif;

if ( ! function_exists( '_e' ) ) :
	function _e( $text, $domain = 'default' ) {
		echo $text;
	}
endif;

if ( ! function_exists( '_n' ) ) :
	function _n( $single, $plural, $number, $domain = 'default' ) {
		return $number === 1 ? $single : $plural;
	}
endif;

if ( ! function_exists( 'esc_html__' ) ) :
	function esc_html__( $text, $domain = 'default' ) {
		return $text;
	}
endif;

if ( ! function_exists( 'esc_html_e' ) ) :
	function esc_html_e( $text, $domain = 'default' ) {
		echo $text;
	}
endif;

if ( ! function_exists( 'esc_html_x' ) ) :
	function esc_html_x( $text, $context, $domain = 'default' ) {
		return $text;
	}
endif;

if ( ! function_exists( 'esc_attr__' ) ) :
	function esc_attr__( $text, $domain = 'default' ) {
		return $text;
	}
endif;

if ( ! function_exists( 'esc_attr_e' ) ) :
	function esc_attr_e( $text, $domain = 'default' ) {
		echo $text;
	}
endif;

if ( ! function_exists( 'esc_attr_x' ) ) :
	function esc_attr_x( $text, $context, $domain = 'default' ) {
		return $text;
	}
endif;
