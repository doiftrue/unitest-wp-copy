<?php

// ------------------auto-generated---------------------

// wp-includes/functions.wp-styles.php (WP 6.6.5)
if( ! function_exists( 'wp_styles' ) ) :
	function wp_styles() {
		global $wp_styles;
	
		if ( ! ( $wp_styles instanceof WP_Styles ) ) {
			$wp_styles = new WP_Styles();
		}
	
		return $wp_styles;
	}
endif;

// wp-includes/functions.wp-styles.php (WP 6.6.5)
if( ! function_exists( 'wp_print_styles' ) ) :
	function wp_print_styles( $handles = false ) {
		global $wp_styles;
	
		if ( '' === $handles ) { // For 'wp_head'.
			$handles = false;
		}
	
		if ( ! $handles ) {
			/**
			 * Fires before styles in the $handles queue are printed.
			 *
			 * @since 2.6.0
			 */
			do_action( 'wp_print_styles' );
		}
	
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__ );
	
		if ( ! ( $wp_styles instanceof WP_Styles ) ) {
			if ( ! $handles ) {
				return array(); // No need to instantiate if nothing is there.
			}
		}
	
		return wp_styles()->do_items( $handles );
	}
endif;

// wp-includes/functions.wp-styles.php (WP 6.6.5)
if( ! function_exists( 'wp_add_inline_style' ) ) :
	function wp_add_inline_style( $handle, $data ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		if ( false !== stripos( $data, '</style>' ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				sprintf(
					/* translators: 1: <style>, 2: wp_add_inline_style() */
					__( 'Do not pass %1$s tags to %2$s.' ),
					'<code>&lt;style&gt;</code>',
					'<code>wp_add_inline_style()</code>'
				),
				'3.7.0'
			);
			$data = trim( preg_replace( '#<style[^>]*>(.*)</style>#is', '$1', $data ) );
		}
	
		return wp_styles()->add_inline_style( $handle, $data );
	}
endif;

// wp-includes/functions.wp-styles.php (WP 6.6.5)
if( ! function_exists( 'wp_register_style' ) ) :
	function wp_register_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		return wp_styles()->add( $handle, $src, $deps, $ver, $media );
	}
endif;

// wp-includes/functions.wp-styles.php (WP 6.6.5)
if( ! function_exists( 'wp_deregister_style' ) ) :
	function wp_deregister_style( $handle ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		wp_styles()->remove( $handle );
	}
endif;

// wp-includes/functions.wp-styles.php (WP 6.6.5)
if( ! function_exists( 'wp_enqueue_style' ) ) :
	function wp_enqueue_style( $handle, $src = '', $deps = array(), $ver = false, $media = 'all' ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		$wp_styles = wp_styles();
	
		if ( $src ) {
			$_handle = explode( '?', $handle );
			$wp_styles->add( $_handle[0], $src, $deps, $ver, $media );
		}
	
		$wp_styles->enqueue( $handle );
	}
endif;

// wp-includes/functions.wp-styles.php (WP 6.6.5)
if( ! function_exists( 'wp_dequeue_style' ) ) :
	function wp_dequeue_style( $handle ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		wp_styles()->dequeue( $handle );
	}
endif;

// wp-includes/functions.wp-styles.php (WP 6.6.5)
if( ! function_exists( 'wp_style_is' ) ) :
	function wp_style_is( $handle, $status = 'enqueued' ) {
		_wp_scripts_maybe_doing_it_wrong( __FUNCTION__, $handle );
	
		return (bool) wp_styles()->query( $handle, $status );
	}
endif;

// wp-includes/functions.wp-styles.php (WP 6.6.5)
if( ! function_exists( 'wp_style_add_data' ) ) :
	function wp_style_add_data( $handle, $key, $value ) {
		return wp_styles()->add_data( $handle, $key, $value );
	}
endif;

