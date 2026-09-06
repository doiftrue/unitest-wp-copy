<?php

// ------------------auto-generated---------------------

// wp-includes/utf8.php (WP 7.1)
if( ! function_exists( 'wp_is_valid_utf8' ) ) :
		function wp_is_valid_utf8( string $bytes ): bool {
			return mb_check_encoding( $bytes, 'UTF-8' );
		}
endif;

// wp-includes/utf8.php (WP 7.1)
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

// wp-includes/utf8.php (WP 7.1)
if( ! function_exists( 'wp_has_noncharacters' ) ) :
	function wp_has_noncharacters( string $text ): bool {
		/*
		 * Match the UTF-8 byte sequences directly so malformed UTF-8 elsewhere
		 * in the subject does not cause PCRE's Unicode mode to reject the string.
		 */
		return 1 === preg_match(
			'~
				# U+FDD0-U+FDEF, U+FFFE-U+FFFF
				\xEF(?:\xB7[\x90-\xAF]|\xBF[\xBE\xBF])
				|
				# U+nFFFE/U+nFFFF
				(?:\xF0[\x9F\xAF\xBF]|[\xF1-\xF3][\x8F\x9F\xAF\xBF]|\xF4\x8F)\xBF[\xBE\xBF]
			~x',
			$text
		);
	}
endif;

