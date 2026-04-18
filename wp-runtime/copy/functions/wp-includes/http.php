<?php

// ------------------auto-generated---------------------

// wp-includes/http.php (WP 6.9.4)
if( ! function_exists( 'wp_parse_url' ) ) :
	function wp_parse_url( $url, $component = -1 ) {
		$to_unset = array();
		$url      = (string) $url;
	
		if ( str_starts_with( $url, '//' ) ) {
			$to_unset[] = 'scheme';
			$url        = 'placeholder:' . $url;
		} elseif ( str_starts_with( $url, '/' ) ) {
			$to_unset[] = 'scheme';
			$to_unset[] = 'host';
			$url        = 'placeholder://placeholder' . $url;
		}
	
		$parts = parse_url( $url );
	
		if ( false === $parts ) {
			// Parsing failure.
			return $parts;
		}
	
		// Remove the placeholder values.
		foreach ( $to_unset as $key ) {
			unset( $parts[ $key ] );
		}
	
		return _get_component_from_parsed_url_array( $parts, $component );
	}
endif;

// wp-includes/http.php (WP 6.9.4)
if( ! function_exists( '_wp_translate_php_url_constant_to_key' ) ) :
	function _wp_translate_php_url_constant_to_key( $constant ) {
		$translation = array(
			PHP_URL_SCHEME   => 'scheme',
			PHP_URL_HOST     => 'host',
			PHP_URL_PORT     => 'port',
			PHP_URL_USER     => 'user',
			PHP_URL_PASS     => 'pass',
			PHP_URL_PATH     => 'path',
			PHP_URL_QUERY    => 'query',
			PHP_URL_FRAGMENT => 'fragment',
		);
	
		if ( isset( $translation[ $constant ] ) ) {
			return $translation[ $constant ];
		} else {
			return false;
		}
	}
endif;

// wp-includes/http.php (WP 6.9.4)
if( ! function_exists( '_get_component_from_parsed_url_array' ) ) :
	function _get_component_from_parsed_url_array( $url_parts, $component = -1 ) {
		if ( -1 === $component ) {
			return $url_parts;
		}
	
		$key = _wp_translate_php_url_constant_to_key( $component );
		if ( false !== $key && is_array( $url_parts ) && isset( $url_parts[ $key ] ) ) {
			return $url_parts[ $key ];
		} else {
			return null;
		}
	}
endif;

// wp-includes/http.php (WP 6.9.4)
if( ! function_exists( 'wp_http_validate_url' ) ) :
	function wp_http_validate_url( $url ) {
		if ( ! is_string( $url ) || '' === $url || is_numeric( $url ) ) {
			return false;
		}
	
		$original_url = $url;
		$url          = wp_kses_bad_protocol( $url, array( 'http', 'https' ) );
		if ( ! $url || strtolower( $url ) !== strtolower( $original_url ) ) {
			return false;
		}
	
		$parsed_url = parse_url( $url );
		if ( ! $parsed_url || empty( $parsed_url['host'] ) ) {
			return false;
		}
	
		if ( isset( $parsed_url['user'] ) || isset( $parsed_url['pass'] ) ) {
			return false;
		}
	
		if ( false !== strpbrk( $parsed_url['host'], ':#?[]' ) ) {
			return false;
		}
	
		$parsed_home = parse_url( $GLOBALS['stub_wp_options']->home );
		$same_host   = isset( $parsed_home['host'] ) && strtolower( $parsed_home['host'] ) === strtolower( $parsed_url['host'] );
		$host        = trim( $parsed_url['host'], '.' );
	
		if ( ! $same_host ) {
			if ( preg_match( '#^(([1-9]?\d|1\d\d|25[0-5]|2[0-4]\d)\.){3}([1-9]?\d|1\d\d|25[0-5]|2[0-4]\d)$#', $host ) ) {
				$ip = $host;
			} else {
				$ip = gethostbyname( $host );
				if ( $ip === $host ) { // Error condition for gethostbyname().
					return false;
				}
			}
			if ( $ip ) {
				$parts = array_map( 'intval', explode( '.', $ip ) );
				if ( 127 === $parts[0] || 10 === $parts[0] || 0 === $parts[0]
					|| ( 172 === $parts[0] && 16 <= $parts[1] && 31 >= $parts[1] )
					|| ( 192 === $parts[0] && 168 === $parts[1] )
				) {
					// If host appears local, reject unless specifically allowed.
					/**
					 * Checks if HTTP request is external or not.
					 *
					 * Allows to change and allow external requests for the HTTP request.
					 *
					 * @since 3.6.0
					 *
					 * @param bool   $external Whether HTTP request is external or not.
					 * @param string $host     Host name of the requested URL.
					 * @param string $url      Requested URL.
					 */
					if ( ! apply_filters( 'http_request_host_is_external', false, $host, $url ) ) {
						return false;
					}
				}
			}
		}
	
		if ( empty( $parsed_url['port'] ) ) {
			return $url;
		}
	
		$port = $parsed_url['port'];
	
		/**
		 * Controls the list of ports considered safe in HTTP API.
		 *
		 * Allows to change and allow external requests for the HTTP request.
		 *
		 * @since 5.9.0
		 *
		 * @param int[]  $allowed_ports Array of integers for valid ports. Default allowed ports
		 *                              are 80, 443, and 8080.
		 * @param string $host          Host name of the requested URL.
		 * @param string $url           Requested URL.
		 */
		$allowed_ports = apply_filters( 'http_allowed_safe_ports', array( 80, 443, 8080 ), $host, $url );
		if ( is_array( $allowed_ports ) && in_array( $port, $allowed_ports, true ) ) {
			return $url;
		}
	
		if ( $parsed_home && $same_host && isset( $parsed_home['port'] ) && $parsed_home['port'] === $port ) {
			return $url;
		}
	
		return false;
	}
endif;

