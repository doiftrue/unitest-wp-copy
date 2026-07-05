<?php

// ------------------auto-generated---------------------

// wp-includes/canonical.php (WP 6.8.5)
if( ! function_exists( '_remove_qs_args_if_not_in_url' ) ) :
	function _remove_qs_args_if_not_in_url( $query_string, array $args_to_check, $url ) {
		$parsed_url = parse_url( $url );
	
		if ( ! empty( $parsed_url['query'] ) ) {
			parse_str( $parsed_url['query'], $parsed_query );
	
			foreach ( $args_to_check as $qv ) {
				if ( ! isset( $parsed_query[ $qv ] ) ) {
					$query_string = remove_query_arg( $qv, $query_string );
				}
			}
		} else {
			$query_string = remove_query_arg( $args_to_check, $query_string );
		}
	
		return $query_string;
	}
endif;

// wp-includes/canonical.php (WP 6.8.5)
if( ! function_exists( 'strip_fragment_from_url' ) ) :
	function strip_fragment_from_url( $url ) {
		$parsed_url = wp_parse_url( $url );
	
		if ( ! empty( $parsed_url['host'] ) ) {
			$url = '';
	
			if ( ! empty( $parsed_url['scheme'] ) ) {
				$url = $parsed_url['scheme'] . ':';
			}
	
			$url .= '//' . $parsed_url['host'];
	
			if ( ! empty( $parsed_url['port'] ) ) {
				$url .= ':' . $parsed_url['port'];
			}
	
			if ( ! empty( $parsed_url['path'] ) ) {
				$url .= $parsed_url['path'];
			}
	
			if ( ! empty( $parsed_url['query'] ) ) {
				$url .= '?' . $parsed_url['query'];
			}
		}
	
		return $url;
	}
endif;

