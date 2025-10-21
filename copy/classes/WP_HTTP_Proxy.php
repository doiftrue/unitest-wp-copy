<?php

// ------------------auto-generated---------------------

// wp-includes/class-wp-http-proxy.php (WP 6.8.3)
if( ! class_exists( 'WP_HTTP_Proxy' ) ) :
	class WP_HTTP_Proxy {
	
		/**
		 * Whether proxy connection should be used.
		 *
		 * Constants which control this behavior:
		 *
		 * - `WP_PROXY_HOST`
		 * - `WP_PROXY_PORT`
		 *
		 * @since 2.8.0
		 *
		 * @return bool
		 */
		public function is_enabled() {
			return defined( 'WP_PROXY_HOST' ) && defined( 'WP_PROXY_PORT' );
		}
	
		/**
		 * Whether authentication should be used.
		 *
		 * Constants which control this behavior:
		 *
		 * - `WP_PROXY_USERNAME`
		 * - `WP_PROXY_PASSWORD`
		 *
		 * @since 2.8.0
		 *
		 * @return bool
		 */
		public function use_authentication() {
			return defined( 'WP_PROXY_USERNAME' ) && defined( 'WP_PROXY_PASSWORD' );
		}
	
		/**
		 * Retrieve the host for the proxy server.
		 *
		 * @since 2.8.0
		 *
		 * @return string
		 */
		public function host() {
			if ( defined( 'WP_PROXY_HOST' ) ) {
				return WP_PROXY_HOST;
			}
	
			return '';
		}
	
		/**
		 * Retrieve the port for the proxy server.
		 *
		 * @since 2.8.0
		 *
		 * @return string
		 */
		public function port() {
			if ( defined( 'WP_PROXY_PORT' ) ) {
				return WP_PROXY_PORT;
			}
	
			return '';
		}
	
		/**
		 * Retrieve the username for proxy authentication.
		 *
		 * @since 2.8.0
		 *
		 * @return string
		 */
		public function username() {
			if ( defined( 'WP_PROXY_USERNAME' ) ) {
				return WP_PROXY_USERNAME;
			}
	
			return '';
		}
	
		/**
		 * Retrieve the password for proxy authentication.
		 *
		 * @since 2.8.0
		 *
		 * @return string
		 */
		public function password() {
			if ( defined( 'WP_PROXY_PASSWORD' ) ) {
				return WP_PROXY_PASSWORD;
			}
	
			return '';
		}
	
		/**
		 * Retrieve authentication string for proxy authentication.
		 *
		 * @since 2.8.0
		 *
		 * @return string
		 */
		public function authentication() {
			return $this->username() . ':' . $this->password();
		}
	
		/**
		 * Retrieve header string for proxy authentication.
		 *
		 * @since 2.8.0
		 *
		 * @return string
		 */
		public function authentication_header() {
			return 'Proxy-Authorization: Basic ' . base64_encode( $this->authentication() );
		}
	
		/**
		 * Determines whether the request should be sent through a proxy.
		 *
		 * We want to keep localhost and the site URL from being sent through the proxy, because
		 * some proxies can not handle this. We also have the constant available for defining other
		 * hosts that won't be sent through the proxy.
		 *
		 * @since 2.8.0
		 *
		 * @param string $uri URL of the request.
		 * @return bool Whether to send the request through the proxy.
		 */
		public function send_through_proxy( $uri ) {
			$check = parse_url( $uri );
	
			// Malformed URL, can not process, but this could mean ssl, so let through anyway.
			if ( false === $check ) {
				return true;
			}
	
			$home = parse_url( $GLOBALS['stub_wp_options']->siteurl );
	
			/**
			 * Filters whether to preempt sending the request through the proxy.
			 *
			 * Returning false will bypass the proxy; returning true will send
			 * the request through the proxy. Returning null bypasses the filter.
			 *
			 * @since 3.5.0
			 *
			 * @param bool|null $override Whether to send the request through the proxy. Default null.
			 * @param string    $uri      URL of the request.
			 * @param array     $check    Associative array result of parsing the request URL with `parse_url()`.
			 * @param array     $home     Associative array result of parsing the site URL with `parse_url()`.
			 */
			$result = apply_filters( 'pre_http_send_through_proxy', null, $uri, $check, $home );
			if ( ! is_null( $result ) ) {
				return $result;
			}
	
			if ( 'localhost' === $check['host'] || ( isset( $home['host'] ) && $home['host'] === $check['host'] ) ) {
				return false;
			}
	
			if ( ! defined( 'WP_PROXY_BYPASS_HOSTS' ) ) {
				return true;
			}
	
			static $bypass_hosts   = null;
			static $wildcard_regex = array();
			if ( null === $bypass_hosts ) {
				$bypass_hosts = preg_split( '|,\s*|', WP_PROXY_BYPASS_HOSTS );
	
				if ( str_contains( WP_PROXY_BYPASS_HOSTS, '*' ) ) {
					$wildcard_regex = array();
					foreach ( $bypass_hosts as $host ) {
						$wildcard_regex[] = str_replace( '\*', '.+', preg_quote( $host, '/' ) );
					}
					$wildcard_regex = '/^(' . implode( '|', $wildcard_regex ) . ')$/i';
				}
			}
	
			if ( ! empty( $wildcard_regex ) ) {
				return ! preg_match( $wildcard_regex, $check['host'] );
			} else {
				return ! in_array( $check['host'], $bypass_hosts, true );
			}
		}
	}
endif;

