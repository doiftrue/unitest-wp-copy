<?php


// ------------------auto-generated---------------------

// wp-includes/load.php (WP 6.8.3)
if( ! function_exists( 'wp_get_environment_type' ) ) :
	function wp_get_environment_type() {
		static $current_env = '';
	
		if ( ! defined( 'WP_RUN_CORE_TESTS' ) && $current_env ) {
			return $current_env;
		}
	
		$wp_environments = array(
			'local',
			'development',
			'staging',
			'production',
		);
	
		// Add a note about the deprecated WP_ENVIRONMENT_TYPES constant.
		if ( defined( 'WP_ENVIRONMENT_TYPES' ) && function_exists( '_deprecated_argument' ) ) {
			if ( function_exists( '__' ) ) {
				/* translators: %s: WP_ENVIRONMENT_TYPES */
				$message = sprintf( __( 'The %s constant is no longer supported.' ), 'WP_ENVIRONMENT_TYPES' );
			} else {
				$message = sprintf( 'The %s constant is no longer supported.', 'WP_ENVIRONMENT_TYPES' );
			}
	
			_deprecated_argument(
				'define()',
				'5.5.1',
				$message
			);
		}
	
		// Check if the environment variable has been set, if `getenv` is available on the system.
		if ( function_exists( 'getenv' ) ) {
			$has_env = getenv( 'WP_ENVIRONMENT_TYPE' );
			if ( false !== $has_env ) {
				$current_env = $has_env;
			}
		}
	
		// Fetch the environment from a constant, this overrides the global system variable.
		if ( defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE ) {
			$current_env = WP_ENVIRONMENT_TYPE;
		}
	
		// Make sure the environment is an allowed one, and not accidentally set to an invalid value.
		if ( ! in_array( $current_env, $wp_environments, true ) ) {
			$current_env = 'production';
		}
	
		return $current_env;
	}
endif;

// wp-includes/load.php (WP 6.8.3)
if( ! function_exists( 'wp_get_development_mode' ) ) :
	function wp_get_development_mode() {
		static $current_mode = null;
	
		if ( ! defined( 'WP_RUN_CORE_TESTS' ) && null !== $current_mode ) {
			return $current_mode;
		}
	
		$development_mode = WP_DEVELOPMENT_MODE;
	
		// Exclusively for core tests, rely on the `$_wp_tests_development_mode` global.
		if ( defined( 'WP_RUN_CORE_TESTS' ) && isset( $GLOBALS['_wp_tests_development_mode'] ) ) {
			$development_mode = $GLOBALS['_wp_tests_development_mode'];
		}
	
		$valid_modes = array(
			'core',
			'plugin',
			'theme',
			'all',
			'',
		);
	
		if ( ! in_array( $development_mode, $valid_modes, true ) ) {
			$development_mode = '';
		}
	
		$current_mode = $development_mode;
	
		return $current_mode;
	}
endif;

// wp-includes/load.php (WP 6.8.3)
if( ! function_exists( 'is_admin' ) ) :
	function is_admin() {
		if ( isset( $GLOBALS['current_screen'] ) ) {
			return $GLOBALS['current_screen']->in_admin();
		} elseif ( defined( 'WP_ADMIN' ) ) {
			return WP_ADMIN;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.8.3)
if( ! function_exists( 'is_multisite' ) ) :
	function is_multisite() {
		if ( defined( 'MULTISITE' ) ) {
			return MULTISITE;
		}
	
		if ( defined( 'SUBDOMAIN_INSTALL' ) || defined( 'VHOST' ) || defined( 'SUNRISE' ) ) {
			return true;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.8.3)
if( ! function_exists( 'absint' ) ) :
	function absint( $maybeint ) {
		return abs( (int) $maybeint );
	}
endif;

// wp-includes/load.php (WP 6.8.3)
if( ! function_exists( 'is_ssl' ) ) :
	function is_ssl() {
		if ( isset( $_SERVER['HTTPS'] ) ) {
			if ( 'on' === strtolower( $_SERVER['HTTPS'] ) ) {
				return true;
			}
	
			if ( '1' === (string) $_SERVER['HTTPS'] ) {
				return true;
			}
		} elseif ( isset( $_SERVER['SERVER_PORT'] ) && ( '443' === (string) $_SERVER['SERVER_PORT'] ) ) {
			return true;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.8.3)
if( ! function_exists( 'wp_convert_hr_to_bytes' ) ) :
	function wp_convert_hr_to_bytes( $value ) {
		$value = strtolower( trim( $value ) );
		$bytes = (int) $value;
	
		if ( str_contains( $value, 'g' ) ) {
			$bytes *= GB_IN_BYTES;
		} elseif ( str_contains( $value, 'm' ) ) {
			$bytes *= MB_IN_BYTES;
		} elseif ( str_contains( $value, 'k' ) ) {
			$bytes *= KB_IN_BYTES;
		}
	
		// Deal with large (float) values which run into the maximum integer size.
		return min( $bytes, PHP_INT_MAX );
	}
endif;

// wp-includes/load.php (WP 6.8.3)
if( ! function_exists( 'wp_is_ini_value_changeable' ) ) :
	function wp_is_ini_value_changeable( $setting ) {
		static $ini_all;
	
		if ( ! isset( $ini_all ) ) {
			$ini_all = false;
			// Sometimes `ini_get_all()` is disabled via the `disable_functions` option for "security purposes".
			if ( function_exists( 'ini_get_all' ) ) {
				$ini_all = ini_get_all();
			}
		}
	
		if ( isset( $ini_all[ $setting ]['access'] )
			&& ( INI_ALL === $ini_all[ $setting ]['access'] || INI_USER === $ini_all[ $setting ]['access'] )
		) {
			return true;
		}
	
		// If we were unable to retrieve the details, fail gracefully to assume it's changeable.
		if ( ! is_array( $ini_all ) ) {
			return true;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.8.3)
if( ! function_exists( 'wp_doing_ajax' ) ) :
	function wp_doing_ajax() {
		/**
		 * Filters whether the current request is a WordPress Ajax request.
		 *
		 * @since 4.7.0
		 *
		 * @param bool $wp_doing_ajax Whether the current request is a WordPress Ajax request.
		 */
		return apply_filters( 'wp_doing_ajax', defined( 'DOING_AJAX' ) && DOING_AJAX );
	}
endif;

// wp-includes/load.php (WP 6.8.3)
if( ! function_exists( 'is_wp_error' ) ) :
	function is_wp_error( $thing ) {
		$is_wp_error = ( $thing instanceof WP_Error );
	
		if ( $is_wp_error ) {
			/**
			 * Fires when `is_wp_error()` is called and its parameter is an instance of `WP_Error`.
			 *
			 * @since 5.6.0
			 *
			 * @param WP_Error $thing The error object passed to `is_wp_error()`.
			 */
			do_action( 'is_wp_error_instance', $thing );
		}
	
		return $is_wp_error;
	}
endif;

