<?php

// ------------------auto-generated---------------------

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( '_wp_scripts_add_args_data' ) ) :
	function _wp_scripts_add_args_data( WP_Scripts $wp_scripts, string $handle, array $args ): void {
		$allowed_keys = array( 'strategy', 'in_footer', 'fetchpriority', 'module_dependencies' );
		$unknown_keys = array_diff( array_keys( $args ), $allowed_keys );
		if ( ! empty( $unknown_keys ) ) {
			$trace         = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
			$function_name = ( $trace[1]['class'] ?? '' ) . ( $trace[1]['type'] ?? '' ) . ( $trace[1]['function'] ?? __FUNCTION__ );
			_doing_it_wrong(
				$function_name,
				sprintf(
					/* translators: 1: $args, 2: List of unrecognized keys, 3: List of supported keys. */
					__( 'Unrecognized key(s) in the %1$s param: %2$s. Supported keys: %3$s' ),
					'$args',
					implode( wp_get_list_item_separator(), $unknown_keys ),
					implode( wp_get_list_item_separator(), $allowed_keys )
				),
				'7.0.0'
			);
		}
	
		$in_footer = ! empty( $args['in_footer'] );
		if ( $in_footer ) {
			$wp_scripts->add_data( $handle, 'group', 1 );
		}
		if ( ! empty( $args['strategy'] ) ) {
			$wp_scripts->add_data( $handle, 'strategy', $args['strategy'] );
		}
		if ( ! empty( $args['fetchpriority'] ) ) {
			$wp_scripts->add_data( $handle, 'fetchpriority', $args['fetchpriority'] );
		}
		if ( ! empty( $args['module_dependencies'] ) ) {
			$wp_scripts->add_data( $handle, 'module_dependencies', $args['module_dependencies'] );
	
			/*
			 * A classic script with module dependencies must either be printed in the
			 * footer or use the 'defer' loading strategy. Otherwise, the script may be
			 * evaluated before the script modules import map is printed, causing
			 * dynamic imports to fail with a "Failed to resolve module specifier" error.
			 */
			$is_deferred = 'defer' === ( $args['strategy'] ?? null );
			if ( ! $in_footer && ! $is_deferred ) {
				$trace         = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
				$function_name = ( $trace[1]['class'] ?? '' ) . ( $trace[1]['type'] ?? '' ) . ( $trace[1]['function'] ?? __FUNCTION__ );
				_doing_it_wrong(
					$function_name,
					sprintf(
						/* translators: 1: 'module_dependencies', 2: Script handle, 3: 'in_footer', 4: 'strategy', 5: 'defer'. */
						__( 'When the %1$s arg is provided, the "%2$s" script must either be printed in the footer (%3$s set to true) or use a deferred loading %4$s (%5$s) so that the import map is printed before the script is evaluated.' ),
						'<code>module_dependencies</code>',
						$handle,
						'<code>in_footer</code>',
						'<code>strategy</code>',
						'<code>defer</code>'
					),
					'7.0.0'
				);
			}
		}
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( '_wp_scripts_maybe_doing_it_wrong' ) ) :
	function _wp_scripts_maybe_doing_it_wrong( $function_name, $handle = '' ) {
		if ( did_action( 'init' ) || did_action( 'wp_enqueue_scripts' )
			|| did_action( 'admin_enqueue_scripts' ) || did_action( 'login_enqueue_scripts' )
		) {
			return;
		}
	
		$message = sprintf(
			/* translators: 1: wp_enqueue_scripts, 2: admin_enqueue_scripts, 3: login_enqueue_scripts */
			__( 'Scripts and styles should not be registered or enqueued until the %1$s, %2$s, or %3$s hooks.' ),
			'<code>wp_enqueue_scripts</code>',
			'<code>admin_enqueue_scripts</code>',
			'<code>login_enqueue_scripts</code>'
		);
	
		if ( $handle ) {
			$message .= ' ' . sprintf(
				/* translators: %s: Name of the script or stylesheet. */
				__( 'This notice was triggered by the %s handle.' ),
				'<code>' . $handle . '</code>'
			);
		}
	
		_doing_it_wrong(
			$function_name,
			$message,
			'3.3.0'
		);
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( 'wp_print_scripts' ) ) :
	function wp_print_scripts( $handles = false ) {
		global $wp_scripts;
	
		/**
		 * Fires before scripts in the $handles queue are printed.
		 *
		 * @since 2.1.0
		 */
		do_action( 'wp_print_scripts' );
	
		if ( '' === $handles ) { // For 'wp_head'.
			$handles = false;
		}
	
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__ );
	
		if ( ! ( $wp_scripts instanceof WP_Scripts ) ) {
			if ( ! $handles ) {
				return array(); // No need to instantiate if nothing is there.
			}
		}
	
		return wp_scripts()->do_items( $handles );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( 'wp_add_inline_script' ) ) :
	function wp_add_inline_script( $handle, $data, $position = 'after' ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		if ( false !== stripos( $data, '</script>' ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				sprintf(
					/* translators: 1: <script>, 2: wp_add_inline_script() */
					__( 'Do not pass %1$s tags to %2$s.' ),
					'<code>&lt;script&gt;</code>',
					'<code>wp_add_inline_script()</code>'
				),
				'4.5.0'
			);
			$data = trim( (string) preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', $data ) );
		}
	
		return wp_scripts()->add_inline_script( $handle, $data, $position );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( 'wp_register_script' ) ) :
	function wp_register_script( $handle, $src, $deps = array(), $ver = false, $args = array() ) {
		if ( ! is_array( $args ) ) {
			$args = array(
				'in_footer' => (bool) $args,
			);
		}
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		$wp_scripts = wp_scripts();
	
		$registered = $wp_scripts->add( $handle, $src, $deps, $ver );
		_wp_scripts_add_args_data( $wp_scripts, $handle, $args );
	
		return $registered;
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( 'wp_localize_script' ) ) :
	function wp_localize_script( $handle, $object_name, $l10n ) {
		$wp_scripts = wp_scripts();
	
		return $wp_scripts->localize( $handle, $object_name, $l10n );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( 'wp_set_script_translations' ) ) :
	function wp_set_script_translations( $handle, $domain = 'default', $path = '' ) {
		global $wp_scripts;
	
		if ( ! ( $wp_scripts instanceof WP_Scripts ) ) {
			_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
			return false;
		}
	
		return $wp_scripts->set_translations( $handle, $domain, $path );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( 'wp_deregister_script' ) ) :
	function wp_deregister_script( $handle ) {
		global $pagenow;
	
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		/**
		 * Do not allow accidental or negligent de-registering of critical scripts in the admin.
		 * Show minimal remorse if the correct hook is used.
		 */
		$current_filter = current_filter();
		if ( ( is_admin() && 'admin_enqueue_scripts' !== $current_filter ) ||
			( 'wp-login.php' === $pagenow && 'login_enqueue_scripts' !== $current_filter )
		) {
			$not_allowed = array(
				'jquery',
				'jquery-core',
				'jquery-migrate',
				'jquery-ui-core',
				'jquery-ui-accordion',
				'jquery-ui-autocomplete',
				'jquery-ui-button',
				'jquery-ui-datepicker',
				'jquery-ui-dialog',
				'jquery-ui-draggable',
				'jquery-ui-droppable',
				'jquery-ui-menu',
				'jquery-ui-mouse',
				'jquery-ui-position',
				'jquery-ui-progressbar',
				'jquery-ui-resizable',
				'jquery-ui-selectable',
				'jquery-ui-slider',
				'jquery-ui-sortable',
				'jquery-ui-spinner',
				'jquery-ui-tabs',
				'jquery-ui-tooltip',
				'jquery-ui-widget',
				'underscore',
				'backbone',
			);
	
			if ( in_array( $handle, $not_allowed, true ) ) {
				_doing_it_wrong(
					__FUNCTION__,
					sprintf(
						/* translators: 1: Script name, 2: wp_enqueue_scripts */
						__( 'Do not deregister the %1$s script in the administration area. To target the front-end theme, use the %2$s hook.' ),
						"<code>$handle</code>",
						'<code>wp_enqueue_scripts</code>'
					),
					'3.6.0'
				);
				return;
			}
		}
	
		wp_scripts()->remove( $handle );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( 'wp_enqueue_script' ) ) :
	function wp_enqueue_script( $handle, $src = '', $deps = array(), $ver = false, $args = array() ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		$wp_scripts = wp_scripts();
	
		if ( $src || ! empty( $args ) ) {
			/** @var array{ 0: non-empty-string, 1?: string } $_handle */
			$_handle = explode( '?', $handle );
			if ( ! is_array( $args ) ) {
				$args = array(
					'in_footer' => (bool) $args,
				);
			}
	
			if ( $src ) {
				$wp_scripts->add( $_handle[0], $src, $deps, $ver );
			}
			if ( ! empty( $args ) ) {
				_wp_scripts_add_args_data( $wp_scripts, $_handle[0], $args );
			}
		}
	
		$wp_scripts->enqueue( $handle );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( 'wp_dequeue_script' ) ) :
	function wp_dequeue_script( $handle ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		wp_scripts()->dequeue( $handle );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( 'wp_script_is' ) ) :
	function wp_script_is( $handle, $status = 'enqueued' ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		return (bool) wp_scripts()->query( $handle, $status );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 7.0)
if( ! function_exists( 'wp_script_add_data' ) ) :
	function wp_script_add_data( $handle, $key, $value ) {
		return wp_scripts()->add_data( $handle, $key, $value );
	}
endif;

