<?php

// ------------------auto-generated---------------------

namespace Unitest_WP_Copy;

// wp-includes/class-wpdb.php (WP 6.9.5)
trait wpdb__Copied_Methods {

	public function _escape( $data ) {
		if ( is_array( $data ) ) {
			foreach ( $data as $k => $v ) {
				if ( is_array( $v ) ) {
					$data[ $k ] = $this->_escape( $v );
				} else {
					$data[ $k ] = $this->_real_escape( $v );
				}
			}
		} else {
			$data = $this->_real_escape( $data );
		}

		return $data;
	}

	private function _escape_identifier_value( $identifier ) {
		return str_replace( '`', '``', $identifier );
	}

	public function prepare( $query, ...$args ) {
		if ( is_null( $query ) ) {
			return;
		}

		/*
		 * This is not meant to be foolproof -- but it will catch obviously incorrect usage.
		 *
		 * Note: str_contains() is not used here, as this file can be included
		 * directly outside of WordPress core, e.g. by HyperDB, in which case
		 * the polyfills from wp-includes/compat.php are not loaded.
		 */
		if ( false === strpos( $query, '%' ) ) {
			wp_load_translations_early();
			_doing_it_wrong(
				'wpdb::prepare',
				sprintf(
					/* translators: %s: wpdb::prepare() */
					__( 'The query argument of %s must have a placeholder.' ),
					'wpdb::prepare()'
				),
				'3.9.0'
			);
		}

		/*
		 * Specify the formatting allowed in a placeholder. The following are allowed:
		 *
		 * - Sign specifier, e.g. $+d
		 * - Numbered placeholders, e.g. %1$s
		 * - Padding specifier, including custom padding characters, e.g. %05s, %'#5s
		 * - Alignment specifier, e.g. %05-s
		 * - Precision specifier, e.g. %.2f
		 */
		$allowed_format = '(?:[1-9][0-9]*[$])?[-+0-9]*(?: |0|\'.)?[-+0-9]*(?:\.[0-9]+)?';

		/*
		 * If a %s placeholder already has quotes around it, removing the existing quotes
		 * and re-inserting them ensures the quotes are consistent.
		 *
		 * For backward compatibility, this is only applied to %s, and not to placeholders like %1$s,
		 * which are frequently used in the middle of longer strings, or as table name placeholders.
		 */
		$query = str_replace( "'%s'", '%s', $query ); // Strip any existing single quotes.
		$query = str_replace( '"%s"', '%s', $query ); // Strip any existing double quotes.

		// Escape any unescaped percents (i.e. anything unrecognised).
		$query = preg_replace( "/%(?:%|$|(?!($allowed_format)?[sdfFi]))/", '%%\\1', $query );

		// Extract placeholders from the query.
		$split_query = preg_split( "/(^|[^%]|(?:%%)+)(%(?:$allowed_format)?[sdfFi])/", $query, -1, PREG_SPLIT_DELIM_CAPTURE );

		$split_query_count = count( $split_query );

		/*
		 * Split always returns with 1 value before the first placeholder (even with $query = "%s"),
		 * then 3 additional values per placeholder.
		 */
		$placeholder_count = ( ( $split_query_count - 1 ) / 3 );

		// If args were passed as an array, as in vsprintf(), move them up.
		$passed_as_array = ( isset( $args[0] ) && is_array( $args[0] ) && 1 === count( $args ) );
		if ( $passed_as_array ) {
			$args = $args[0];
		}

		$new_query       = '';
		$key             = 2; // Keys 0 and 1 in $split_query contain values before the first placeholder.
		$arg_id          = 0;
		$arg_identifiers = array();
		$arg_strings     = array();

		while ( $key < $split_query_count ) {
			$placeholder = $split_query[ $key ];

			$format = substr( $placeholder, 1, -1 );
			$type   = substr( $placeholder, -1 );

			if ( 'f' === $type && true === $this->allow_unsafe_unquoted_parameters
				/*
				 * Note: str_ends_with() is not used here, as this file can be included
				 * directly outside of WordPress core, e.g. by HyperDB, in which case
				 * the polyfills from wp-includes/compat.php are not loaded.
				 */
				&& '%' === substr( $split_query[ $key - 1 ], -1, 1 )
			) {

				/*
				 * Before WP 6.2 the "force floats to be locale-unaware" RegEx didn't
				 * convert "%%%f" to "%%%F" (note the uppercase F).
				 * This was because it didn't check to see if the leading "%" was escaped.
				 * And because the "Escape any unescaped percents" RegEx used "[sdF]" in its
				 * negative lookahead assertion, when there was an odd number of "%", it added
				 * an extra "%", to give the fully escaped "%%%%f" (not a placeholder).
				 */

				$s = $split_query[ $key - 2 ] . $split_query[ $key - 1 ];
				$k = 1;
				$l = strlen( $s );
				while ( $k <= $l && '%' === $s[ $l - $k ] ) {
					++$k;
				}

				$placeholder = '%' . ( $k % 2 ? '%' : '' ) . $format . $type;

				--$placeholder_count;

			} else {

				// Force floats to be locale-unaware.
				if ( 'f' === $type ) {
					$type        = 'F';
					$placeholder = '%' . $format . $type;
				}

				if ( 'i' === $type ) {
					$placeholder = '`%' . $format . 's`';
					// Using a simple strpos() due to previous checking (e.g. $allowed_format).
					$argnum_pos = strpos( $format, '$' );

					if ( false !== $argnum_pos ) {
						// sprintf() argnum starts at 1, $arg_id from 0.
						$arg_identifiers[] = ( ( (int) substr( $format, 0, $argnum_pos ) ) - 1 );
					} else {
						$arg_identifiers[] = $arg_id;
					}
				} elseif ( 'd' !== $type && 'F' !== $type ) {
					/*
					 * i.e. ( 's' === $type ), where 'd' and 'F' keeps $placeholder unchanged,
					 * and we ensure string escaping is used as a safe default (e.g. even if 'x').
					 */
					$argnum_pos = strpos( $format, '$' );

					if ( false !== $argnum_pos ) {
						$arg_strings[] = ( ( (int) substr( $format, 0, $argnum_pos ) ) - 1 );
					} else {
						$arg_strings[] = $arg_id;
					}

					/*
					 * Unquoted strings for backward compatibility (dangerous).
					 * First, "numbered or formatted string placeholders (eg, %1$s, %5s)".
					 * Second, if "%s" has a "%" before it, even if it's unrelated (e.g. "LIKE '%%%s%%'").
					 */
					if ( true !== $this->allow_unsafe_unquoted_parameters
						/*
						 * Note: str_ends_with() is not used here, as this file can be included
						 * directly outside of WordPress core, e.g. by HyperDB, in which case
						 * the polyfills from wp-includes/compat.php are not loaded.
						 */
						|| ( '' === $format && '%' !== substr( $split_query[ $key - 1 ], -1, 1 ) )
					) {
						$placeholder = "'%" . $format . "s'";
					}
				}
			}

			// Glue (-2), any leading characters (-1), then the new $placeholder.
			$new_query .= $split_query[ $key - 2 ] . $split_query[ $key - 1 ] . $placeholder;

			$key += 3;
			++$arg_id;
		}

		// Replace $query; and add remaining $query characters, or index 0 if there were no placeholders.
		$query = $new_query . $split_query[ $key - 2 ];

		$dual_use = array_intersect( $arg_identifiers, $arg_strings );

		if ( count( $dual_use ) > 0 ) {
			wp_load_translations_early();

			$used_placeholders = array();

			$key    = 2;
			$arg_id = 0;
			// Parse again (only used when there is an error).
			while ( $key < $split_query_count ) {
				$placeholder = $split_query[ $key ];

				$format = substr( $placeholder, 1, -1 );

				$argnum_pos = strpos( $format, '$' );

				if ( false !== $argnum_pos ) {
					$arg_pos = ( ( (int) substr( $format, 0, $argnum_pos ) ) - 1 );
				} else {
					$arg_pos = $arg_id;
				}

				$used_placeholders[ $arg_pos ][] = $placeholder;

				$key += 3;
				++$arg_id;
			}

			$conflicts = array();
			foreach ( $dual_use as $arg_pos ) {
				$conflicts[] = implode( ' and ', $used_placeholders[ $arg_pos ] );
			}

			_doing_it_wrong(
				'wpdb::prepare',
				sprintf(
					/* translators: %s: A list of placeholders found to be a problem. */
					__( 'Arguments cannot be prepared as both an Identifier and Value. Found the following conflicts: %s' ),
					implode( ', ', $conflicts )
				),
				'6.2.0'
			);

			return;
		}

		$args_count = count( $args );

		if ( $args_count !== $placeholder_count ) {
			if ( 1 === $placeholder_count && $passed_as_array ) {
				/*
				 * If the passed query only expected one argument,
				 * but the wrong number of arguments was sent as an array, bail.
				 */
				wp_load_translations_early();
				_doing_it_wrong(
					'wpdb::prepare',
					__( 'The query only expected one placeholder, but an array of multiple placeholders was sent.' ),
					'4.9.0'
				);

				return;
			} else {
				/*
				 * If we don't have the right number of placeholders,
				 * but they were passed as individual arguments,
				 * or we were expecting multiple arguments in an array, throw a warning.
				 */
				wp_load_translations_early();
				_doing_it_wrong(
					'wpdb::prepare',
					sprintf(
						/* translators: 1: Number of placeholders, 2: Number of arguments passed. */
						__( 'The query does not contain the correct number of placeholders (%1$d) for the number of arguments passed (%2$d).' ),
						$placeholder_count,
						$args_count
					),
					'4.8.3'
				);

				/*
				 * If we don't have enough arguments to match the placeholders,
				 * return an empty string to avoid a fatal error on PHP 8.
				 */
				if ( $args_count < $placeholder_count ) {
					$max_numbered_placeholder = 0;

					for ( $i = 2, $l = $split_query_count; $i < $l; $i += 3 ) {
						// Assume a leading number is for a numbered placeholder, e.g. '%3$s'.
						$argnum = (int) substr( $split_query[ $i ], 1 );

						if ( $max_numbered_placeholder < $argnum ) {
							$max_numbered_placeholder = $argnum;
						}
					}

					if ( ! $max_numbered_placeholder || $args_count < $max_numbered_placeholder ) {
						return '';
					}
				}
			}
		}

		$args_escaped = array();

		foreach ( $args as $i => $value ) {
			if ( in_array( $i, $arg_identifiers, true ) ) {
				$args_escaped[] = $this->_escape_identifier_value( $value );
			} elseif ( is_int( $value ) || is_float( $value ) ) {
				$args_escaped[] = $value;
			} else {
				if ( ! is_scalar( $value ) && ! is_null( $value ) ) {
					wp_load_translations_early();
					_doing_it_wrong(
						'wpdb::prepare',
						sprintf(
							/* translators: %s: Value type. */
							__( 'Unsupported value type (%s).' ),
							gettype( $value )
						),
						'4.8.2'
					);

					// Preserving old behavior, where values are escaped as strings.
					$value = '';
				}

				$args_escaped[] = $this->_real_escape( $value );
			}
		}

		$query = vsprintf( $query, $args_escaped );

		return $this->add_placeholder_escape( $query );
	}

	public function esc_like( $text ) {
		return addcslashes( $text, '_%\\' );
	}

	public function placeholder_escape() {
		static $placeholder;

		if ( ! $placeholder ) {
			// Old WP installs may not have AUTH_SALT defined.
			$salt = defined( 'AUTH_SALT' ) && AUTH_SALT ? AUTH_SALT : (string) rand();

			$placeholder = '{' . hash_hmac( 'sha256', uniqid( $salt, true ), $salt ) . '}';
		}

		/*
		 * Add the filter to remove the placeholder escaper. Uses priority 0, so that anything
		 * else attached to this filter will receive the query with the placeholder string removed.
		 */
		if ( false === has_filter( 'query', array( $this, 'remove_placeholder_escape' ) ) ) {
			add_filter( 'query', array( $this, 'remove_placeholder_escape' ), 0 );
		}

		return $placeholder;
	}

	public function add_placeholder_escape( $query ) {
		/*
		 * To prevent returning anything that even vaguely resembles a placeholder,
		 * we clobber every % we can find.
		 */
		return str_replace( '%', $this->placeholder_escape(), $query );
	}

	public function remove_placeholder_escape( $query ) {
		return str_replace( $this->placeholder_escape(), '%', $query );
	}

}
