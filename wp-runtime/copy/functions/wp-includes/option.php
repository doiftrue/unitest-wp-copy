<?php

// ------------------auto-generated---------------------

// wp-includes/option.php (WP 7.0)
if( ! function_exists( 'wp_autoload_values_to_autoload' ) ) :
	function wp_autoload_values_to_autoload() {
		$autoload_values = array( 'yes', 'on', 'auto-on', 'auto' );
	
		/**
		 * Filters the autoload values that should be considered for autoloading from the options table.
		 *
		 * The filter can only be used to remove autoload values from the default list.
		 *
		 * @since 6.6.0
		 *
		 * @param string[] $autoload_values Autoload values used to autoload option.
		 *                               Default list contains 'yes', 'on', 'auto-on', and 'auto'.
		 */
		$filtered_values = apply_filters( 'wp_autoload_values_to_autoload', $autoload_values );
	
		return array_intersect( $filtered_values, $autoload_values );
	}
endif;

// wp-includes/option.php (WP 7.0)
if( ! function_exists( 'wp_determine_option_autoload_value' ) ) :
	function wp_determine_option_autoload_value( $option, $value, $serialized_value, $autoload ) {
	
		// Check if autoload is a boolean.
		if ( is_bool( $autoload ) ) {
			return $autoload ? 'on' : 'off';
		}
	
		switch ( $autoload ) {
			case 'on':
			case 'yes':
				return 'on';
			case 'off':
			case 'no':
				return 'off';
		}
	
		/**
		 * Allows to determine the default autoload value for an option where no explicit value is passed.
		 *
		 * @since 6.6.0
		 *
		 * @param bool|null $autoload         The default autoload value to set. Returning true will be set as 'auto-on' in the
		 *                                    database, false will be set as 'auto-off', and null will be set as 'auto'.
		 * @param string    $option           The passed option name.
		 * @param mixed     $value            The passed option value to be saved.
		 * @param mixed     $serialized_value The passed option value to be saved, in serialized form.
		 */
		$autoload = apply_filters( 'wp_default_autoload_value', null, $option, $value, $serialized_value );
		if ( is_bool( $autoload ) ) {
			return $autoload ? 'auto-on' : 'auto-off';
		}
	
		return 'auto';
	}
endif;

// wp-includes/option.php (WP 7.0)
if( ! function_exists( 'wp_filter_default_autoload_value_via_option_size' ) ) :
	function wp_filter_default_autoload_value_via_option_size( $autoload, $option, $value, $serialized_value ) {
		/**
		 * Filters the maximum size of option value in bytes.
		 *
		 * @since 6.6.0
		 *
		 * @param int    $max_option_size The option-size threshold, in bytes. Default 150000.
		 * @param string $option          The name of the option.
		 */
		$max_option_size = (int) apply_filters( 'wp_max_autoloaded_option_size', 150000, $option );
		$size            = ! empty( $serialized_value ) ? strlen( $serialized_value ) : 0;
	
		if ( $size > $max_option_size ) {
			return false;
		}
	
		return $autoload;
	}
endif;

// wp-includes/option.php (WP 7.0)
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

// wp-includes/option.php (WP 7.0)
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
			'label'             => '',
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

// wp-includes/option.php (WP 7.0)
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

