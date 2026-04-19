<?php

// ------------------auto-generated---------------------

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
if( ! function_exists( 'wp_scripts' ) ) :
	function wp_scripts() {
		global $wp_scripts;
	
		if ( ! ( $wp_scripts instanceof WP_Scripts ) ) {
			$wp_scripts = new WP_Scripts();
		}
	
		return $wp_scripts;
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
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

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
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

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
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
			$data = trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', $data ) );
		}
	
		return wp_scripts()->add_inline_script( $handle, $data, $position );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
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
		if ( ! empty( $args['in_footer'] ) ) {
			$wp_scripts->add_data( $handle, 'group', 1 );
		}
		if ( ! empty( $args['strategy'] ) ) {
			$wp_scripts->add_data( $handle, 'strategy', $args['strategy'] );
		}
		return $registered;
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
if( ! function_exists( 'wp_localize_script' ) ) :
	function wp_localize_script( $handle, $object_name, $l10n ) {
		$wp_scripts = wp_scripts();
	
		return $wp_scripts->localize( $handle, $object_name, $l10n );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
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

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
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

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
if( ! function_exists( 'wp_enqueue_script' ) ) :
	function wp_enqueue_script( $handle, $src = '', $deps = array(), $ver = false, $args = array() ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		$wp_scripts = wp_scripts();
	
		if ( $src || ! empty( $args ) ) {
			$_handle = explode( '?', $handle );
			if ( ! is_array( $args ) ) {
				$args = array(
					'in_footer' => (bool) $args,
				);
			}
	
			if ( $src ) {
				$wp_scripts->add( $_handle[0], $src, $deps, $ver );
			}
			if ( ! empty( $args['in_footer'] ) ) {
				$wp_scripts->add_data( $_handle[0], 'group', 1 );
			}
			if ( ! empty( $args['strategy'] ) ) {
				$wp_scripts->add_data( $_handle[0], 'strategy', $args['strategy'] );
			}
		}
	
		$wp_scripts->enqueue( $handle );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
if( ! function_exists( 'wp_dequeue_script' ) ) :
	function wp_dequeue_script( $handle ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		wp_scripts()->dequeue( $handle );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
if( ! function_exists( 'wp_script_is' ) ) :
	function wp_script_is( $handle, $status = 'enqueued' ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		return (bool) wp_scripts()->query( $handle, $status );
	}
endif;

// wp-includes/functions.wp-scripts.php (WP 6.5.8)
if( ! function_exists( 'wp_script_add_data' ) ) :
	function wp_script_add_data( $handle, $key, $value ) {
		return wp_scripts()->add_data( $handle, $key, $value );
	}
endif;

