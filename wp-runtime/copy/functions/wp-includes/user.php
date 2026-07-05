<?php

// ------------------auto-generated---------------------

// wp-includes/user.php (WP 6.8.5)
if( ! function_exists( 'validate_username' ) ) :
	function validate_username( $username ) {
		$sanitized = sanitize_user( $username, true );
		$valid     = ( $sanitized === $username && ! empty( $sanitized ) );
	
		/**
		 * Filters whether the provided username is valid.
		 *
		 * @since 2.0.1
		 *
		 * @param bool   $valid    Whether given username is valid.
		 * @param string $username Username to check.
		 */
		return apply_filters( 'validate_username', $valid, $username );
	}
endif;

// wp-includes/user.php (WP 6.8.5)
if( ! function_exists( 'wp_get_password_hint' ) ) :
	function wp_get_password_hint() {
		$hint = __( 'Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ &amp; ).' );
	
		/**
		 * Filters the text describing the site's password complexity policy.
		 *
		 * @since 4.1.0
		 *
		 * @param string $hint The password hint text.
		 */
		return apply_filters( 'password_hint', $hint );
	}
endif;

// wp-includes/user.php (WP 6.8.5)
if( ! function_exists( '_wp_privacy_action_request_types' ) ) :
	function _wp_privacy_action_request_types() {
		return array(
			'export_personal_data',
			'remove_personal_data',
		);
	}
endif;

// wp-includes/user.php (WP 6.8.5)
if( ! function_exists( 'wp_register_user_personal_data_exporter' ) ) :
	function wp_register_user_personal_data_exporter( $exporters ) {
		$exporters['wordpress-user'] = array(
			'exporter_friendly_name' => __( 'WordPress User' ),
			'callback'               => 'wp_user_personal_data_exporter',
		);
	
		return $exporters;
	}
endif;

// wp-includes/user.php (WP 6.8.5)
if( ! function_exists( 'wp_user_request_action_description' ) ) :
	function wp_user_request_action_description( $action_name ) {
		switch ( $action_name ) {
			case 'export_personal_data':
				$description = __( 'Export Personal Data' );
				break;
			case 'remove_personal_data':
				$description = __( 'Erase Personal Data' );
				break;
			default:
				/* translators: %s: Action name. */
				$description = sprintf( __( 'Confirm the "%s" action' ), $action_name );
				break;
		}
	
		/**
		 * Filters the user action description.
		 *
		 * @since 4.9.6
		 *
		 * @param string $description The default description.
		 * @param string $action_name The name of the request.
		 */
		return apply_filters( 'user_request_action_description', $description, $action_name );
	}
endif;

// wp-includes/user.php (WP 6.8.5)
if( ! function_exists( 'wp_is_application_passwords_available' ) ) :
	function wp_is_application_passwords_available() {
		/**
		 * Filters whether Application Passwords is available.
		 *
		 * @since 5.6.0
		 *
		 * @param bool $available True if available, false otherwise.
		 */
		return apply_filters( 'wp_is_application_passwords_available', wp_is_application_passwords_supported() );
	}
endif;

// wp-includes/user.php (WP 6.8.5)
if( ! function_exists( 'wp_cache_set_users_last_changed' ) ) :
	function wp_cache_set_users_last_changed() {
		wp_cache_set_last_changed( 'users' );
	}
endif;

// wp-includes/user.php (WP 6.8.5)
if( ! function_exists( 'sanitize_user_field' ) ) :
	function sanitize_user_field( $field, $value, $user_id, $context ) {
		$int_fields = array( 'ID' );
		if ( in_array( $field, $int_fields, true ) ) {
			$value = (int) $value;
		}
	
		if ( 'raw' === $context ) {
			return $value;
		}
	
		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			return $value;
		}
	
		$prefixed = str_contains( $field, 'user_' );
	
		if ( 'edit' === $context ) {
			if ( $prefixed ) {
	
				/** This filter is documented in wp-includes/post.php */
				$value = apply_filters( "edit_{$field}", $value, $user_id );
			} else {
	
				/**
				 * Filters a user field value in the 'edit' context.
				 *
				 * The dynamic portion of the hook name, `$field`, refers to the prefixed user
				 * field being filtered, such as 'user_login', 'user_email', 'first_name', etc.
				 *
				 * @since 2.9.0
				 *
				 * @param mixed $value   Value of the prefixed user field.
				 * @param int   $user_id User ID.
				 */
				$value = apply_filters( "edit_user_{$field}", $value, $user_id );
			}
	
			if ( 'description' === $field ) {
				$value = esc_html( $value ); // textarea_escaped?
			} else {
				$value = esc_attr( $value );
			}
		} elseif ( 'db' === $context ) {
			if ( $prefixed ) {
				/** This filter is documented in wp-includes/post.php */
				$value = apply_filters( "pre_{$field}", $value );
			} else {
	
				/**
				 * Filters the value of a user field in the 'db' context.
				 *
				 * The dynamic portion of the hook name, `$field`, refers to the prefixed user
				 * field being filtered, such as 'user_login', 'user_email', 'first_name', etc.
				 *
				 * @since 2.9.0
				 *
				 * @param mixed $value Value of the prefixed user field.
				 */
				$value = apply_filters( "pre_user_{$field}", $value );
			}
		} else {
			// Use display filters by default.
			if ( $prefixed ) {
	
				/** This filter is documented in wp-includes/post.php */
				$value = apply_filters( "{$field}", $value, $user_id, $context );
			} else {
	
				/**
				 * Filters the value of a user field in a standard context.
				 *
				 * The dynamic portion of the hook name, `$field`, refers to the prefixed user
				 * field being filtered, such as 'user_login', 'user_email', 'first_name', etc.
				 *
				 * @since 2.9.0
				 *
				 * @param mixed  $value   The user object value to sanitize.
				 * @param int    $user_id User ID.
				 * @param string $context The context to filter within.
				 */
				$value = apply_filters( "user_{$field}", $value, $user_id, $context );
			}
		}
	
		if ( 'user_url' === $field ) {
			$value = esc_url( $value );
		}
	
		if ( 'attribute' === $context ) {
			$value = esc_attr( $value );
		} elseif ( 'js' === $context ) {
			$value = esc_js( $value );
		}
	
		// Restore the type for integer fields after esc_attr().
		if ( in_array( $field, $int_fields, true ) ) {
			$value = (int) $value;
		}
	
		return $value;
	}
endif;

