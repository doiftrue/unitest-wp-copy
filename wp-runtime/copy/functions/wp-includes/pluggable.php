<?php

// ------------------auto-generated---------------------

// wp-includes/pluggable.php (WP 6.8.6)
if( ! function_exists( 'wp_password_needs_rehash' ) ) :
		function wp_password_needs_rehash( $hash, $user_id = '' ) {
			global $wp_hasher;
	
			if ( ! empty( $wp_hasher ) ) {
				return false;
			}
	
			/** This filter is documented in wp-includes/pluggable.php */
			$algorithm = apply_filters( 'wp_hash_password_algorithm', PASSWORD_BCRYPT );
	
			/** This filter is documented in wp-includes/pluggable.php */
			$options = apply_filters( 'wp_hash_password_options', array(), $algorithm );
	
			$prefixed = str_starts_with( $hash, '$wp' );
	
			if ( ( PASSWORD_BCRYPT === $algorithm ) && ! $prefixed ) {
				// If bcrypt is in use and the hash is not prefixed then it needs to be rehashed.
				$needs_rehash = true;
			} else {
				// Otherwise check the hash minus its prefix if necessary.
				$hash_to_check = $prefixed ? substr( $hash, 3 ) : $hash;
				$needs_rehash  = password_needs_rehash( $hash_to_check, $algorithm, $options );
			}
	
			/**
			 * Filters whether the password hash needs to be rehashed.
			 *
			 * @since 6.8.0
			 *
			 * @param bool       $needs_rehash Whether the password hash needs to be rehashed.
			 * @param string     $hash         The password hash.
			 * @param string|int $user_id      Optional. ID of a user associated with the password.
			 */
			return apply_filters( 'password_needs_rehash', $needs_rehash, $hash, $user_id );
		}
endif;

// wp-includes/pluggable.php (WP 6.8.6)
if( ! function_exists( '_wp_sanitize_utf8_in_redirect' ) ) :
		function _wp_sanitize_utf8_in_redirect( $matches ) {
			return urlencode( $matches[0] );
		}
endif;

// wp-includes/pluggable.php (WP 6.8.6)
if( ! function_exists( 'wp_validate_redirect' ) ) :
		function wp_validate_redirect( $location, $fallback_url = '' ) {
			$location = wp_sanitize_redirect( trim( $location, " \t\n\r\0\x08\x0B" ) );
			// Browsers will assume 'http' is your protocol, and will obey a redirect to a URL starting with '//'.
			if ( str_starts_with( $location, '//' ) ) {
				$location = 'http:' . $location;
			}
	
			/*
			 * In PHP 5 parse_url() may fail if the URL query part contains 'http://'.
			 * See https://bugs.php.net/bug.php?id=38143
			 */
			$cut  = strpos( $location, '?' );
			$test = $cut ? substr( $location, 0, $cut ) : $location;
	
			$lp = parse_url( $test );
	
			// Give up if malformed URL.
			if ( false === $lp ) {
				return $fallback_url;
			}
	
			// Allow only 'http' and 'https' schemes. No 'data:', etc.
			if ( isset( $lp['scheme'] ) && ! ( 'http' === $lp['scheme'] || 'https' === $lp['scheme'] ) ) {
				return $fallback_url;
			}
	
			if ( ! isset( $lp['host'] ) && ! empty( $lp['path'] ) && '/' !== $lp['path'][0] ) {
				$path = '';
				if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
					$path = dirname( parse_url( 'http://placeholder' . $_SERVER['REQUEST_URI'], PHP_URL_PATH ) . '?' );
					$path = wp_normalize_path( $path );
				}
				$location = '/' . ltrim( $path . '/', '/' ) . $location;
			}
	
			/*
			 * Reject if certain components are set but host is not.
			 * This catches URLs like https:host.com for which parse_url() does not set the host field.
			 */
			if ( ! isset( $lp['host'] ) && ( isset( $lp['scheme'] ) || isset( $lp['user'] ) || isset( $lp['pass'] ) || isset( $lp['port'] ) ) ) {
				return $fallback_url;
			}
	
			// Reject malformed components parse_url() can return on odd inputs.
			foreach ( array( 'user', 'pass', 'host' ) as $component ) {
				if ( isset( $lp[ $component ] ) && strpbrk( $lp[ $component ], ':/?#@' ) ) {
					return $fallback_url;
				}
			}
	
			$wpp = parse_url( home_url() );
	
			/**
			 * Filters the list of allowed hosts to redirect to.
			 *
			 * @since 2.3.0
			 *
			 * @param string[] $hosts An array of allowed host names.
			 * @param string   $host  The host name of the redirect destination; empty string if not set.
			 */
			$allowed_hosts = (array) apply_filters( 'allowed_redirect_hosts', array( $wpp['host'] ), isset( $lp['host'] ) ? $lp['host'] : '' );
	
			if ( isset( $lp['host'] ) && ( ! in_array( $lp['host'], $allowed_hosts, true ) && strtolower( $wpp['host'] ) !== $lp['host'] ) ) {
				$location = $fallback_url;
			}
	
			return $location;
		}
