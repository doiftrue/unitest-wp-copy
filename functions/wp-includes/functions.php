<?php

// ------------------auto-generated---------------------

// wp-includes/functions.php (WP 6.8.3)
function number_format_i18n( $number, $decimals = 0 ) {
	global $wp_locale;

	if ( isset( $wp_locale ) ) {
		$formatted = number_format( $number, absint( $decimals ), $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep'] );
	} else {
		$formatted = number_format( $number, absint( $decimals ) );
	}

	/**
	 * Filters the number formatted based on the locale.
	 *
	 * @since 2.8.0
	 * @since 4.9.0 The `$number` and `$decimals` parameters were added.
	 *
	 * @param string $formatted Converted number in string format.
	 * @param float  $number    The number to convert based on locale.
	 * @param int    $decimals  Precision of the number of decimal places.
	 */
	return apply_filters( 'number_format_i18n', $formatted, $number, $decimals );
}

// wp-includes/functions.php (WP 6.8.3)
function size_format( $bytes, $decimals = 0 ) {
	$quant = array(
		/* translators: Unit symbol for yottabyte. */
		_x( 'YB', 'unit symbol' ) => YB_IN_BYTES,
		/* translators: Unit symbol for zettabyte. */
		_x( 'ZB', 'unit symbol' ) => ZB_IN_BYTES,
		/* translators: Unit symbol for exabyte. */
		_x( 'EB', 'unit symbol' ) => EB_IN_BYTES,
		/* translators: Unit symbol for petabyte. */
		_x( 'PB', 'unit symbol' ) => PB_IN_BYTES,
		/* translators: Unit symbol for terabyte. */
		_x( 'TB', 'unit symbol' ) => TB_IN_BYTES,
		/* translators: Unit symbol for gigabyte. */
		_x( 'GB', 'unit symbol' ) => GB_IN_BYTES,
		/* translators: Unit symbol for megabyte. */
		_x( 'MB', 'unit symbol' ) => MB_IN_BYTES,
		/* translators: Unit symbol for kilobyte. */
		_x( 'KB', 'unit symbol' ) => KB_IN_BYTES,
		/* translators: Unit symbol for byte. */
		_x( 'B', 'unit symbol' )  => 1,
	);

	if ( 0 === $bytes ) {
		/* translators: Unit symbol for byte. */
		return number_format_i18n( 0, $decimals ) . ' ' . _x( 'B', 'unit symbol' );
	}

	foreach ( $quant as $unit => $mag ) {
		if ( (float) $bytes >= $mag ) {
			return number_format_i18n( $bytes / $mag, $decimals ) . ' ' . $unit;
		}
	}

	return false;
}

// wp-includes/functions.php (WP 6.8.3)
function maybe_serialize( $data ) {
	if ( is_array( $data ) || is_object( $data ) ) {
		return serialize( $data );
	}

	/*
	 * Double serialization is required for backward compatibility.
	 * See https://core.trac.wordpress.org/ticket/12930
	 * Also the world will end. See WP 3.6.1.
	 */
	if ( is_serialized( $data, false ) ) {
		return serialize( $data );
	}

	return $data;
}

// wp-includes/functions.php (WP 6.8.3)
function maybe_unserialize( $data ) {
	if ( is_serialized( $data ) ) { // Don't attempt to unserialize data that wasn't serialized going in.
		return @unserialize( trim( $data ) );
	}

	return $data;
}

// wp-includes/functions.php (WP 6.8.3)
function is_serialized( $data, $strict = true ) {
	// If it isn't a string, it isn't serialized.
	if ( ! is_string( $data ) ) {
		return false;
	}
	$data = trim( $data );
	if ( 'N;' === $data ) {
		return true;
	}
	if ( strlen( $data ) < 4 ) {
		return false;
	}
	if ( ':' !== $data[1] ) {
		return false;
	}
	if ( $strict ) {
		$lastc = substr( $data, -1 );
		if ( ';' !== $lastc && '}' !== $lastc ) {
			return false;
		}
	} else {
		$semicolon = strpos( $data, ';' );
		$brace     = strpos( $data, '}' );
		// Either ; or } must exist.
		if ( false === $semicolon && false === $brace ) {
			return false;
		}
		// But neither must be in the first X characters.
		if ( false !== $semicolon && $semicolon < 3 ) {
			return false;
		}
		if ( false !== $brace && $brace < 4 ) {
			return false;
		}
	}
	$token = $data[0];
	switch ( $token ) {
		case 's':
			if ( $strict ) {
				if ( '"' !== substr( $data, -2, 1 ) ) {
					return false;
				}
			} elseif ( ! str_contains( $data, '"' ) ) {
				return false;
			}
			// Or else fall through.
		case 'a':
		case 'O':
		case 'E':
			return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
		case 'b':
		case 'i':
		case 'd':
			$end = $strict ? '$' : '';
			return (bool) preg_match( "/^{$token}:[0-9.E+-]+;$end/", $data );
	}
	return false;
}

// wp-includes/functions.php (WP 6.8.3)
function is_serialized_string( $data ) {
	// if it isn't a string, it isn't a serialized string.
	if ( ! is_string( $data ) ) {
		return false;
	}
	$data = trim( $data );
	if ( strlen( $data ) < 4 ) {
		return false;
	} elseif ( ':' !== $data[1] ) {
		return false;
	} elseif ( ! str_ends_with( $data, ';' ) ) {
		return false;
	} elseif ( 's' !== $data[0] ) {
		return false;
	} elseif ( '"' !== substr( $data, -2, 1 ) ) {
		return false;
	} else {
		return true;
	}
}

// wp-includes/functions.php (WP 6.8.3)
function wp_normalize_path( $path ) {
	$wrapper = '';

	if ( wp_is_stream( $path ) ) {
		list( $wrapper, $path ) = explode( '://', $path, 2 );

		$wrapper .= '://';
	}

	// Standardize all paths to use '/'.
	$path = str_replace( '\\', '/', $path );

	// Replace multiple slashes down to a singular, allowing for network shares having two slashes.
	$path = preg_replace( '|(?<=.)/+|', '/', $path );

	// Windows paths should uppercase the drive letter.
	if ( ':' === substr( $path, 1, 1 ) ) {
		$path = ucfirst( $path );
	}

	return $wrapper . $path;
}

// wp-includes/functions.php (WP 6.8.3)
function wp_parse_args( $args, $defaults = array() ) {
	if ( is_object( $args ) ) {
		$parsed_args = get_object_vars( $args );
	} elseif ( is_array( $args ) ) {
		$parsed_args =& $args;
	} else {
		wp_parse_str( $args, $parsed_args );
	}

	if ( is_array( $defaults ) && $defaults ) {
		return array_merge( $defaults, $parsed_args );
	}
	return $parsed_args;
}

// wp-includes/functions.php (WP 6.8.3)
function wp_parse_list( $input_list ) {
	if ( ! is_array( $input_list ) ) {
		return preg_split( '/[\s,]+/', $input_list, -1, PREG_SPLIT_NO_EMPTY );
	}

	// Validate all entries of the list are scalar.
	$input_list = array_filter( $input_list, 'is_scalar' );

	return $input_list;
}

// wp-includes/functions.php (WP 6.8.3)
function _deprecated_argument( $function_name, $version, $message = '' ) {

	/**
	 * Fires when a deprecated argument is called.
	 *
	 * @since 3.0.0
	 *
	 * @param string $function_name The function that was called.
	 * @param string $message       A message regarding the change.
	 * @param string $version       The version of WordPress that deprecated the argument used.
	 */
	do_action( 'deprecated_argument_run', $function_name, $message, $version );

	/**
	 * Filters whether to trigger an error for deprecated arguments.
	 *
	 * @since 3.0.0
	 *
	 * @param bool $trigger Whether to trigger the error for deprecated arguments. Default true.
	 */
	if ( WP_DEBUG && apply_filters( 'deprecated_argument_trigger_error', true ) ) {
		if ( function_exists( '__' ) ) {
			if ( $message ) {
				$message = sprintf(
					/* translators: 1: PHP function name, 2: Version number, 3: Optional message regarding the change. */
					__( 'Function %1$s was called with an argument that is <strong>deprecated</strong> since version %2$s! %3$s' ),
					$function_name,
					$version,
					$message
				);
			} else {
				$message = sprintf(
					/* translators: 1: PHP function name, 2: Version number. */
					__( 'Function %1$s was called with an argument that is <strong>deprecated</strong> since version %2$s with no alternative available.' ),
					$function_name,
					$version
				);
			}
		} else {
			if ( $message ) {
				$message = sprintf(
					'Function %1$s was called with an argument that is <strong>deprecated</strong> since version %2$s! %3$s',
					$function_name,
					$version,
					$message
				);
			} else {
				$message = sprintf(
					'Function %1$s was called with an argument that is <strong>deprecated</strong> since version %2$s with no alternative available.',
					$function_name,
					$version
				);
			}
		}

		wp_trigger_error( '', $message, E_USER_DEPRECATED );
	}
}

// wp-includes/functions.php (WP 6.8.3)
function _doing_it_wrong( $function_name, $message, $version ) {

	/**
	 * Fires when the given function is being used incorrectly.
	 *
	 * @since 3.1.0
	 *
	 * @param string $function_name The function that was called.
	 * @param string $message       A message explaining what has been done incorrectly.
	 * @param string $version       The version of WordPress where the message was added.
	 */
	do_action( 'doing_it_wrong_run', $function_name, $message, $version );

	/**
	 * Filters whether to trigger an error for _doing_it_wrong() calls.
	 *
	 * @since 3.1.0
	 * @since 5.1.0 Added the $function_name, $message and $version parameters.
	 *
	 * @param bool   $trigger       Whether to trigger the error for _doing_it_wrong() calls. Default true.
	 * @param string $function_name The function that was called.
	 * @param string $message       A message explaining what has been done incorrectly.
	 * @param string $version       The version of WordPress where the message was added.
	 */
	if ( WP_DEBUG && apply_filters( 'doing_it_wrong_trigger_error', true, $function_name, $message, $version ) ) {
		if ( function_exists( '__' ) ) {
			if ( $version ) {
				/* translators: %s: Version number. */
				$version = sprintf( __( '(This message was added in version %s.)' ), $version );
			}

			$message .= ' ' . sprintf(
				/* translators: %s: Documentation URL. */
				__( 'Please see <a href="%s">Debugging in WordPress</a> for more information.' ),
				__( 'https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/' )
			);

			$message = sprintf(
				/* translators: Developer debugging message. 1: PHP function name, 2: Explanatory message, 3: WordPress version number. */
				__( 'Function %1$s was called <strong>incorrectly</strong>. %2$s %3$s' ),
				$function_name,
				$message,
				$version
			);
		} else {
			if ( $version ) {
				$version = sprintf( '(This message was added in version %s.)', $version );
			}

			$message .= sprintf(
				' Please see <a href="%s">Debugging in WordPress</a> for more information.',
				'https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/'
			);

			$message = sprintf(
				'Function %1$s was called <strong>incorrectly</strong>. %2$s %3$s',
				$function_name,
				$message,
				$version
			);
		}

		wp_trigger_error( '', $message );
	}
}

// wp-includes/functions.php (WP 6.8.3)
function wp_trigger_error( $function_name, $message, $error_level = E_USER_NOTICE ) {

	// Bail out if WP_DEBUG is not turned on.
	if ( ! WP_DEBUG ) {
		return;
	}

	/**
	 * Fires when the given function triggers a user-level error/warning/notice/deprecation message.
	 *
	 * Can be used for debug backtracking.
	 *
	 * @since 6.4.0
	 *
	 * @param string $function_name The function that was called.
	 * @param string $message       A message explaining what has been done incorrectly.
	 * @param int    $error_level   The designated error type for this error.
	 */
	do_action( 'wp_trigger_error_run', $function_name, $message, $error_level );

	if ( ! empty( $function_name ) ) {
		$message = sprintf( '%s(): %s', $function_name, $message );
	}

	$message = wp_kses(
		$message,
		array(
			'a'      => array( 'href' => true ),
			'br'     => array(),
			'code'   => array(),
			'em'     => array(),
			'strong' => array(),
		),
		array( 'http', 'https' )
	);

	if ( E_USER_ERROR === $error_level ) {
		throw new WP_Exception( $message );
	}

	trigger_error( $message, $error_level );
}

// wp-includes/functions.php (WP 6.8.3)
function _cleanup_header_comment( $str ) {
	return trim( preg_replace( '/\s*(?:\*\/|\?>).*/', '', $str ) );
}

// wp-includes/functions.php (WP 6.8.3)
function get_file_data( $file, $default_headers, $context = '' ) {
	// Pull only the first 8 KB of the file in.
	$file_data = file_get_contents( $file, false, null, 0, 8 * KB_IN_BYTES );

	if ( false === $file_data ) {
		$file_data = '';
	}

	// Make sure we catch CR-only line endings.
	$file_data = str_replace( "\r", "\n", $file_data );

	/**
	 * Filters extra file headers by context.
	 *
	 * The dynamic portion of the hook name, `$context`, refers to
	 * the context where extra headers might be loaded.
	 *
	 * @since 2.9.0
	 *
	 * @param array $extra_context_headers Empty array by default.
	 */
	$extra_headers = $context ? apply_filters( "extra_{$context}_headers", array() ) : array();
	if ( $extra_headers ) {
		$extra_headers = array_combine( $extra_headers, $extra_headers ); // Keys equal values.
		$all_headers   = array_merge( $extra_headers, (array) $default_headers );
	} else {
		$all_headers = $default_headers;
	}

	foreach ( $all_headers as $field => $regex ) {
		if ( preg_match( '/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] ) {
			$all_headers[ $field ] = _cleanup_header_comment( $match[1] );
		} else {
			$all_headers[ $field ] = '';
		}
	}

	return $all_headers;
}

// wp-includes/functions.php (WP 6.8.3)
function wp_is_stream( $path ) {
	$scheme_separator = strpos( $path, '://' );

	if ( false === $scheme_separator ) {
		// $path isn't a stream.
		return false;
	}

	$stream = substr( $path, 0, $scheme_separator );

	return in_array( $stream, stream_get_wrappers(), true );
}

// wp-includes/functions.php (WP 6.8.3)
function is_utf8_charset( $blog_charset = null ) {
	return _is_utf8_charset( $blog_charset ?? WPCOPY__OPTION_BLOG_CHARSET );
}

// wp-includes/functions.php (WP 6.8.3)
function _canonical_charset( $charset ) {
	if ( is_utf8_charset( $charset ) ) {
		return 'UTF-8';
	}

	/*
	 * Normalize the ISO-8859-1 family of languages.
	 *
	 * This is not required for htmlspecialchars(), as it properly recognizes all of
	 * the input character sets that here are transformed into "ISO-8859-1".
	 *
	 * @todo Should this entire check be removed since it's not required for the stated purpose?
	 * @todo Should WordPress transform other potential charset equivalents, such as "latin1"?
	 */
	if (
		( 0 === strcasecmp( 'iso-8859-1', $charset ) ) ||
		( 0 === strcasecmp( 'iso8859-1', $charset ) )
	) {
		return 'ISO-8859-1';
	}

	return $charset;
}

