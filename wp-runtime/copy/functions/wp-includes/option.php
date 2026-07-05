<?php

// ------------------auto-generated---------------------

// wp-includes/option.php (WP 6.5.8)
if( ! function_exists( 'filter_default_option' ) ) :
	function filter_default_option( $default_value, $option, $passed_default ) {
		if ( $passed_default ) {
			return $default_value;
		}
	
		$registered = get_registered_settings();
		if ( empty( $registered[ $option ] ) ) {
			return $default_value;
		}
	
		return $registered[ $option ]['default'];
	}
endif;

// wp-includes/option.php (WP 6.5.8)
if( ! function_exists( 'register_setting' ) ) :
	function register_setting( $option_group, $option_name, $args = array() ) {
		global $new_allowed_options, $wp_registered_settings;
	
		/*
		 * In 5.5.0, the `$new_whitelist_options` global variable was renamed to `$new_allowed_options`.
		 * Please consider writing more inclusive code.
		 */
		$GLOBALS['new_whitelist_options'] = &$new_allowed_options;
	
		$defaults = array(
			'type'              => 'string',
			'group'             => $option_group,
			'description'       => '',
			'sanitize_callback' => null,
			'show_in_rest'      => false,
		);
	
		// Back-compat: old sanitize callback is added.
		if ( is_callable( $args ) ) {
			$args = array(
				'sanitize_callback' => $args,
			);
		}
	
		/**
		 * Filters the registration arguments when registering a setting.
		 *
		 * @since 4.7.0
		 *
		 * @param array  $args         Array of setting registration arguments.
		 * @param array  $defaults     Array of default arguments.
		 * @param string $option_group Setting group.
		 * @param string $option_name  Setting name.
		 */
		$args = apply_filters( 'register_setting_args', $args, $defaults, $option_group, $option_name );
	
		$args = wp_parse_args( $args, $defaults );
	
		// Require an item schema when registering settings with an array type.
		if ( false !== $args['show_in_rest'] && 'array' === $args['type'] && ( ! is_array( $args['show_in_rest'] ) || ! isset( $args['show_in_rest']['schema']['items'] ) ) ) {
			_doing_it_wrong( __FUNCTION__, __( 'When registering an "array" setting to show in the REST API, you must specify the schema for each array item in "show_in_rest.schema.items".' ), '5.4.0' );
		}
	
		if ( ! is_array( $wp_registered_settings ) ) {
			$wp_registered_settings = array();
		}
	
		if ( 'misc' === $option_group ) {
			_deprecated_argument(
				__FUNCTION__,
				'3.0.0',
				sprintf(
					/* translators: %s: misc */
					__( 'The "%s" options group has been removed. Use another settings group.' ),
					'misc'
				)
			);
			$option_group = 'general';
		}
	
		if ( 'privacy' === $option_group ) {
			_deprecated_argument(
				__FUNCTION__,
				'3.5.0',
				sprintf(
					/* translators: %s: privacy */
					__( 'The "%s" options group has been removed. Use another settings group.' ),
					'privacy'
				)
			);
			$option_group = 'reading';
		}
	
		$new_allowed_options[ $option_group ][] = $option_name;
	
		if ( ! empty( $args['sanitize_callback'] ) ) {
			add_filter( "sanitize_option_{$option_name}", $args['sanitize_callback'] );
		}
		if ( array_key_exists( 'default', $args ) ) {
			add_filter( "default_option_{$option_name}", 'filter_default_option', 10, 3 );
		}
	
		/**
		 * Fires immediately before the setting is registered but after its filters are in place.
		 *
		 * @since 5.5.0
		 *
		 * @param string $option_group Setting group.
		 * @param string $option_name  Setting name.
		 * @param array  $args         Array of setting registration arguments.
		 */
		do_action( 'register_setting', $option_group, $option_name, $args );
	
		$wp_registered_settings[ $option_name ] = $args;
	}
endif;

// wp-includes/option.php (WP 6.5.8)
if( ! function_exists( 'unregister_setting' ) ) :
	function unregister_setting( $option_group, $option_name, $deprecated = '' ) {
		global $new_allowed_options, $wp_registered_settings;
	
		/*
		 * In 5.5.0, the `$new_whitelist_options` global variable was renamed to `$new_allowed_options`.
		 * Please consider writing more inclusive code.
		 */
		$GLOBALS['new_whitelist_options'] = &$new_allowed_options;
	
		if ( 'misc' === $option_group ) {
			_deprecated_argument(
				__FUNCTION__,
				'3.0.0',
				sprintf(
					/* translators: %s: misc */
					__( 'The "%s" options group has been removed. Use another settings group.' ),
					'misc'
				)
			);
			$option_group = 'general';
		}
	
		if ( 'privacy' === $option_group ) {
			_deprecated_argument(
				__FUNCTION__,
				'3.5.0',
				sprintf(
					/* translators: %s: privacy */
					__( 'The "%s" options group has been removed. Use another settings group.' ),
					'privacy'
				)
			);
			$option_group = 'reading';
		}
	
		$pos = false;
		if ( isset( $new_allowed_options[ $option_group ] ) ) {
			$pos = array_search( $option_name, (array) $new_allowed_options[ $option_group ], true );
		}
	
		if ( false !== $pos ) {
			unset( $new_allowed_options[ $option_group ][ $pos ] );
		}
	
		if ( '' !== $deprecated ) {
			_deprecated_argument(
				__FUNCTION__,
				'4.7.0',
				sprintf(
					/* translators: 1: $sanitize_callback, 2: register_setting() */
					__( '%1$s is deprecated. The callback from %2$s is used instead.' ),
					'<code>$sanitize_callback</code>',
					'<code>register_setting()</code>'
				)
			);
			remove_filter( "sanitize_option_{$option_name}", $deprecated );
		}
	
		if ( isset( $wp_registered_settings[ $option_name ] ) ) {
			// Remove the sanitize callback if one was set during registration.
			if ( ! empty( $wp_registered_settings[ $option_name ]['sanitize_callback'] ) ) {
				remove_filter( "sanitize_option_{$option_name}", $wp_registered_settings[ $option_name ]['sanitize_callback'] );
			}
	
			// Remove the default filter if a default was provided during registration.
			if ( array_key_exists( 'default', $wp_registered_settings[ $option_name ] ) ) {
				remove_filter( "default_option_{$option_name}", 'filter_default_option', 10 );
			}
	
			/**
			 * Fires immediately before the setting is unregistered and after its filters have been removed.
			 *
			 * @since 5.5.0
			 *
			 * @param string $option_group Setting group.
			 * @param string $option_name  Setting name.
			 */
			do_action( 'unregister_setting', $option_group, $option_name );
	
			unset( $wp_registered_settings[ $option_name ] );
		}
	}
endif;

