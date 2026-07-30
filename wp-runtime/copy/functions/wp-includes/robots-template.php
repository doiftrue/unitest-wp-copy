<?php

// ------------------auto-generated---------------------

// wp-includes/robots-template.php (WP 7.0.2)
if( ! function_exists( 'wp_robots' ) ) :
	function wp_robots() {
		/**
		 * Filters the directives to be included in the 'robots' meta tag.
		 *
		 * The meta tag will only be included as necessary.
		 *
		 * @since 5.7.0
		 *
		 * @param array $robots Associative array of directives. Every key must be the name of the directive, and the
		 *                      corresponding value must either be a string to provide as value for the directive or a
		 *                      boolean `true` if it is a boolean directive, i.e. without a value.
		 */
		$robots = apply_filters( 'wp_robots', array() );
	
		$robots_strings = array();
		foreach ( $robots as $directive => $value ) {
			if ( is_string( $value ) ) {
				// If a string value, include it as value for the directive.
				$robots_strings[] = "{$directive}:{$value}";
			} elseif ( $value ) {
				// Otherwise, include the directive if it is truthy.
				$robots_strings[] = $directive;
			}
		}
	
		if ( empty( $robots_strings ) ) {
			return;
		}
	
		echo "<meta name='robots' content='" . esc_attr( implode( ', ', $robots_strings ) ) . "' />\n";
	}
endif;

// wp-includes/robots-template.php (WP 7.0.2)
if( ! function_exists( 'wp_robots_noindex' ) ) :
	function wp_robots_noindex( array $robots ) {
		if ( ! get_option( 'blog_public' ) ) {
			return wp_robots_no_robots( $robots );
		}
	
		return $robots;
	}
endif;

// wp-includes/robots-template.php (WP 7.0.2)
if( ! function_exists( 'wp_robots_no_robots' ) ) :
	function wp_robots_no_robots( array $robots ) {
		$robots['noindex'] = true;
	
		if ( get_option( 'blog_public' ) ) {
			$robots['follow'] = true;
		} else {
			$robots['nofollow'] = true;
		}
	
		return $robots;
	}
endif;

// wp-includes/robots-template.php (WP 7.0.2)
if( ! function_exists( 'wp_robots_sensitive_page' ) ) :
	function wp_robots_sensitive_page( array $robots ) {
		$robots['noindex']   = true;
		$robots['noarchive'] = true;
		return $robots;
	}
endif;

// wp-includes/robots-template.php (WP 7.0.2)
if( ! function_exists( 'wp_robots_max_image_preview_large' ) ) :
	function wp_robots_max_image_preview_large( array $robots ) {
		if ( get_option( 'blog_public' ) ) {
			$robots['max-image-preview'] = 'large';
		}
		return $robots;
	}
endif;

