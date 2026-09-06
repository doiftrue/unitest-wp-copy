<?php

// ------------------auto-generated---------------------

// wp-includes/formatting.php (WP 7.1)
if( ! function_exists( 'balanceTags' ) ) :
	function balanceTags( $text, $force = false ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( $force || 1 === (int) get_option( 'use_balanceTags' ) ) {
			return force_balance_tags( $text );
		} else {
			return $text;
		}
	}
endif;

// wp-includes/formatting.php (WP 7.1)
if( ! function_exists( 'antispambot' ) ) :
	function antispambot( $email_address, $hex_encoding = 0 ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		$obfuscated     = '';
		$at             = 0;
		$end            = strlen( $email_address );
		$invalid_length = 0;
	
		while ( $at < $end ) {
			$was_at = $at;
			if (
				0 === _wp_scan_utf8( $email_address, $at, $invalid_length, null, 1 ) &&
				0 === $invalid_length
			) {
				break;
			}
	
			$character_length = $at - $was_at;
	
			if ( $character_length > 0 ) {
				$character = substr( $email_address, $was_at, $character_length );
	
				switch ( rand( 0, 1 + $hex_encoding ) ) {
					case 0:
						$code_point  = mb_ord( $character );
						$obfuscated .= "&#{$code_point};";
						break;
	
					case 1:
						$obfuscated .= $character;
						break;
	
					case 2:
						for ( $i = 0; $i < $character_length; $i++ ) {
							$hex_value   = bin2hex( $character[ $i ] );
							$obfuscated .= "%{$hex_value}";
						}
						break;
				}
			}
	
			if ( 0 !== $invalid_length ) {
				$obfuscated .= substr( $email_address, $at, $invalid_length );
			}
	
			$at += $invalid_length;
		}
	
		return str_replace( '@', '&#64;', $obfuscated );
	}
endif;

// wp-includes/formatting.php (WP 7.1)
if( ! function_exists( 'convert_smilies' ) ) :
	function convert_smilies( $text ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_smiliessearch;
	
		if ( ! get_option( 'use_smilies' ) || empty( $wp_smiliessearch ) ) {
			// Return default text.
			return $text;
		}
	
		// HTML loop taken from texturize function, could possible be consolidated.
		$textarr = preg_split( '/(<[^>]*>)/U', $text, -1, PREG_SPLIT_DELIM_CAPTURE ); // Capture the tags as well as in between.
	
		if ( false === $textarr ) {
			// Return default text.
			return $text;
		}
	
		// Loop stuff.
		$stop   = count( $textarr );
		$output = '';
	
		// Ignore processing of specific tags.
		$tags_to_ignore       = 'code|pre|style|script|textarea';
		$ignore_block_element = '';
	
		for ( $i = 0; $i < $stop; $i++ ) {
			$content = $textarr[ $i ];
	
			// If we're in an ignore block, wait until we find its closing tag.
			if ( '' === $ignore_block_element && preg_match( '/^<(' . $tags_to_ignore . ')[^>]*>/', $content, $matches ) ) {
				$ignore_block_element = $matches[1];
			}
	
			// If it's not a tag and not in ignore block.
			if ( '' === $ignore_block_element && strlen( $content ) > 0 && '<' !== $content[0] ) {
				$content = preg_replace_callback( $wp_smiliessearch, 'translate_smiley', $content );
			}
	
			// Did we exit ignore block?
			if ( '' !== $ignore_block_element && '</' . $ignore_block_element . '>' === $content ) {
				$ignore_block_element = '';
			}
	
			$output .= $content;
		}
	
		return $output;
	}
endif;

