<?php

// ------------------auto-generated---------------------

// wp-includes/theme.php (WP 6.8.5)
if( ! function_exists( 'current_theme_supports' ) ) :
	function current_theme_supports( $feature, ...$args ) {
		global $_wp_theme_features;
	
		if ( 'custom-header-uploads' === $feature ) {
			return current_theme_supports( 'custom-header', 'uploads' );
		}
	
		if ( ! isset( $_wp_theme_features[ $feature ] ) ) {
			return false;
		}
	
		// If no args passed then no extra checks need to be performed.
		if ( ! $args ) {
			/** This filter is documented in wp-includes/theme.php */
			return apply_filters( "current_theme_supports-{$feature}", true, $args, $_wp_theme_features[ $feature ] ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
	
		switch ( $feature ) {
			case 'post-thumbnails':
				/*
				 * post-thumbnails can be registered for only certain content/post types
				 * by passing an array of types to add_theme_support().
				 * If no array was passed, then any type is accepted.
				 */
				if ( true === $_wp_theme_features[ $feature ] ) {  // Registered for all types.
					return true;
				}
				$content_type = $args[0];
				return in_array( $content_type, $_wp_theme_features[ $feature ][0], true );
	
			case 'html5':
			case 'post-formats':
				/*
				 * Specific post formats can be registered by passing an array of types
				 * to add_theme_support().
				 *
				 * Specific areas of HTML5 support *must* be passed via an array to add_theme_support().
				 */
				$type = $args[0];
				return in_array( $type, $_wp_theme_features[ $feature ][0], true );
	
			case 'custom-logo':
			case 'custom-header':
			case 'custom-background':
				// Specific capabilities can be registered by passing an array to add_theme_support().
				return ( isset( $_wp_theme_features[ $feature ][0][ $args[0] ] ) && $_wp_theme_features[ $feature ][0][ $args[0] ] );
		}
	
		/**
		 * Filters whether the active theme supports a specific feature.
		 *
		 * The dynamic portion of the hook name, `$feature`, refers to the specific
		 * theme feature. See add_theme_support() for the list of possible values.
		 *
		 * @since 3.4.0
		 *
		 * @param bool   $supports Whether the active theme supports the given feature. Default true.
		 * @param array  $args     Array of arguments for the feature.
		 * @param string $feature  The theme feature.
		 */
		return apply_filters( "current_theme_supports-{$feature}", true, $args, $_wp_theme_features[ $feature ] ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
	}
endif;