endif;

// wp-includes/pluggable.php (WP 6.8.6)
if( ! function_exists( 'wp_hash_password' ) ) :
		function wp_hash_password(
			#[\SensitiveParameter]
			$password
		) {
			global $wp_hasher;
	
			if ( ! empty( $wp_hasher ) ) {
				return $wp_hasher->HashPassword( trim( $password ) );
			}
	
			if ( strlen( $password ) > 4096 ) {
				return '*';
			}
	
			/**
			 * Filters the hashing algorithm to use in the password_hash() and password_needs_rehash() functions.
			 *
			 * The default is the value of the `PASSWORD_BCRYPT` constant which means bcrypt is used.
			 *
			 * **Important:** The only password hashing algorithm that is guaranteed to be available across PHP
			 * installations is bcrypt. If you use any other algorithm you must make sure that it is available on
			 * the server. The `password_algos()` function can be used to check which hashing algorithms are available.
			 *
			 * The hashing options can be controlled via the {@see 'wp_hash_password_options'} filter.
			 *
			 * Other available constants include:
			 *
			 * - `PASSWORD_ARGON2I`
			 * - `PASSWORD_ARGON2ID`
			 * - `PASSWORD_DEFAULT`
			 *
			 * @since 6.8.0
			 *
			 * @param string $algorithm The hashing algorithm. Default is the value of the `PASSWORD_BCRYPT` constant.
			 */
			$algorithm = apply_filters( 'wp_hash_password_algorithm', PASSWORD_BCRYPT );
	
			/**
			 * Filters the options passed to the password_hash() and password_needs_rehash() functions.
			 *
			 * The default hashing algorithm is bcrypt, but this can be changed via the {@see 'wp_hash_password_algorithm'}
			 * filter. You must ensure that the options are appropriate for the algorithm in use.
			 *
			 * @since 6.8.0
			 *
			 * @param array $options    Array of options to pass to the password hashing functions.
			 *                          By default this is an empty array which means the default
			 *                          options will be used.
			 * @param string $algorithm The hashing algorithm in use.
			 */
			$options = apply_filters( 'wp_hash_password_options', array(), $algorithm );
	
			// Algorithms other than bcrypt don't need to use pre-hashing.
			if ( PASSWORD_BCRYPT !== $algorithm ) {
				return password_hash( $password, $algorithm, $options );
			}
	
			// Use SHA-384 to retain entropy from a password that's longer than 72 bytes, and a `wp-sha384` key for domain separation.
			$password_to_hash = base64_encode( hash_hmac( 'sha384', trim( $password ), 'wp-sha384', true ) );
	
			// Add a prefix to facilitate distinguishing vanilla bcrypt hashes.
			return '$wp' . password_hash( $password_to_hash, $algorithm, $options );
		}
endif;

// wp-includes/pluggable.php (WP 6.8.6)
if( ! function_exists( 'wp_generate_password' ) ) :
		function wp_generate_password( $length = 12, $special_chars = true, $extra_special_chars = false ) {
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			if ( $special_chars ) {
				$chars .= '!@#$%^&*()';
			}
			if ( $extra_special_chars ) {
				$chars .= '-_ []{}<>~`+=,.;:/?|';
			}
	
			$password = '';
			for ( $i = 0; $i < $length; $i++ ) {
				$password .= substr( $chars, wp_rand( 0, strlen( $chars ) - 1 ), 1 );
			}
	
			/**
			 * Filters the randomly-generated password.
			 *
			 * @since 3.0.0
			 * @since 5.3.0 Added the `$length`, `$special_chars`, and `$extra_special_chars` parameters.
			 *
			 * @param string $password            The generated password.
			 * @param int    $length              The length of password to generate.
			 * @param bool   $special_chars       Whether to include standard special characters.
			 * @param bool   $extra_special_chars Whether to include other special characters.
			 */
			return apply_filters( 'random_password', $password, $length, $special_chars, $extra_special_chars );
		}
