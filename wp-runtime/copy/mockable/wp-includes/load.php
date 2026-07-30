<?php

// ------------------auto-generated---------------------

// wp-includes/load.php (WP 6.8.6)
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

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'is_login' ) ) :
	function is_login() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		return false !== stripos( wp_login_url(), $_SERVER['SCRIPT_NAME'] );
	}
endif;

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'timer_float' ) ) :
	function timer_float() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		return microtime( true ) - $_SERVER['REQUEST_TIME_FLOAT'];
	}
endif;

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'wp_is_jsonp_request' ) ) :
	function wp_is_jsonp_request() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( ! isset( $_GET['_jsonp'] ) ) {
			return false;
		}
	
		if ( ! function_exists( 'wp_check_jsonp_callback' ) ) {
			require_once ABSPATH . WPINC . '/functions.php';
		}
	
		$jsonp_callback = $_GET['_jsonp'];
		if ( ! wp_check_jsonp_callback( $jsonp_callback ) ) {
			return false;
		}
	
		/** This filter is documented in wp-includes/rest-api/class-wp-rest-server.php */
		$jsonp_enabled = apply_filters( 'rest_jsonp_enabled', true );
	
		return $jsonp_enabled;
	}
endif;

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'wp_is_xml_request' ) ) :
	function wp_is_xml_request() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$accepted = array(
			'text/xml',
			'application/rss+xml',
			'application/atom+xml',
			'application/rdf+xml',
			'text/xml+oembed',
			'application/xml+oembed',
		);
	
		if ( isset( $_SERVER['HTTP_ACCEPT'] ) ) {
			foreach ( $accepted as $type ) {
				if ( str_contains( $_SERVER['HTTP_ACCEPT'], $type ) ) {
					return true;
				}
			}
		}
	
		if ( isset( $_SERVER['CONTENT_TYPE'] ) && in_array( $_SERVER['CONTENT_TYPE'], $accepted, true ) ) {
			return true;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'wp_is_json_request' ) ) :
	function wp_is_json_request() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( isset( $_SERVER['HTTP_ACCEPT'] ) && wp_is_json_media_type( $_SERVER['HTTP_ACCEPT'] ) ) {
			return true;
		}
	
		if ( isset( $_SERVER['CONTENT_TYPE'] ) && wp_is_json_media_type( $_SERVER['CONTENT_TYPE'] ) ) {
			return true;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'wp_using_themes' ) ) :
	function wp_using_themes() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		/**
		 * Filters whether the current request should use themes.
		 *
		 * @since 5.1.0
		 *
		 * @param bool $wp_using_themes Whether the current request should use themes.
		 */
		return apply_filters( 'wp_using_themes', defined( 'WP_USE_THEMES' ) && WP_USE_THEMES );
	}
endif;

// wp-includes/load.php (WP 6.8.6)
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

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'wp_doing_cron' ) ) :
	function wp_doing_cron() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		/**
		 * Filters whether the current request is a WordPress cron request.
		 *
		 * @since 4.8.0
		 *
		 * @param bool $wp_doing_cron Whether the current request is a WordPress cron request.
		 */
		return apply_filters( 'wp_doing_cron', defined( 'DOING_CRON' ) && DOING_CRON );
	}
endif;

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'wp_is_file_mod_allowed' ) ) :
	function wp_is_file_mod_allowed( $context ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		/**
		 * Filters whether file modifications are allowed.
		 *
		 * @since 4.8.0
		 *
		 * @param bool   $file_mod_allowed Whether file modifications are allowed.
		 * @param string $context          The usage context.
		 */
		return apply_filters( 'file_mod_allowed', ! defined( 'DISALLOW_FILE_MODS' ) || ! DISALLOW_FILE_MODS, $context );
	}
endif;

// wp-includes/load.php (WP 6.8.6)
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

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'get_current_network_id' ) ) :
	function get_current_network_id() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( ! is_multisite() ) {
			return 1;
		}
	
		$current_network = get_network();
	
		if ( ! isset( $current_network->id ) ) {
			return get_main_network_id();
		}
	
		return absint( $current_network->id );
	}
endif;

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'wp_get_server_protocol' ) ) :
	function wp_get_server_protocol() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$protocol = isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : '';
	
		if ( ! in_array( $protocol, array( 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0', 'HTTP/3' ), true ) ) {
			$protocol = 'HTTP/1.0';
		}
	
		return $protocol;
	}
endif;

// wp-includes/load.php (WP 6.8.6)
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

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'is_blog_admin' ) ) :
	function is_blog_admin() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( isset( $GLOBALS['current_screen'] ) ) {
			return $GLOBALS['current_screen']->in_admin( 'site' );
		} elseif ( defined( 'WP_BLOG_ADMIN' ) ) {
			return WP_BLOG_ADMIN;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'is_network_admin' ) ) :
	function is_network_admin() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( isset( $GLOBALS['current_screen'] ) ) {
			return $GLOBALS['current_screen']->in_admin( 'network' );
		} elseif ( defined( 'WP_NETWORK_ADMIN' ) ) {
			return WP_NETWORK_ADMIN;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'is_user_admin' ) ) :
	function is_user_admin() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( isset( $GLOBALS['current_screen'] ) ) {
			return $GLOBALS['current_screen']->in_admin( 'user' );
		} elseif ( defined( 'WP_USER_ADMIN' ) ) {
			return WP_USER_ADMIN;
		}
	
		return false;
	}
endif;

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'get_current_blog_id' ) ) :
	function get_current_blog_id() {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $blog_id;
	
		return absint( $blog_id );
	}
endif;

// wp-includes/load.php (WP 6.8.6)
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

// wp-includes/load.php (WP 6.8.6)
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

// wp-includes/load.php (WP 6.8.6)
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

// wp-includes/load.php (WP 6.8.6)
if( ! function_exists( 'timer_stop' ) ) :
	function timer_stop( $display = 0, $precision = 3 ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $timestart, $timeend;
	
		$timeend   = microtime( true );
		$timetotal = $timeend - $timestart;
	
		if ( function_exists( 'number_format_i18n' ) ) {
			$r = number_format_i18n( $timetotal, $precision );
		} else {
			$r = number_format( $timetotal, $precision );
		}
	
		if ( $display ) {
			echo $r;
		}
	
		return $r;
	}
endif;

