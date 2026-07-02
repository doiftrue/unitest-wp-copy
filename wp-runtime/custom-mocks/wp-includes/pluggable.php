<?php
/**
 * Runtime-adapted implementations of WordPress functions from wp-includes/pluggable.php.
 * Supports WP_Mock unit testing implementation.
 */

use Unitest_WP_Copy\WP_Mock_Utils;

/**
 * Runtime adaptation of wp_salt() from WordPress 7.0 wp-includes/pluggable.php.
 */
if ( ! function_exists( 'wp_salt' ) ) :
	function wp_salt( $scheme = 'auth' ) {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		static $cached_salts = array();
		if ( isset( $cached_salts[ $scheme ] ) ) {
			/**
			 * Filters the WordPress salt.
			 *
			 * @since 2.5.0
			 *
			 * @param string $cached_salt Cached salt for the given scheme.
			 * @param string $scheme      Authentication scheme. Values include 'auth',
			 *                            'secure_auth', 'logged_in', and 'nonce'.
			 */
			return apply_filters( 'salt', $cached_salts[ $scheme ], $scheme );
		}

		static $duplicated_keys;
		if ( null === $duplicated_keys ) {
			$duplicated_keys = array();

			foreach ( array( 'AUTH', 'SECURE_AUTH', 'LOGGED_IN', 'NONCE', 'SECRET' ) as $first ) {
				foreach ( array( 'KEY', 'SALT' ) as $second ) {
					if ( ! defined( "{$first}_{$second}" ) ) {
						continue;
					}
					$value                     = constant( "{$first}_{$second}" );
					$duplicated_keys[ $value ] = isset( $duplicated_keys[ $value ] );
				}
			}

			$duplicated_keys['put your unique phrase here'] = true;

			/*
			 * translators: This string should only be translated if wp-config-sample.php is localized.
			 * You can check the localized release package or
			 * https://i18n.svn.wordpress.org/<locale code>/branches/<wp version>/dist/wp-config-sample.php
			 */
			$duplicated_keys[ __( 'put your unique phrase here' ) ] = true;
		}

		/*
		 * Determine which options to prime.
		 *
		 * If the salt keys are undefined, use a duplicate value or the
		 * default `put your unique phrase here` value the salt will be
		 * generated via `wp_generate_password()` and stored as a site
		 * option. These options will be primed to avoid repeated
		 * database requests for undefined salts.
		 */
		$options_to_prime = array();
		foreach ( array( 'auth', 'secure_auth', 'logged_in', 'nonce' ) as $key ) {
			foreach ( array( 'key', 'salt' ) as $second ) {
				$const = strtoupper( "{$key}_{$second}" );
				if ( ! defined( $const ) || true === $duplicated_keys[ constant( $const ) ] ) {
					$options_to_prime[] = "{$key}_{$second}";
				}
			}
		}

		if ( ! empty( $options_to_prime ) ) {
			throw new RuntimeException(
				'Unique, non-empty WordPress salt constants are required: AUTH_KEY, AUTH_SALT, '
				. 'SECURE_AUTH_KEY, SECURE_AUTH_SALT, LOGGED_IN_KEY, LOGGED_IN_SALT, NONCE_KEY, '
				. 'and NONCE_SALT. Site-option fallback is unavailable.'
			);
		}

		$values = array(
			'key'  => '',
			'salt' => '',
		);
		if ( defined( 'SECRET_KEY' ) && SECRET_KEY && empty( $duplicated_keys[ SECRET_KEY ] ) ) {
			$values['key'] = SECRET_KEY;
		}
		if ( 'auth' === $scheme && defined( 'SECRET_SALT' ) && SECRET_SALT && empty( $duplicated_keys[ SECRET_SALT ] ) ) {
			$values['salt'] = SECRET_SALT;
		}

		if ( in_array( $scheme, array( 'auth', 'secure_auth', 'logged_in', 'nonce' ), true ) ) {
			foreach ( array( 'key', 'salt' ) as $type ) {
				$const = strtoupper( "{$scheme}_{$type}" );
				if ( defined( $const ) && constant( $const ) && empty( $duplicated_keys[ constant( $const ) ] ) ) {
					$values[ $type ] = constant( $const );
				} elseif ( ! $values[ $type ] ) {
					throw new RuntimeException(
						"A valid $const constant is required. Define it with a unique, non-empty value, "
						. "for example: define( '$const', 'unique random value' ). Site-option fallback is unavailable."
					);
				}
			}
		} else {
			if ( ! $values['key'] ) {
				throw new RuntimeException( 'A valid SECRET_KEY constant is required; site-option fallback is unavailable.' );
			}
			$values['salt'] = hash_hmac( 'md5', $scheme, $values['key'] );
		}

		$cached_salts[ $scheme ] = $values['key'] . $values['salt'];

		/** This filter is documented in wp-includes/pluggable.php */
		return apply_filters( 'salt', $cached_salts[ $scheme ], $scheme );
	}
endif;