endif;

// wp-includes/pluggable.php (WP 6.8.6)
if( ! function_exists( 'wp_parse_auth_cookie' ) ) :
		function wp_parse_auth_cookie( $cookie = '', $scheme = '' ) {
			if ( empty( $cookie ) ) {
				switch ( $scheme ) {
					case 'auth':
						$cookie_name = AUTH_COOKIE;
						break;
					case 'secure_auth':
						$cookie_name = SECURE_AUTH_COOKIE;
						break;
					case 'logged_in':
						$cookie_name = LOGGED_IN_COOKIE;
						break;
					default:
						if ( is_ssl() ) {
							$cookie_name = SECURE_AUTH_COOKIE;
							$scheme      = 'secure_auth';
						} else {
							$cookie_name = AUTH_COOKIE;
							$scheme      = 'auth';
						}
				}
	
				if ( empty( $_COOKIE[ $cookie_name ] ) ) {
					return false;
				}
				$cookie = $_COOKIE[ $cookie_name ];
			}
	
			$cookie_elements = explode( '|', $cookie );
			if ( count( $cookie_elements ) !== 4 ) {
				return false;
			}
	
			list( $username, $expiration, $token, $hmac ) = $cookie_elements;
	
			return compact( 'username', 'expiration', 'token', 'hmac', 'scheme' );
		}
endif;

// wp-includes/pluggable.php (WP 6.8.6)
if( ! function_exists( 'wp_sanitize_redirect' ) ) :
		function wp_sanitize_redirect( $location ) {
			// Encode spaces.
			$location = str_replace( ' ', '%20', $location );
	
			$regex    = '/
			(
				(?: [\xC2-\xDF][\x80-\xBF]        # double-byte sequences   110xxxxx 10xxxxxx
				|   \xE0[\xA0-\xBF][\x80-\xBF]    # triple-byte sequences   1110xxxx 10xxxxxx * 2
				|   [\xE1-\xEC][\x80-\xBF]{2}
				|   \xED[\x80-\x9F][\x80-\xBF]
				|   [\xEE-\xEF][\x80-\xBF]{2}
				|   \xF0[\x90-\xBF][\x80-\xBF]{2} # four-byte sequences   11110xxx 10xxxxxx * 3
				|   [\xF1-\xF3][\x80-\xBF]{3}
				|   \xF4[\x80-\x8F][\x80-\xBF]{2}
			){1,40}                              # ...one or more times
			)/x';
			$location = preg_replace_callback( $regex, '_wp_sanitize_utf8_in_redirect', $location );
			$location = preg_replace( '|[^a-z0-9-~+_.?#=&;,/:%!*\[\]()@]|i', '', $location );
			$location = wp_kses_no_null( $location );
	
			// Remove %0D and %0A from location.
			$strip = array( '%0d', '%0a', '%0D', '%0A' );
			return _deep_replace( $strip, $location );
		}
endif;

// wp-includes/pluggable.php (WP 6.8.6)
if( ! function_exists( 'wp_hash' ) ) :
		function wp_hash( $data, $scheme = 'auth', $algo = 'md5' ) {
			$salt = wp_salt( $scheme );
	
			// Ensure the algorithm is supported by the hash_hmac function.
			if ( ! in_array( $algo, hash_hmac_algos(), true ) ) {
				throw new InvalidArgumentException(
					sprintf(
						/* translators: 1: Name of a cryptographic hash algorithm. 2: List of supported algorithms. */
						__( 'Unsupported hashing algorithm: %1$s. Supported algorithms are: %2$s' ),
						$algo,
						implode( ', ', hash_hmac_algos() )
					)
				);
			}
	
			return hash_hmac( $algo, $data, $salt );
		}
endif;

