<?php

// ------------------auto-generated---------------------

// wp-includes/script-loader.php (WP 6.9.4)
if( ! function_exists( 'wp_prototype_before_jquery' ) ) :
	function wp_prototype_before_jquery( $js_array ) {
		$prototype = array_search( 'prototype', $js_array, true );
	
		if ( false === $prototype ) {
			return $js_array;
		}
	
		$jquery = array_search( 'jquery', $js_array, true );
	
		if ( false === $jquery ) {
			return $js_array;
		}
	
		if ( $prototype < $jquery ) {
			return $js_array;
		}
	
		unset( $js_array[ $prototype ] );
	
		array_splice( $js_array, $jquery, 0, 'prototype' );
	
		return $js_array;
	}
endif;

// wp-includes/script-loader.php (WP 6.9.4)
if( ! function_exists( 'wp_sanitize_script_attributes' ) ) :
	function wp_sanitize_script_attributes( $attributes ) {
		$html5_script_support = is_admin() || current_theme_supports( 'html5', 'script' );
		$attributes_string    = '';
	
		/*
		 * If HTML5 script tag is supported, only the attribute name is added
		 * to $attributes_string for entries with a boolean value, and that are true.
		 */
		foreach ( $attributes as $attribute_name => $attribute_value ) {
			if ( is_bool( $attribute_value ) ) {
				if ( $attribute_value ) {
					$attributes_string .= $html5_script_support ? ' ' . esc_attr( $attribute_name ) : sprintf( ' %1$s="%2$s"', esc_attr( $attribute_name ), esc_attr( $attribute_name ) );
				}
			} else {
				$attributes_string .= sprintf( ' %1$s="%2$s"', esc_attr( $attribute_name ), esc_attr( $attribute_value ) );
			}
		}
	
		return $attributes_string;
	}
endif;

// wp-includes/script-loader.php (WP 6.9.4)
if( ! function_exists( 'wp_get_script_tag' ) ) :
	function wp_get_script_tag( $attributes ) {
		if ( ! isset( $attributes['type'] ) && ! is_admin() && ! current_theme_supports( 'html5', 'script' ) ) {
			// Keep the type attribute as the first for legacy reasons (it has always been this way in core).
			$attributes = array_merge(
				array( 'type' => 'text/javascript' ),
				$attributes
			);
		}
		/**
		 * Filters attributes to be added to a script tag.
		 *
		 * @since 5.7.0
		 *
		 * @param array $attributes Key-value pairs representing `<script>` tag attributes.
		 *                          Only the attribute name is added to the `<script>` tag for
		 *                          entries with a boolean value, and that are true.
		 */
		$attributes = apply_filters( 'wp_script_attributes', $attributes );
	
		return sprintf( "<script%s></script>\n", wp_sanitize_script_attributes( $attributes ) );
	}
endif;

// wp-includes/script-loader.php (WP 6.9.4)
if( ! function_exists( 'wp_print_script_tag' ) ) :
	function wp_print_script_tag( $attributes ) {
		echo wp_get_script_tag( $attributes );
	}
endif;

// wp-includes/script-loader.php (WP 6.9.4)
if( ! function_exists( 'wp_get_inline_script_tag' ) ) :
	function wp_get_inline_script_tag( $data, $attributes = array() ) {
		$is_html5 = current_theme_supports( 'html5', 'script' ) || is_admin();
		if ( ! isset( $attributes['type'] ) && ! $is_html5 ) {
			// Keep the type attribute as the first for legacy reasons (it has always been this way in core).
			$attributes = array_merge(
				array( 'type' => 'text/javascript' ),
				$attributes
			);
		}
	
		/*
		 * XHTML extracts the contents of the SCRIPT element and then the XML parser
		 * decodes character references and other syntax elements. This can lead to
		 * misinterpretation of the script contents or invalid XHTML documents.
		 *
		 * Wrapping the contents in a CDATA section instructs the XML parser not to
		 * transform the contents of the SCRIPT element before passing them to the
		 * JavaScript engine.
		 *
		 * Example:
		 *
		 *     <script>console.log('&hellip;');</script>
		 *
		 *     In an HTML document this would print "&hellip;" to the console,
		 *     but in an XHTML document it would print "…" to the console.
		 *
		 *     <script>console.log('An image is <img> in HTML');</script>
		 *
		 *     In an HTML document this would print "An image is <img> in HTML",
		 *     but it's an invalid XHTML document because it interprets the `<img>`
		 *     as an empty tag missing its closing `/`.
		 *
		 * @see https://www.w3.org/TR/xhtml1/#h-4.8
		 */
		if (
			! $is_html5 &&
			(
				! isset( $attributes['type'] ) ||
				'module' === $attributes['type'] ||
				str_contains( $attributes['type'], 'javascript' ) ||
				str_contains( $attributes['type'], 'ecmascript' ) ||
				str_contains( $attributes['type'], 'jscript' ) ||
				str_contains( $attributes['type'], 'livescript' )
			)
		) {
			/*
			 * If the string `]]>` exists within the JavaScript it would break
			 * out of any wrapping CDATA section added here, so to start, it's
			 * necessary to escape that sequence which requires splitting the
			 * content into two CDATA sections wherever it's found.
			 *
			 * Note: it's only necessary to escape the closing `]]>` because
			 * an additional `<![CDATA[` leaves the contents unchanged.
			 */
			$data = str_replace( ']]>', ']]]]><![CDATA[>', $data );
	
			// Wrap the entire escaped script inside a CDATA section.
			$data = sprintf( "/* <![CDATA[ */\n%s\n/* ]]> */", $data );
		}
	
		$data = "\n" . trim( $data, "\n\r " ) . "\n";
	
		/**
		 * Filters attributes to be added to a script tag.
		 *
		 * @since 5.7.0
		 *
		 * @param array  $attributes Key-value pairs representing `<script>` tag attributes.
		 *                           Only the attribute name is added to the `<script>` tag for
		 *                           entries with a boolean value, and that are true.
		 * @param string $data       Inline data.
		 */
		$attributes = apply_filters( 'wp_inline_script_attributes', $attributes, $data );
	
		return sprintf( "<script%s>%s</script>\n", wp_sanitize_script_attributes( $attributes ), $data );
	}
