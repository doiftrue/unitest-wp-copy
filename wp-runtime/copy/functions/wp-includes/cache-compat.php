<?php

// ------------------auto-generated---------------------

// wp-includes/cache-compat.php (WP 7.0.2)
if( ! function_exists( 'wp_cache_get_salted' ) ) :
		function wp_cache_get_salted( $cache_key, $group, $salt ) {
			$salt  = is_array( $salt ) ? implode( ':', $salt ) : $salt;
			$cache = wp_cache_get( $cache_key, $group );
	
			if ( ! is_array( $cache ) ) {
				return false;
			}
	
			if ( ! isset( $cache['salt'] ) || ! isset( $cache['data'] ) || $salt !== $cache['salt'] ) {
				return false;
			}
	
			return $cache['data'];
		}
endif;

// wp-includes/cache-compat.php (WP 7.0.2)
if( ! function_exists( 'wp_cache_set_salted' ) ) :
		function wp_cache_set_salted( $cache_key, $data, $group, $salt, $expire = 0 ) {
			$salt = is_array( $salt ) ? implode( ':', $salt ) : $salt;
			return wp_cache_set(
				$cache_key,
				array(
					'data' => $data,
					'salt' => $salt,
				),
				$group,
				$expire
			);
		}
endif;

// wp-includes/cache-compat.php (WP 7.0.2)
if( ! function_exists( 'wp_cache_get_multiple_salted' ) ) :
		function wp_cache_get_multiple_salted( $cache_keys, $group, $salt ) {
			$salt  = is_array( $salt ) ? implode( ':', $salt ) : $salt;
			$cache = wp_cache_get_multiple( $cache_keys, $group );
	
			foreach ( $cache as $key => $value ) {
				if ( ! is_array( $value ) ) {
					$cache[ $key ] = false;
					continue;
				}
				if ( ! isset( $value['salt'], $value['data'] ) || $salt !== $value['salt'] ) {
					$cache[ $key ] = false;
					continue;
				}
				$cache[ $key ] = $value['data'];
			}
	
			return $cache;
		}
endif;

// wp-includes/cache-compat.php (WP 7.0.2)
if( ! function_exists( 'wp_cache_set_multiple_salted' ) ) :
		function wp_cache_set_multiple_salted( $data, $group, $salt, $expire = 0 ) {
			$salt      = is_array( $salt ) ? implode( ':', $salt ) : $salt;
			$new_cache = array();
			foreach ( $data as $key => $value ) {
				$new_cache[ $key ] = array(
					'data' => $value,
					'salt' => $salt,
				);
			}
			return wp_cache_set_multiple( $new_cache, $group, $expire );
		}
endif;

