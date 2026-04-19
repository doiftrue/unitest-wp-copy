<?php

// ------------------auto-generated---------------------

// wp-includes/utf8.php (WP 6.9.4)
if( ! function_exists( 'wp_is_valid_utf8' ) ) :
		function wp_is_valid_utf8( string $bytes ): bool {
			return mb_check_encoding( $bytes, 'UTF-8' );
		}
endif;

// wp-includes/utf8.php (WP 6.9.4)
if( ! function_exists( 'wp_scrub_utf8' ) ) :
		function wp_scrub_utf8( $text ) {
			/*
			 * While it looks like setting the substitute character could fail,
			 * the internal PHP code will never fail when provided a valid
			 * code point as a number. In this case, there’s no need to check
			 * its return value to see if it succeeded.
			 */
			$prev_replacement_character = mb_substitute_character();
			mb_substitute_character( 0xFFFD );
			$scrubbed = mb_scrub( $text, 'UTF-8' );
			mb_substitute_character( $prev_replacement_character );
	
			return $scrubbed;
		}
endif;

// wp-includes/utf8.php (WP 6.9.4)
if( ! function_exists( 'wp_has_noncharacters' ) ) :
		function wp_has_noncharacters( string $text ): bool {
			return 1 === preg_match(
				'/[\x{FDD0}-\x{FDEF}\x{FFFE}\x{FFFF}\x{1FFFE}\x{1FFFF}\x{2FFFE}\x{2FFFF}\x{3FFFE}\x{3FFFF}\x{4FFFE}\x{4FFFF}\x{5FFFE}\x{5FFFF}\x{6FFFE}\x{6FFFF}\x{7FFFE}\x{7FFFF}\x{8FFFE}\x{8FFFF}\x{9FFFE}\x{9FFFF}\x{AFFFE}\x{AFFFF}\x{BFFFE}\x{BFFFF}\x{CFFFE}\x{CFFFF}\x{DFFFE}\x{DFFFF}\x{EFFFE}\x{EFFFF}\x{FFFFE}\x{FFFFF}\x{10FFFE}\x{10FFFF}]/u',
				$text
			);
		}
endif;

