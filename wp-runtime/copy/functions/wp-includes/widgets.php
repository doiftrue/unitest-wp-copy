<?php

// ------------------auto-generated---------------------

// wp-includes/widgets.php (WP 6.9.5)
if( ! function_exists( 'wp_parse_widget_id' ) ) :
	function wp_parse_widget_id( $id ) {
		$parsed = array();
	
		if ( preg_match( '/^(.+)-(\d+)$/', $id, $matches ) ) {
			$parsed['id_base'] = $matches[1];
			$parsed['number']  = (int) $matches[2];
		} else {
			// Likely an old single widget.
			$parsed['id_base'] = $id;
		}
	
		return $parsed;
	}
endif;

// wp-includes/widgets.php (WP 6.9.5)
if( ! function_exists( '_get_widget_id_base' ) ) :
	function _get_widget_id_base( $id ) {
		return preg_replace( '/-[0-9]+$/', '', $id );
	}
endif;

// wp-includes/widgets.php (WP 6.9.5)
if( ! function_exists( 'register_sidebars' ) ) :
	function register_sidebars( $number = 1, $args = array() ) {
		global $wp_registered_sidebars;
		$number = (int) $number;
	
		if ( is_string( $args ) ) {
			parse_str( $args, $args );
		}
	
		for ( $i = 1; $i <= $number; $i++ ) {
			$_args = $args;
	
			if ( $number > 1 ) {
				if ( isset( $args['name'] ) ) {
					$_args['name'] = sprintf( $args['name'], $i );
				} else {
					/* translators: %d: Sidebar number. */
					$_args['name'] = sprintf( __( 'Sidebar %d' ), $i );
				}
			} else {
				$_args['name'] = isset( $args['name'] ) ? $args['name'] : __( 'Sidebar' );
			}
	
			/*
			 * Custom specified ID's are suffixed if they exist already.
			 * Automatically generated sidebar names need to be suffixed regardless starting at -0.
			 */
			if ( isset( $args['id'] ) ) {
				$_args['id'] = $args['id'];
				$n           = 2; // Start at -2 for conflicting custom IDs.
				while ( is_registered_sidebar( $_args['id'] ) ) {
					$_args['id'] = $args['id'] . '-' . $n++;
				}
			} else {
				$n = count( $wp_registered_sidebars );
				do {
					$_args['id'] = 'sidebar-' . ++$n;
				} while ( is_registered_sidebar( $_args['id'] ) );
			}
			register_sidebar( $_args );
		}
	}
endif;

// wp-includes/widgets.php (WP 6.9.5)
if( ! function_exists( 'register_sidebar' ) ) :
	function register_sidebar( $args = array() ) {
		global $wp_registered_sidebars;
	
		$i = count( $wp_registered_sidebars ) + 1;
	
		$id_is_empty = empty( $args['id'] );
	
		$defaults = array(
			/* translators: %d: Sidebar number. */
			'name'           => sprintf( __( 'Sidebar %d' ), $i ),
			'id'             => "sidebar-$i",
			'description'    => '',
			'class'          => '',
			'before_widget'  => '<li id="%1$s" class="widget %2$s">',
			'after_widget'   => "</li>\n",
			'before_title'   => '<h2 class="widgettitle">',
			'after_title'    => "</h2>\n",
			'before_sidebar' => '',
			'after_sidebar'  => '',
			'show_in_rest'   => false,
		);
	
		/**
		 * Filters the sidebar default arguments.
		 *
		 * @since 5.3.0
		 *
		 * @see register_sidebar()
		 *
		 * @param array $defaults The default sidebar arguments.
		 */
		$sidebar = wp_parse_args( $args, apply_filters( 'register_sidebar_defaults', $defaults ) );
	
		if ( $id_is_empty ) {
			_doing_it_wrong(
				__FUNCTION__,
				sprintf(
					/* translators: 1: The 'id' argument, 2: Sidebar name, 3: Recommended 'id' value. */
					__( 'No %1$s was set in the arguments array for the "%2$s" sidebar. Defaulting to "%3$s". Manually set the %1$s to "%3$s" to silence this notice and keep existing sidebar content.' ),
					'<code>id</code>',
					$sidebar['name'],
					$sidebar['id']
				),
				'4.2.0'
			);
		}
	
		$wp_registered_sidebars[ $sidebar['id'] ] = $sidebar;
	
		add_theme_support( 'widgets' );
	
		/**
		 * Fires once a sidebar has been registered.
		 *
		 * @since 3.0.0
		 *
		 * @param array $sidebar Parsed arguments for the registered sidebar.
		 */
		do_action( 'register_sidebar', $sidebar );
	
		return $sidebar['id'];
	}
endif;

// wp-includes/widgets.php (WP 6.9.5)
if( ! function_exists( 'unregister_sidebar' ) ) :
	function unregister_sidebar( $sidebar_id ) {
		global $wp_registered_sidebars;
	
		unset( $wp_registered_sidebars[ $sidebar_id ] );
	}
endif;

