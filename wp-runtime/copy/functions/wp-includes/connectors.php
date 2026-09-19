<?php

// ------------------auto-generated---------------------

// wp-includes/connectors.php (WP 7.1.1)
if( ! function_exists( 'wp_connectors_parse_application_password_credentials' ) ) :
	function wp_connectors_parse_application_password_credentials( string $value ): array {
		$separator = strpos( $value, ':' );
		// Trim so surrounding whitespace or a trailing newline (common when the
		// value comes from a file or `.env`) does not become part of the credentials.
		$username = false === $separator ? '' : trim( substr( $value, 0, $separator ) );
		$password = false === $separator ? '' : trim( substr( $value, $separator + 1 ) );
	
		if ( '' === $username || '' === $password ) {
			return array(
				'username' => '',
				'password' => '',
			);
		}
	
		return array(
			'username' => $username,
			'password' => $password,
		);
	}
endif;

// wp-includes/connectors.php (WP 7.1.1)
if( ! function_exists( '_wp_connectors_mask_api_key' ) ) :
	function _wp_connectors_mask_api_key( string $key ): string {
		if ( strlen( $key ) <= 4 ) {
			return $key;
		}
	
		return str_repeat( "\u{2022}", min( strlen( $key ) - 4, 16 ) ) . substr( $key, -4 );
	}
endif;

