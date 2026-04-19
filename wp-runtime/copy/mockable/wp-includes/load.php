<?php

// ------------------auto-generated---------------------

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'wp_get_development_mode' ) ) :
	function wp_get_development_mode() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
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

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'wp_get_environment_type' ) ) :
	function wp_get_environment_type() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
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

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'wp_doing_ajax' ) ) :
	function wp_doing_ajax() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
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

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'is_ssl' ) ) :
	function is_ssl() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
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

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'wp_installing' ) ) :
	function wp_installing( $is_installing = null ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		static $installing = null;
	
		// Support for the `WP_INSTALLING` constant, defined before WP is loaded.
		if ( is_null( $installing ) ) {
			$installing = defined( 'WP_INSTALLING' ) && WP_INSTALLING;
		}
	
		if ( ! is_null( $is_installing ) ) {
			$old_installing = $installing;
			$installing     = $is_installing;
	
			return (bool) $old_installing;
		}
	
		return (bool) $installing;
	}
endif;

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'is_admin' ) ) :
	function is_admin() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( isset( $GLOBALS['current_screen'] ) ) {
			return $GLOBALS['current_screen']->in_admin();
		} elseif ( defined( 'WP_ADMIN' ) ) {
			return WP_ADMIN;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.6.5)
if( ! function_exists( 'is_multisite' ) ) :
	function is_multisite() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( defined( 'MULTISITE' ) ) {
			return MULTISITE;
		}
	
		if ( defined( 'SUBDOMAIN_INSTALL' ) || defined( 'VHOST' ) || defined( 'SUNRISE' ) ) {
			return true;
		}
	
		return false;
	}
endif;

