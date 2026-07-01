<?php

// ------------------auto-generated---------------------

// wp-includes/script-modules.php (WP 6.7.5)
if( ! function_exists( 'wp_register_script_module' ) ) :
	function wp_register_script_module( string $id, string $src, array $deps = array(), $version = false ) {
		wp_script_modules()->register( $id, $src, $deps, $version );
	}
endif;

// wp-includes/script-modules.php (WP 6.7.5)
if( ! function_exists( 'wp_enqueue_script_module' ) ) :
	function wp_enqueue_script_module( string $id, string $src = '', array $deps = array(), $version = false ) {
		wp_script_modules()->enqueue( $id, $src, $deps, $version );
	}
endif;

// wp-includes/script-modules.php (WP 6.7.5)
if( ! function_exists( 'wp_dequeue_script_module' ) ) :
	function wp_dequeue_script_module( string $id ) {
		wp_script_modules()->dequeue( $id );
	}
endif;

// wp-includes/script-modules.php (WP 6.7.5)
if( ! function_exists( 'wp_deregister_script_module' ) ) :
	function wp_deregister_script_module( string $id ) {
		wp_script_modules()->deregister( $id );
	}
endif;

