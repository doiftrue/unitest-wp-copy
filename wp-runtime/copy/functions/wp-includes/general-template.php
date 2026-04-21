<?php

// ------------------auto-generated---------------------

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_login_url' ) ) :
	function wp_login_url( $redirect = '', $force_reauth = false ) {
		$login_url = site_url( 'wp-login.php', 'login' );
	
		if ( ! empty( $redirect ) ) {
			$login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );
		}
	
		if ( $force_reauth ) {
			$login_url = add_query_arg( 'reauth', '1', $login_url );
		}
	
		/**
		 * Filters the login URL.
		 *
		 * @since 2.8.0
		 * @since 4.2.0 The `$force_reauth` parameter was added.
		 *
		 * @param string $login_url    The login URL. Not HTML-encoded.
		 * @param string $redirect     The path to redirect to on login, if supplied.
		 * @param bool   $force_reauth Whether to force reauthorization, even if a cookie is present.
		 */
		return apply_filters( 'login_url', $login_url, $redirect, $force_reauth );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_registration_url' ) ) :
	function wp_registration_url() {
		/**
		 * Filters the user registration URL.
		 *
		 * @since 3.6.0
		 *
		 * @param string $register The user registration URL.
		 */
		return apply_filters( 'register_url', site_url( 'wp-login.php?action=register', 'login' ) );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_login_form' ) ) :
	function wp_login_form( $args = array() ) {
		$defaults = array(
			'echo'              => true,
			// Default 'redirect' value takes the user back to the request URI.
			'redirect'          => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
			'form_id'           => 'loginform',
			'label_username'    => __( 'Username or Email Address' ),
			'label_password'    => __( 'Password' ),
			'label_remember'    => __( 'Remember Me' ),
			'label_log_in'      => __( 'Log In' ),
			'id_username'       => 'user_login',
			'id_password'       => 'user_pass',
			'id_remember'       => 'rememberme',
			'id_submit'         => 'wp-submit',
			'remember'          => true,
			'value_username'    => '',
			// Set 'value_remember' to true to default the "Remember me" checkbox to checked.
			'value_remember'    => false,
			// Set 'required_username' to true to add the required attribute to username field.
			'required_username' => false,
			// Set 'required_password' to true to add the required attribute to password field.
			'required_password' => false,
		);
	
		/**
		 * Filters the default login form output arguments.
		 *
		 * @since 3.0.0
		 *
		 * @see wp_login_form()
		 *
		 * @param array $defaults An array of default login form arguments.
		 */
		$args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );
	
		/**
		 * Filters content to display at the top of the login form.
		 *
		 * The filter evaluates just following the opening form tag element.
		 *
		 * @since 3.0.0
		 *
		 * @param string $content Content to display. Default empty.
		 * @param array  $args    Array of login form arguments.
		 */
		$login_form_top = apply_filters( 'login_form_top', '', $args );
	
		/**
		 * Filters content to display in the middle of the login form.
		 *
		 * The filter evaluates just following the location where the 'login-password'
		 * field is displayed.
		 *
		 * @since 3.0.0
		 *
		 * @param string $content Content to display. Default empty.
		 * @param array  $args    Array of login form arguments.
		 */
		$login_form_middle = apply_filters( 'login_form_middle', '', $args );
	
		/**
		 * Filters content to display at the bottom of the login form.
		 *
		 * The filter evaluates just preceding the closing form tag element.
		 *
		 * @since 3.0.0
		 *
		 * @param string $content Content to display. Default empty.
		 * @param array  $args    Array of login form arguments.
		 */
		$login_form_bottom = apply_filters( 'login_form_bottom', '', $args );
	
		$form =
			sprintf(
				'<form name="%1$s" id="%1$s" action="%2$s" method="post">',
				esc_attr( $args['form_id'] ),
				esc_url( site_url( 'wp-login.php', 'login_post' ) )
			) .
			$login_form_top .
			sprintf(
				'<p class="login-username">
					<label for="%1$s">%2$s</label>
					<input type="text" name="log" id="%1$s" autocomplete="username" class="input" value="%3$s" size="20"%4$s />
				</p>',
				esc_attr( $args['id_username'] ),
				esc_html( $args['label_username'] ),
				esc_attr( $args['value_username'] ),
				( $args['required_username'] ? ' required="required"' : '' )
			) .
			sprintf(
				'<p class="login-password">
					<label for="%1$s">%2$s</label>
					<input type="password" name="pwd" id="%1$s" autocomplete="current-password" spellcheck="false" class="input" value="" size="20"%3$s />
				</p>',
				esc_attr( $args['id_password'] ),
				esc_html( $args['label_password'] ),
				( $args['required_password'] ? ' required="required"' : '' )
			) .
			$login_form_middle .
			( $args['remember'] ?
				sprintf(
					'<p class="login-remember"><label><input name="rememberme" type="checkbox" id="%1$s" value="forever"%2$s /> %3$s</label></p>',
					esc_attr( $args['id_remember'] ),
					( $args['value_remember'] ? ' checked="checked"' : '' ),
					esc_html( $args['label_remember'] )
				) : ''
			) .
			sprintf(
				'<p class="login-submit">
					<input type="submit" name="wp-submit" id="%1$s" class="button button-primary" value="%2$s" />
					<input type="hidden" name="redirect_to" value="%3$s" />
				</p>',
				esc_attr( $args['id_submit'] ),
				esc_attr( $args['label_log_in'] ),
				esc_url( $args['redirect'] )
			) .
			$login_form_bottom .
			'</form>';
	
		if ( $args['echo'] ) {
			echo $form;
		} else {
			return $form;
		}
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_meta' ) ) :
	function wp_meta() {
		/**
		 * Fires before displaying echoed content in the sidebar.
		 *
		 * @since 1.5.0
		 */
		do_action( 'wp_meta' );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'bloginfo' ) ) :
	function bloginfo( $show = '' ) {
		echo get_bloginfo( $show, 'display' );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'get_archives_link' ) ) :
	function get_archives_link( $url, $text, $format = 'html', $before = '', $after = '', $selected = false ) {
		$text         = wptexturize( $text );
		$url          = esc_url( $url );
		$aria_current = $selected ? ' aria-current="page"' : '';
	
		if ( 'link' === $format ) {
			$link_html = "\t<link rel='archives' title='" . esc_attr( $text ) . "' href='$url' />\n";
		} elseif ( 'option' === $format ) {
			$selected_attr = $selected ? " selected='selected'" : '';
			$link_html     = "\t<option value='$url'$selected_attr>$before $text $after</option>\n";
		} elseif ( 'html' === $format ) {
			$link_html = "\t<li>$before<a href='$url'$aria_current>$text</a>$after</li>\n";
		} else { // Custom.
			$link_html = "\t$before<a href='$url'$aria_current>$text</a>$after\n";
		}
	
		/**
		 * Filters the archive link content.
		 *
		 * @since 2.6.0
		 * @since 4.5.0 Added the `$url`, `$text`, `$format`, `$before`, and `$after` parameters.
		 * @since 5.2.0 Added the `$selected` parameter.
		 *
		 * @param string $link_html The archive HTML link content.
		 * @param string $url       URL to archive.
		 * @param string $text      Archive text description.
		 * @param string $format    Link format. Can be 'link', 'option', 'html', or custom.
		 * @param string $before    Content to prepend to the description.
		 * @param string $after     Content to append to the description.
		 * @param bool   $selected  True if the current page is the selected archive.
		 */
		return apply_filters( 'get_archives_link', $link_html, $url, $text, $format, $before, $after, $selected );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'calendar_week_mod' ) ) :
	function calendar_week_mod( $num ) {
		$base = 7;
		return ( $num - $base * floor( $num / $base ) );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'allowed_tags' ) ) :
	function allowed_tags() {
		global $allowedtags;
		$allowed = '';
		foreach ( (array) $allowedtags as $tag => $attributes ) {
			$allowed .= '<' . $tag;
			if ( 0 < count( $attributes ) ) {
				foreach ( $attributes as $attribute => $limits ) {
					$allowed .= ' ' . $attribute . '=""';
				}
			}
			$allowed .= '> ';
		}
		return htmlentities( $allowed );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_head' ) ) :
	function wp_head() {
		/**
		 * Prints scripts or data in the head tag on the front end.
		 *
		 * @since 1.5.0
		 */
		do_action( 'wp_head' );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_footer' ) ) :
	function wp_footer() {
		/**
		 * Prints scripts or data before the closing body tag on the front end.
		 *
		 * @since 1.5.1
		 */
		do_action( 'wp_footer' );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_body_open' ) ) :
	function wp_body_open() {
		/**
		 * Triggered after the opening body tag.
		 *
		 * @since 5.2.0
		 */
		do_action( 'wp_body_open' );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'rsd_link' ) ) :
	function rsd_link() {
		printf(
			'<link rel="EditURI" type="application/rsd+xml" title="RSD" href="%s" />' . "\n",
			esc_url( site_url( 'xmlrpc.php?rsd', 'rpc' ) )
		);
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_strict_cross_origin_referrer' ) ) :
	function wp_strict_cross_origin_referrer() {
		?>
		<meta name='referrer' content='strict-origin-when-cross-origin' />
		<?php
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_resource_hints' ) ) :
	function wp_resource_hints() {
		$hints = array(
			'dns-prefetch' => wp_dependencies_unique_hosts(),
			'preconnect'   => array(),
			'prefetch'     => array(),
			'prerender'    => array(),
		);
	
		foreach ( $hints as $relation_type => $urls ) {
			$unique_urls = array();
	
			/**
			 * Filters domains and URLs for resource hints of the given relation type.
			 *
			 * @since 4.6.0
			 * @since 4.7.0 The `$urls` parameter accepts arrays of specific HTML attributes
			 *              as its child elements.
			 *
			 * @param array  $urls {
			 *     Array of resources and their attributes, or URLs to print for resource hints.
			 *
			 *     @type array|string ...$0 {
			 *         Array of resource attributes, or a URL string.
			 *
			 *         @type string $href        URL to include in resource hints. Required.
			 *         @type string $as          How the browser should treat the resource
			 *                                   (`script`, `style`, `image`, `document`, etc).
			 *         @type string $crossorigin Indicates the CORS policy of the specified resource.
			 *         @type float  $pr          Expected probability that the resource hint will be used.
			 *         @type string $type        Type of the resource (`text/html`, `text/css`, etc).
			 *     }
			 * }
			 * @param string $relation_type The relation type the URLs are printed for. One of
			 *                              'dns-prefetch', 'preconnect', 'prefetch', or 'prerender'.
			 */
			$urls = apply_filters( 'wp_resource_hints', $urls, $relation_type );
	
			foreach ( $urls as $key => $url ) {
				$atts = array();
	
				if ( is_array( $url ) ) {
					if ( isset( $url['href'] ) ) {
						$atts = $url;
						$url  = $url['href'];
					} else {
						continue;
					}
				}
	
				$url = esc_url( $url, array( 'http', 'https' ) );
	
				if ( ! $url ) {
					continue;
				}
	
				if ( isset( $unique_urls[ $url ] ) ) {
					continue;
				}
	
				if ( in_array( $relation_type, array( 'preconnect', 'dns-prefetch' ), true ) ) {
					$parsed = wp_parse_url( $url );
	
					if ( empty( $parsed['host'] ) ) {
						continue;
					}
	
					if ( 'preconnect' === $relation_type && ! empty( $parsed['scheme'] ) ) {
						$url = $parsed['scheme'] . '://' . $parsed['host'];
					} else {
						// Use protocol-relative URLs for dns-prefetch or if scheme is missing.
						$url = '//' . $parsed['host'];
					}
				}
	
				$atts['rel']  = $relation_type;
				$atts['href'] = $url;
	
				$unique_urls[ $url ] = $atts;
			}
	
			foreach ( $unique_urls as $atts ) {
				$html = '';
	
				foreach ( $atts as $attr => $value ) {
					if ( ! is_scalar( $value )
						|| ( ! in_array( $attr, array( 'as', 'crossorigin', 'href', 'pr', 'rel', 'type' ), true ) && ! is_numeric( $attr ) )
					) {
	
						continue;
					}
	
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
	
					if ( ! is_string( $attr ) ) {
						$html .= " $value";
					} else {
						$html .= " $attr='$value'";
					}
				}
	
				$html = trim( $html );
	
				echo "<link $html />\n";
			}
		}
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_preload_resources' ) ) :
	function wp_preload_resources() {
		/**
		 * Filters domains and URLs for resource preloads.
		 *
		 * @since 6.1.0
		 * @since 6.6.0 Added the `$fetchpriority` attribute.
		 *
		 * @param array  $preload_resources {
		 *     Array of resources and their attributes, or URLs to print for resource preloads.
		 *
		 *     @type array ...$0 {
		 *         Array of resource attributes.
		 *
		 *         @type string $href          URL to include in resource preloads. Required.
		 *         @type string $as            How the browser should treat the resource
		 *                                     (`script`, `style`, `image`, `document`, etc).
		 *         @type string $crossorigin   Indicates the CORS policy of the specified resource.
		 *         @type string $type          Type of the resource (`text/html`, `text/css`, etc).
		 *         @type string $media         Accepts media types or media queries. Allows responsive preloading.
		 *         @type string $imagesizes    Responsive source size to the source Set.
		 *         @type string $imagesrcset   Responsive image sources to the source set.
		 *         @type string $fetchpriority Fetchpriority value for the resource.
		 *     }
		 * }
		 */
		$preload_resources = apply_filters( 'wp_preload_resources', array() );
	
		if ( ! is_array( $preload_resources ) ) {
			return;
		}
	
		$unique_resources = array();
	
		// Parse the complete resource list and extract unique resources.
		foreach ( $preload_resources as $resource ) {
			if ( ! is_array( $resource ) ) {
				continue;
			}
	
			$attributes = $resource;
			if ( isset( $resource['href'] ) ) {
				$href = $resource['href'];
				if ( isset( $unique_resources[ $href ] ) ) {
					continue;
				}
				$unique_resources[ $href ] = $attributes;
				// Media can use imagesrcset and not href.
			} elseif ( ( 'image' === $resource['as'] ) &&
				( isset( $resource['imagesrcset'] ) || isset( $resource['imagesizes'] ) )
			) {
				if ( isset( $unique_resources[ $resource['imagesrcset'] ] ) ) {
					continue;
				}
				$unique_resources[ $resource['imagesrcset'] ] = $attributes;
			} else {
				continue;
			}
		}
	
		// Build and output the HTML for each unique resource.
		foreach ( $unique_resources as $unique_resource ) {
			$html = '';
	
			foreach ( $unique_resource as $resource_key => $resource_value ) {
				if ( ! is_scalar( $resource_value ) ) {
					continue;
				}
	
				// Ignore non-supported attributes.
				$non_supported_attributes = array( 'as', 'crossorigin', 'href', 'imagesrcset', 'imagesizes', 'type', 'media', 'fetchpriority' );
				if ( ! in_array( $resource_key, $non_supported_attributes, true ) && ! is_numeric( $resource_key ) ) {
					continue;
				}
	
				// imagesrcset only usable when preloading image, ignore otherwise.
				if ( ( 'imagesrcset' === $resource_key ) && ( ! isset( $unique_resource['as'] ) || ( 'image' !== $unique_resource['as'] ) ) ) {
					continue;
				}
	
				// imagesizes only usable when preloading image and imagesrcset present, ignore otherwise.
				if ( ( 'imagesizes' === $resource_key ) &&
					( ! isset( $unique_resource['as'] ) || ( 'image' !== $unique_resource['as'] ) || ! isset( $unique_resource['imagesrcset'] ) )
				) {
					continue;
				}
	
				$resource_value = ( 'href' === $resource_key ) ? esc_url( $resource_value, array( 'http', 'https' ) ) : esc_attr( $resource_value );
	
				if ( ! is_string( $resource_key ) ) {
					$html .= " $resource_value";
				} else {
					$html .= " $resource_key='$resource_value'";
				}
			}
			$html = trim( $html );
	
			printf( "<link rel='preload' %s />\n", $html );
		}
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_dependencies_unique_hosts' ) ) :
	function wp_dependencies_unique_hosts() {
		global $wp_scripts, $wp_styles;
	
		$unique_hosts = array();
	
		foreach ( array( $wp_scripts, $wp_styles ) as $dependencies ) {
			if ( $dependencies instanceof WP_Dependencies && ! empty( $dependencies->queue ) ) {
				foreach ( $dependencies->queue as $handle ) {
					if ( ! isset( $dependencies->registered[ $handle ] ) ) {
						continue;
					}
	
					/* @var _WP_Dependency $dependency */
					$dependency = $dependencies->registered[ $handle ];
					$parsed     = wp_parse_url( $dependency->src );
	
					if ( ! empty( $parsed['host'] )
						&& ! in_array( $parsed['host'], $unique_hosts, true ) && $parsed['host'] !== $_SERVER['SERVER_NAME']
					) {
						$unique_hosts[] = $parsed['host'];
					}
				}
			}
		}
	
		return $unique_hosts;
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'get_language_attributes' ) ) :
	function get_language_attributes( $doctype = 'html' ) {
		$attributes = array();
	
		if ( function_exists( 'is_rtl' ) && is_rtl() ) {
			$attributes[] = 'dir="rtl"';
		}
	
		$lang = get_bloginfo( 'language' );
		if ( $lang ) {
			if ( 'text/html' === $GLOBALS['stub_wp_options']->html_type || 'html' === $doctype ) {
				$attributes[] = 'lang="' . esc_attr( $lang ) . '"';
			}
	
			if ( 'text/html' !== $GLOBALS['stub_wp_options']->html_type || 'xhtml' === $doctype ) {
				$attributes[] = 'xml:lang="' . esc_attr( $lang ) . '"';
			}
		}
	
		$output = implode( ' ', $attributes );
	
		/**
		 * Filters the language attributes for display in the 'html' tag.
		 *
		 * @since 2.5.0
		 * @since 4.3.0 Added the `$doctype` parameter.
		 *
		 * @param string $output A space-separated list of language attributes.
		 * @param string $doctype The type of HTML document (xhtml|html).
		 */
		return apply_filters( 'language_attributes', $output, $doctype );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'language_attributes' ) ) :
	function language_attributes( $doctype = 'html' ) {
		echo get_language_attributes( $doctype );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_admin_css_color' ) ) :
	function wp_admin_css_color( $key, $name, $url, $colors = array(), $icons = array() ) {
		global $_wp_admin_css_colors;
	
		if ( ! isset( $_wp_admin_css_colors ) ) {
			$_wp_admin_css_colors = array();
		}
	
		$_wp_admin_css_colors[ $key ] = (object) array(
			'name'        => $name,
			'url'         => $url,
			'colors'      => $colors,
			'icon_colors' => $icons,
		);
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'checked' ) ) :
	function checked( $checked, $current = true, $display = true ) {
		return __checked_selected_helper( $checked, $current, $display, 'checked' );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'selected' ) ) :
	function selected( $selected, $current = true, $display = true ) {
		return __checked_selected_helper( $selected, $current, $display, 'selected' );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'disabled' ) ) :
	function disabled( $disabled, $current = true, $display = true ) {
		return __checked_selected_helper( $disabled, $current, $display, 'disabled' );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_readonly' ) ) :
	function wp_readonly( $readonly_value, $current = true, $display = true ) {
		return __checked_selected_helper( $readonly_value, $current, $display, 'readonly' );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( '__checked_selected_helper' ) ) :
	function __checked_selected_helper( $helper, $current, $display, $type ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionDoubleUnderscore,PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames.FunctionDoubleUnderscore
		if ( (string) $helper === (string) $current ) {
			$result = " $type='$type'";
		} else {
			$result = '';
		}
	
		if ( $display ) {
			echo $result;
		}
	
		return $result;
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_required_field_indicator' ) ) :
	function wp_required_field_indicator() {
		/* translators: Character to identify required form fields. */
		$glyph     = __( '*' );
		$indicator = '<span class="required">' . esc_html( $glyph ) . '</span>';
	
		/**
		 * Filters the markup for a visual indicator of required form fields.
		 *
		 * @since 6.1.0
		 *
		 * @param string $indicator Markup for the indicator element.
		 */
		return apply_filters( 'wp_required_field_indicator', $indicator );
	}
endif;

// wp-includes/general-template.php (WP 6.9.4)
if( ! function_exists( 'wp_required_field_message' ) ) :
	function wp_required_field_message() {
		$message = sprintf(
			'<span class="required-field-message">%s</span>',
			/* translators: %s: Asterisk symbol (*). */
			sprintf( __( 'Required fields are marked %s' ), wp_required_field_indicator() )
		);
	
		/**
		 * Filters the message to explain required form fields.
		 *
		 * @since 6.1.0
		 *
		 * @param string $message Message text and glyph wrapped in a `span` tag.
		 */
		return apply_filters( 'wp_required_field_message', $message );
	}
endif;

