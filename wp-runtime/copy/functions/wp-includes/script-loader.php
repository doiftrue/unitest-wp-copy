<?php

// ------------------auto-generated---------------------

// wp-includes/script-loader.php (WP 7.0.2)
if( ! function_exists( 'wp_js_dataset_name' ) ) :
	function wp_js_dataset_name( string $html_attribute_name ): ?string {
		if ( 0 !== substr_compare( $html_attribute_name, 'data-', 0, 5, true ) ) {
			return null;
		}
	
		$end = strlen( $html_attribute_name );
	
		/*
		 * If it contains characters which would end the attribute name parsing then
		 * something else is wrong and this contains more than just an attribute name.
		 */
		if ( ( $end - 5 ) !== strcspn( $html_attribute_name, "=/> \t\f\r\n", 5 ) ) {
			return null;
		}
	
		/**
		 * > For each name in list, for each U+002D HYPHEN-MINUS character (-)
		 * > in the name that is followed by an ASCII lower alpha, remove the
		 * > U+002D HYPHEN-MINUS character (-) and replace the character that
		 * > followed it by the same character converted to ASCII uppercase.
		 *
		 * @see https://html.spec.whatwg.org/#concept-domstringmap-pairs
		 */
		$custom_name = '';
		$at          = 5;
		$was_at      = $at;
	
		while ( $at < $end ) {
			$next_dash_at = strpos( $html_attribute_name, '-', $at );
			if ( false === $next_dash_at || $next_dash_at === $end - 1 ) {
				break;
			}
	
			// Transform `-a` to `A`, for example.
			$c = $html_attribute_name[ $next_dash_at + 1 ];
			if ( ( $c >= 'A' && $c <= 'Z' ) || ( $c >= 'a' && $c <= 'z' ) ) {
				$prefix       = substr( $html_attribute_name, $was_at, $next_dash_at - $was_at );
				$custom_name .= strtolower( $prefix );
				$custom_name .= strtoupper( $c );
				$at           = $next_dash_at + 2;
				$was_at       = $at;
				continue;
			}
	
			$at = $next_dash_at + 1;
		}
	
		// If nothing has been added it means there are no dash-letter pairs; return the name as-is.
		return '' === $custom_name
			? strtolower( substr( $html_attribute_name, 5 ) )
			: ( $custom_name . strtolower( substr( $html_attribute_name, $was_at ) ) );
	}
endif;

