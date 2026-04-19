<?php

// ------------------auto-generated---------------------

// wp-includes/formatting.php (WP 6.7.5)
if( ! function_exists( 'balanceTags' ) ) :
	function balanceTags( $text, $force = false ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		if ( $force || (int) $GLOBALS['stub_wp_options']->use_balanceTags === 1 ) {
			return force_balance_tags( $text );
		} else {
			return $text;
		}
	}
endif;

// wp-includes/formatting.php (WP 6.7.5)
if( ! function_exists( 'convert_smilies' ) ) :
	function convert_smilies( $text ) {
		if ( \Unitest_WP_Copy\WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return \Unitest_WP_Copy\WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}
	
		global $wp_smiliessearch;
		$output = '';
		if ( $GLOBALS['stub_wp_options']->use_smilies && ! empty( $wp_smiliessearch ) ) {
			// HTML loop taken from texturize function, could possible be consolidated.
			$textarr = preg_split( '/(<.*>)/U', $text, -1, PREG_SPLIT_DELIM_CAPTURE ); // Capture the tags as well as in between.
			$stop    = count( $textarr ); // Loop stuff.
	
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
		} else {
			// Return default text.
			$output = $text;
		}
		return $output;
	}
endif;