endif;

// wp-includes/script-loader.php (WP 6.9.4)
if( ! function_exists( 'wp_print_inline_script_tag' ) ) :
	function wp_print_inline_script_tag( $data, $attributes = array() ) {
		echo wp_get_inline_script_tag( $data, $attributes );
	}
endif;

// wp-includes/script-loader.php (WP 6.9.4)
if( ! function_exists( '_print_scripts' ) ) :
	function _print_scripts() {
		global $wp_scripts, $compress_scripts;
	
		$zip = $compress_scripts ? 1 : 0;
		if ( $zip && defined( 'ENFORCE_GZIP' ) && ENFORCE_GZIP ) {
			$zip = 'gzip';
		}
	
		$concat    = trim( $wp_scripts->concat, ', ' );
		$type_attr = current_theme_supports( 'html5', 'script' ) ? '' : " type='text/javascript'";
	
		if ( $concat ) {
			if ( ! empty( $wp_scripts->print_code ) ) {
				echo "\n<script{$type_attr}>\n";
				echo "/* <![CDATA[ */\n"; // Not needed in HTML 5.
				echo $wp_scripts->print_code;
				echo sprintf( "\n//# sourceURL=%s\n", rawurlencode( 'js-inline-concat-' . $concat ) );
				echo "/* ]]> */\n";
				echo "</script>\n";
			}
	
			$concat       = str_split( $concat, 128 );
			$concatenated = '';
	
			foreach ( $concat as $key => $chunk ) {
				$concatenated .= "&load%5Bchunk_{$key}%5D={$chunk}";
			}
	
			$src = $wp_scripts->base_url . "/wp-admin/load-scripts.php?c={$zip}" . $concatenated . '&ver=' . $wp_scripts->default_version;
			echo "<script{$type_attr} src='" . esc_attr( $src ) . "'></script>\n";
		}
	
		if ( ! empty( $wp_scripts->print_html ) ) {
			echo $wp_scripts->print_html;
		}
	}
endif;

// wp-includes/script-loader.php (WP 6.9.4)
if( ! function_exists( 'wp_remove_surrounding_empty_script_tags' ) ) :
	function wp_remove_surrounding_empty_script_tags( $contents ) {
		$contents = trim( $contents );
		$opener   = '<SCRIPT>';
		$closer   = '</SCRIPT>';
	
		if (
			strlen( $contents ) > strlen( $opener ) + strlen( $closer ) &&
			strtoupper( substr( $contents, 0, strlen( $opener ) ) ) === $opener &&
			strtoupper( substr( $contents, -strlen( $closer ) ) ) === $closer
		) {
			return substr( $contents, strlen( $opener ), -strlen( $closer ) );
		} else {
			$error_message = __( 'Expected string to start with script tag (without attributes) and end with script tag, with optional whitespace.' );
			_doing_it_wrong( __FUNCTION__, $error_message, '6.4' );
			return sprintf(
				'console.error(%s)',
				wp_json_encode(
					sprintf(
						/* translators: %s: wp_remove_surrounding_empty_script_tags() */
						__( 'Function %s used incorrectly in PHP.' ),
						'wp_remove_surrounding_empty_script_tags()'
					) . ' ' . $error_message
				)
			);
		}
	}
endif;

// wp-includes/script-loader.php (WP 6.9.4)
if( ! function_exists( 'wp_filter_out_block_nodes' ) ) :
	function wp_filter_out_block_nodes( $nodes ) {
		return array_filter(
			$nodes,
			static function ( $node ) {
				return ! in_array( 'blocks', $node['path'], true );
			},
			ARRAY_FILTER_USE_BOTH
		);
	}
endif;

// wp-includes/script-loader.php (WP 6.9.4)
if( ! function_exists( '_wp_normalize_relative_css_links' ) ) :
	function _wp_normalize_relative_css_links( $css, $stylesheet_url ) {
		return preg_replace_callback(
			'#(url\s*\(\s*[\'"]?\s*)([^\'"\)]+)#',
			static function ( $matches ) use ( $stylesheet_url ) {
				list( , $prefix, $url ) = $matches;
	
				// Short-circuit if the URL does not require normalization.
				if (
					str_starts_with( $url, 'http:' ) ||
					str_starts_with( $url, 'https:' ) ||
					str_starts_with( $url, '/' ) ||
					str_starts_with( $url, '#' ) ||
					str_starts_with( $url, 'data:' )
				) {
					return $matches[0];
				}
	
				// Build the absolute URL.
				$absolute_url = dirname( $stylesheet_url ) . '/' . $url;
				$absolute_url = str_replace( '/./', '/', $absolute_url );
	
				// Convert to URL related to the site root.
				$url = wp_make_link_relative( $absolute_url );
	
				return $prefix . $url;
			},
			$css
		);
	}
endif;