// wp-includes/script-loader.php (WP 7.0.2)
if( ! function_exists( 'wp_html_custom_data_attribute_name' ) ) :
	function wp_html_custom_data_attribute_name( string $js_dataset_name ): ?string {
		$end = strlen( $js_dataset_name );
		if ( 0 === $end ) {
			return 'data-';
		}
	
		/*
		 * If it contains characters which would end the attribute name parsing then
		 * something it’s not possible to represent this in HTML.
		 */
		if ( strcspn( $js_dataset_name, "=/> \t\f\r\n" ) !== $end ) {
			return null;
		}
	
		$html_name = 'data-';
		$at        = 0;
		$was_at    = $at;
	
		while ( $at < $end ) {
			$next_upper_after = strcspn( $js_dataset_name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', $at );
			$next_upper_at    = $at + $next_upper_after;
			if ( $next_upper_at >= $end ) {
				break;
			}
	
			$prefix     = substr( $js_dataset_name, $was_at, $next_upper_at - $was_at );
			$html_name .= strtolower( $prefix );
			$html_name .= '-' . strtolower( $js_dataset_name[ $next_upper_at ] );
			$at         = $next_upper_at + 1;
			$was_at     = $at;
		}
	
		if ( $was_at < $end ) {
			$html_name .= strtolower( substr( $js_dataset_name, $was_at ) );
		}
	
		return $html_name;
	}
endif;

// wp-includes/script-loader.php (WP 7.0.2)
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

// wp-includes/script-loader.php (WP 7.0.2)
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

// wp-includes/script-loader.php (WP 7.0.2)
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

// wp-includes/script-loader.php (WP 7.0.2)
if( ! function_exists( 'wp_get_script_tag' ) ) :
	function wp_get_script_tag( $attributes ) {
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
	
		$processor = new WP_HTML_Tag_Processor( '<script></script>' );
		$processor->next_tag();
		foreach ( $attributes as $name => $value ) {
			/*
			 * Lexical variations of an attribute name may represent the
			 * same attribute in HTML, therefore it’s possible that the
			 * input array might contain duplicate attributes even though
			 * it’s keyed on their name. Calling code should rewrite an
			 * attribute’s value rather than sending a duplicate attribute.
			 *
			 * Example:
			 *
			 *     array( 'id' => 'main', 'ID' => 'nav' )
			 *
			 * In this example, there are two keys both describing the `id`
			 * attribute. PHP array iteration is in key-insertion order so
			 * the 'id' value will be set in the SCRIPT tag.
			 */
			if ( null !== $processor->get_attribute( $name ) ) {
				continue;
			}
	
			$processor->set_attribute( $name, $value ?? true );
		}
		return "{$processor->get_updated_html()}\n";
	}
endif;

// wp-includes/script-loader.php (WP 7.0.2)
if( ! function_exists( 'wp_print_script_tag' ) ) :
	function wp_print_script_tag( $attributes ) {
		echo wp_get_script_tag( $attributes );
	}
endif;

// wp-includes/script-loader.php (WP 7.0.2)
if( ! function_exists( 'wp_get_inline_script_tag' ) ) :
	function wp_get_inline_script_tag( $data, $attributes = array() ) {
		$data = "\n" . trim( $data, "\n\r " ) . "\n";
	
		/**
		 * Filters attributes to be added to a script tag.
		 *
		 * @since 5.7.0
		 *
		 * @param array<string, string|bool> $attributes Key-value pairs representing `<script>` tag attributes.
		 *                                               Only the attribute name is added to the `<script>` tag for
		 *                                               entries with a boolean value, and that are true.
		 * @param string                     $data       Inline data.
		 */
		$attributes = apply_filters( 'wp_inline_script_attributes', $attributes, $data );
	
		$processor = new WP_HTML_Tag_Processor( '<script></script>' );
		$processor->next_tag();
		foreach ( $attributes as $name => $value ) {
			/*
			 * Lexical variations of an attribute name may represent the
			 * same attribute in HTML, therefore it’s possible that the
			 * input array might contain duplicate attributes even though
			 * it’s keyed on their name. Calling code should rewrite an
			 * attribute’s value rather than sending a duplicate attribute.
			 *
			 * Example:
			 *
			 *     array( 'id' => 'main', 'ID' => 'nav' )
			 *
			 * In this example, there are two keys both describing the `id`
			 * attribute. PHP array iteration is in key-insertion order so
			 * the 'id' value will be set in the SCRIPT tag.
			 */
			if ( null !== $processor->get_attribute( $name ) ) {
				continue;
			}
	
			$processor->set_attribute( $name, $value ?? true );
		}
	
		if ( ! $processor->set_modifiable_text( $data ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				__( 'Unable to set inline script data.' ),
				'7.0.0'
			);
			return '';
		}
	
		return "{$processor->get_updated_html()}\n";
	}
endif;

// wp-includes/script-loader.php (WP 7.0.2)
if( ! function_exists( 'wp_print_inline_script_tag' ) ) :
	function wp_print_inline_script_tag( $data, $attributes = array() ) {
		echo wp_get_inline_script_tag( $data, $attributes );
	}
endif;

// wp-includes/script-loader.php (WP 7.0.2)
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

// wp-includes/script-loader.php (WP 7.0.2)
if( ! function_exists( '_print_scripts' ) ) :
	function _print_scripts() {
		global $wp_scripts, $compress_scripts;
	
		$zip = $compress_scripts ? 1 : 0;
		if ( $zip && defined( 'ENFORCE_GZIP' ) && ENFORCE_GZIP ) {
			$zip = 'gzip';
		}
	
		$concat = trim( $wp_scripts->concat, ', ' );
	
		if ( $concat ) {
			if ( ! empty( $wp_scripts->print_code ) ) {
				echo "\n<script>\n";
				echo $wp_scripts->print_code;
				echo sprintf( "\n//# sourceURL=%s\n", rawurlencode( 'js-inline-concat-' . $concat ) );
				echo "</script>\n";
			}
	
			$concat       = str_split( $concat, 128 );
			$concatenated = '';
	
			foreach ( $concat as $key => $chunk ) {
				$concatenated .= "&load%5Bchunk_{$key}%5D={$chunk}";
			}
	
			$src = $wp_scripts->base_url . "/wp-admin/load-scripts.php?c={$zip}" . $concatenated . '&ver=' . $wp_scripts->default_version;
			echo "<script src='" . esc_attr( $src ) . "'></script>\n";
		}
	
		if ( ! empty( $wp_scripts->print_html ) ) {
			echo $wp_scripts->print_html;
		}
	}
endif;

