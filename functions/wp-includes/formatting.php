<?php
/**
 * Copy of WP functions to they work as is.
 * Here is only functions that not depends on DB or other external libs.
 */

// ------------------auto-generated---------------------

// wp-includes/formatting.php (WP 6.8.1)
function wptexturize( $text, $reset = false ) {
	global $wp_cockneyreplace, $shortcode_tags;
	static $static_characters            = null,
		$static_replacements             = null,
		$dynamic_characters              = null,
		$dynamic_replacements            = null,
		$default_no_texturize_tags       = null,
		$default_no_texturize_shortcodes = null,
		$run_texturize                   = true,
		$apos                            = null,
		$prime                           = null,
		$double_prime                    = null,
		$opening_quote                   = null,
		$closing_quote                   = null,
		$opening_single_quote            = null,
		$closing_single_quote            = null,
		$open_q_flag                     = '<!--oq-->',
		$open_sq_flag                    = '<!--osq-->',
		$apos_flag                       = '<!--apos-->';

	// If there's nothing to do, just stop.
	if ( empty( $text ) || false === $run_texturize ) {
		return $text;
	}

	// Set up static variables. Run once only.
	if ( $reset || ! isset( $static_characters ) ) {
		/**
		 * Filters whether to skip running wptexturize().
		 *
		 * Returning false from the filter will effectively short-circuit wptexturize()
		 * and return the original text passed to the function instead.
		 *
		 * The filter runs only once, the first time wptexturize() is called.
		 *
		 * @since 4.0.0
		 *
		 * @see wptexturize()
		 *
		 * @param bool $run_texturize Whether to short-circuit wptexturize().
		 */
		$run_texturize = apply_filters( 'run_wptexturize', $run_texturize );
		if ( false === $run_texturize ) {
			return $text;
		}

		/* translators: Opening curly double quote. */
		$opening_quote = _x( '&#8220;', 'opening curly double quote' );
		/* translators: Closing curly double quote. */
		$closing_quote = _x( '&#8221;', 'closing curly double quote' );

		/* translators: Apostrophe, for example in 'cause or can't. */
		$apos = _x( '&#8217;', 'apostrophe' );

		/* translators: Prime, for example in 9' (nine feet). */
		$prime = _x( '&#8242;', 'prime' );
		/* translators: Double prime, for example in 9" (nine inches). */
		$double_prime = _x( '&#8243;', 'double prime' );

		/* translators: Opening curly single quote. */
		$opening_single_quote = _x( '&#8216;', 'opening curly single quote' );
		/* translators: Closing curly single quote. */
		$closing_single_quote = _x( '&#8217;', 'closing curly single quote' );

		/* translators: En dash. */
		$en_dash = _x( '&#8211;', 'en dash' );
		/* translators: Em dash. */
		$em_dash = _x( '&#8212;', 'em dash' );

		$default_no_texturize_tags       = array( 'pre', 'code', 'kbd', 'style', 'script', 'tt' );
		$default_no_texturize_shortcodes = array( 'code' );

		// If a plugin has provided an autocorrect array, use it.
		if ( isset( $wp_cockneyreplace ) ) {
			$cockney        = array_keys( $wp_cockneyreplace );
			$cockneyreplace = array_values( $wp_cockneyreplace );
		} else {
			/*
			 * translators: This is a comma-separated list of words that defy the syntax of quotations in normal use,
			 * for example... 'We do not have enough words yet'... is a typical quoted phrase. But when we write
			 * lines of code 'til we have enough of 'em, then we need to insert apostrophes instead of quotes.
			 */
			$cockney = explode(
				',',
				_x(
					"'tain't,'twere,'twas,'tis,'twill,'til,'bout,'nuff,'round,'cause,'em",
					'Comma-separated list of words to texturize in your language'
				)
			);

			$cockneyreplace = explode(
				',',
				_x(
					'&#8217;tain&#8217;t,&#8217;twere,&#8217;twas,&#8217;tis,&#8217;twill,&#8217;til,&#8217;bout,&#8217;nuff,&#8217;round,&#8217;cause,&#8217;em',
					'Comma-separated list of replacement words in your language'
				)
			);
		}

		$static_characters   = array_merge( array( '...', '``', '\'\'', ' (tm)' ), $cockney );
		$static_replacements = array_merge( array( '&#8230;', $opening_quote, $closing_quote, ' &#8482;' ), $cockneyreplace );

		/*
		 * Pattern-based replacements of characters.
		 * Sort the remaining patterns into several arrays for performance tuning.
		 */
		$dynamic_characters   = array(
			'apos'  => array(),
			'quote' => array(),
			'dash'  => array(),
		);
		$dynamic_replacements = array(
			'apos'  => array(),
			'quote' => array(),
			'dash'  => array(),
		);
		$dynamic              = array();
		$spaces               = wp_spaces_regexp();

		// '99' and '99" are ambiguous among other patterns; assume it's an abbreviated year at the end of a quotation.
		if ( "'" !== $apos || "'" !== $closing_single_quote ) {
			$dynamic[ '/\'(\d\d)\'(?=\Z|[.,:;!?)}\-\]]|&gt;|' . $spaces . ')/' ] = $apos_flag . '$1' . $closing_single_quote;
		}
		if ( "'" !== $apos || '"' !== $closing_quote ) {
			$dynamic[ '/\'(\d\d)"(?=\Z|[.,:;!?)}\-\]]|&gt;|' . $spaces . ')/' ] = $apos_flag . '$1' . $closing_quote;
		}

		// '99 '99s '99's (apostrophe)  But never '9 or '99% or '999 or '99.0.
		if ( "'" !== $apos ) {
			$dynamic['/\'(?=\d\d(?:\Z|(?![%\d]|[.,]\d)))/'] = $apos_flag;
		}

		// Quoted numbers like '0.42'.
		if ( "'" !== $opening_single_quote && "'" !== $closing_single_quote ) {
			$dynamic[ '/(?<=\A|' . $spaces . ')\'(\d[.,\d]*)\'/' ] = $open_sq_flag . '$1' . $closing_single_quote;
		}

		// Single quote at start, or preceded by (, {, <, [, ", -, or spaces.
		if ( "'" !== $opening_single_quote ) {
			$dynamic[ '/(?<=\A|[([{"\-]|&lt;|' . $spaces . ')\'/' ] = $open_sq_flag;
		}

		// Apostrophe in a word. No spaces, double apostrophes, or other punctuation.
		if ( "'" !== $apos ) {
			$dynamic[ '/(?<!' . $spaces . ')\'(?!\Z|[.,:;!?"\'(){}[\]\-]|&[lg]t;|' . $spaces . ')/' ] = $apos_flag;
		}

		$dynamic_characters['apos']   = array_keys( $dynamic );
		$dynamic_replacements['apos'] = array_values( $dynamic );
		$dynamic                      = array();

		// Quoted numbers like "42".
		if ( '"' !== $opening_quote && '"' !== $closing_quote ) {
			$dynamic[ '/(?<=\A|' . $spaces . ')"(\d[.,\d]*)"/' ] = $open_q_flag . '$1' . $closing_quote;
		}

		// Double quote at start, or preceded by (, {, <, [, -, or spaces, and not followed by spaces.
		if ( '"' !== $opening_quote ) {
			$dynamic[ '/(?<=\A|[([{\-]|&lt;|' . $spaces . ')"(?!' . $spaces . ')/' ] = $open_q_flag;
		}

		$dynamic_characters['quote']   = array_keys( $dynamic );
		$dynamic_replacements['quote'] = array_values( $dynamic );
		$dynamic                       = array();

		// Dashes and spaces.
		$dynamic['/---/'] = $em_dash;
		$dynamic[ '/(?<=^|' . $spaces . ')--(?=$|' . $spaces . ')/' ] = $em_dash;
		$dynamic['/(?<!xn)--/']                                       = $en_dash;
		$dynamic[ '/(?<=^|' . $spaces . ')-(?=$|' . $spaces . ')/' ]  = $en_dash;

		$dynamic_characters['dash']   = array_keys( $dynamic );
		$dynamic_replacements['dash'] = array_values( $dynamic );
	}

	// Must do this every time in case plugins use these filters in a context sensitive manner.
	/**
	 * Filters the list of HTML elements not to texturize.
	 *
	 * @since 2.8.0
	 *
	 * @param string[] $default_no_texturize_tags An array of HTML element names.
	 */
	$no_texturize_tags = apply_filters( 'no_texturize_tags', $default_no_texturize_tags );
	/**
	 * Filters the list of shortcodes not to texturize.
	 *
	 * @since 2.8.0
	 *
	 * @param string[] $default_no_texturize_shortcodes An array of shortcode names.
	 */
	$no_texturize_shortcodes = apply_filters( 'no_texturize_shortcodes', $default_no_texturize_shortcodes );

	$no_texturize_tags_stack       = array();
	$no_texturize_shortcodes_stack = array();

	// Look for shortcodes and HTML elements.

	preg_match_all( '@\[/?([^<>&/\[\]\x00-\x20=]++)@', $text, $matches );
	$tagnames         = array_intersect( array_keys( $shortcode_tags ), $matches[1] );
	$found_shortcodes = ! empty( $tagnames );
	$shortcode_regex  = $found_shortcodes ? _get_wptexturize_shortcode_regex( $tagnames ) : '';
	$regex            = _get_wptexturize_split_regex( $shortcode_regex );

	$textarr = preg_split( $regex, $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

	foreach ( $textarr as &$curl ) {
		// Only call _wptexturize_pushpop_element if $curl is a delimiter.
		$first = $curl[0];
		if ( '<' === $first ) {
			if ( str_starts_with( $curl, '<!--' ) ) {
				// This is an HTML comment delimiter.
				continue;
			} else {
				// This is an HTML element delimiter.

				// Replace each & with &#038; unless it already looks like an entity.
				$curl = preg_replace( '/&(?!#(?:\d+|x[a-f0-9]+);|[a-z1-4]{1,8};)/i', '&#038;', $curl );

				_wptexturize_pushpop_element( $curl, $no_texturize_tags_stack, $no_texturize_tags );
			}
		} elseif ( '' === trim( $curl ) ) {
			// This is a newline between delimiters. Performance improves when we check this.
			continue;

		} elseif ( '[' === $first && $found_shortcodes && 1 === preg_match( '/^' . $shortcode_regex . '$/', $curl ) ) {
			// This is a shortcode delimiter.

			if ( ! str_starts_with( $curl, '[[' ) && ! str_ends_with( $curl, ']]' ) ) {
				// Looks like a normal shortcode.
				_wptexturize_pushpop_element( $curl, $no_texturize_shortcodes_stack, $no_texturize_shortcodes );
			} else {
				// Looks like an escaped shortcode.
				continue;
			}
		} elseif ( empty( $no_texturize_shortcodes_stack ) && empty( $no_texturize_tags_stack ) ) {
			// This is neither a delimiter, nor is this content inside of no_texturize pairs. Do texturize.

			$curl = str_replace( $static_characters, $static_replacements, $curl );

			if ( str_contains( $curl, "'" ) ) {
				$curl = preg_replace( $dynamic_characters['apos'], $dynamic_replacements['apos'], $curl );
				$curl = wptexturize_primes( $curl, "'", $prime, $open_sq_flag, $closing_single_quote );
				$curl = str_replace( $apos_flag, $apos, $curl );
				$curl = str_replace( $open_sq_flag, $opening_single_quote, $curl );
			}
			if ( str_contains( $curl, '"' ) ) {
				$curl = preg_replace( $dynamic_characters['quote'], $dynamic_replacements['quote'], $curl );
				$curl = wptexturize_primes( $curl, '"', $double_prime, $open_q_flag, $closing_quote );
				$curl = str_replace( $open_q_flag, $opening_quote, $curl );
			}
			if ( str_contains( $curl, '-' ) ) {
				$curl = preg_replace( $dynamic_characters['dash'], $dynamic_replacements['dash'], $curl );
			}

			// 9x9 (times), but never 0x9999.
			if ( 1 === preg_match( '/(?<=\d)x\d/', $curl ) ) {
				// Searching for a digit is 10 times more expensive than for the x, so we avoid doing this one!
				$curl = preg_replace( '/\b(\d(?(?<=0)[\d\.,]+|[\d\.,]*))x(\d[\d\.,]*)\b/', '$1&#215;$2', $curl );
			}

			// Replace each & with &#038; unless it already looks like an entity.
			$curl = preg_replace( '/&(?!#(?:\d+|x[a-f0-9]+);|[a-z1-4]{1,8};)/i', '&#038;', $curl );
		}
	}

	return implode( '', $textarr );
}

// wp-includes/formatting.php (WP 6.8.1)
function wptexturize_primes( $haystack, $needle, $prime, $open_quote, $close_quote ) {
	$spaces           = wp_spaces_regexp();
	$flag             = '<!--wp-prime-or-quote-->';
	$quote_pattern    = "/$needle(?=\\Z|[.,:;!?)}\\-\\]]|&gt;|" . $spaces . ')/';
	$prime_pattern    = "/(?<=\\d)$needle/";
	$flag_after_digit = "/(?<=\\d)$flag/";
	$flag_no_digit    = "/(?<!\\d)$flag/";

	$sentences = explode( $open_quote, $haystack );

	foreach ( $sentences as $key => &$sentence ) {
		if ( ! str_contains( $sentence, $needle ) ) {
			continue;
		} elseif ( 0 !== $key && 0 === substr_count( $sentence, $close_quote ) ) {
			$sentence = preg_replace( $quote_pattern, $flag, $sentence, -1, $count );
			if ( $count > 1 ) {
				// This sentence appears to have multiple closing quotes. Attempt Vulcan logic.
				$sentence = preg_replace( $flag_no_digit, $close_quote, $sentence, -1, $count2 );
				if ( 0 === $count2 ) {
					// Try looking for a quote followed by a period.
					$count2 = substr_count( $sentence, "$flag." );
					if ( $count2 > 0 ) {
						// Assume the rightmost quote-period match is the end of quotation.
						$pos = strrpos( $sentence, "$flag." );
					} else {
						/*
						 * When all else fails, make the rightmost candidate a closing quote.
						 * This is most likely to be problematic in the context of bug #18549.
						 */
						$pos = strrpos( $sentence, $flag );
					}
					$sentence = substr_replace( $sentence, $close_quote, $pos, strlen( $flag ) );
				}
				// Use conventional replacement on any remaining primes and quotes.
				$sentence = preg_replace( $prime_pattern, $prime, $sentence );
				$sentence = preg_replace( $flag_after_digit, $prime, $sentence );
				$sentence = str_replace( $flag, $close_quote, $sentence );
			} elseif ( 1 === $count ) {
				// Found only one closing quote candidate, so give it priority over primes.
				$sentence = str_replace( $flag, $close_quote, $sentence );
				$sentence = preg_replace( $prime_pattern, $prime, $sentence );
			} else {
				// No closing quotes found. Just run primes pattern.
				$sentence = preg_replace( $prime_pattern, $prime, $sentence );
			}
		} else {
			$sentence = preg_replace( $prime_pattern, $prime, $sentence );
			$sentence = preg_replace( $quote_pattern, $close_quote, $sentence );
		}
		if ( '"' === $needle && str_contains( $sentence, '"' ) ) {
			$sentence = str_replace( '"', $close_quote, $sentence );
		}
	}

	return implode( $open_quote, $sentences );
}

// wp-includes/formatting.php (WP 6.8.1)
function _wptexturize_pushpop_element( $text, &$stack, $disabled_elements ) {
	// Is it an opening tag or closing tag?
	if ( isset( $text[1] ) && '/' !== $text[1] ) {
		$opening_tag = true;
		$name_offset = 1;
	} elseif ( 0 === count( $stack ) ) {
		// Stack is empty. Just stop.
		return;
	} else {
		$opening_tag = false;
		$name_offset = 2;
	}

	// Parse out the tag name.
	$space = strpos( $text, ' ' );
	if ( false === $space ) {
		$space = -1;
	} else {
		$space -= $name_offset;
	}
	$tag = substr( $text, $name_offset, $space );

	// Handle disabled tags.
	if ( in_array( $tag, $disabled_elements, true ) ) {
		if ( $opening_tag ) {
			/*
			 * This disables texturize until we find a closing tag of our type
			 * (e.g. <pre>) even if there was invalid nesting before that.
			 *
			 * Example: in the case <pre>sadsadasd</code>"baba"</pre>
			 *          "baba" won't be texturized.
			 */

			array_push( $stack, $tag );
		} elseif ( end( $stack ) === $tag ) {
			array_pop( $stack );
		}
	}
}

// wp-includes/formatting.php (WP 6.8.1)
function wpautop( $text, $br = true ) {
	$pre_tags = array();

	if ( trim( $text ) === '' ) {
		return '';
	}

	// Just to make things a little easier, pad the end.
	$text = $text . "\n";

	/*
	 * Pre tags shouldn't be touched by autop.
	 * Replace pre tags with placeholders and bring them back after autop.
	 */
	if ( str_contains( $text, '<pre' ) ) {
		$text_parts = explode( '</pre>', $text );
		$last_part  = array_pop( $text_parts );
		$text       = '';
		$i          = 0;

		foreach ( $text_parts as $text_part ) {
			$start = strpos( $text_part, '<pre' );

			// Malformed HTML?
			if ( false === $start ) {
				$text .= $text_part;
				continue;
			}

			$name              = "<pre wp-pre-tag-$i></pre>";
			$pre_tags[ $name ] = substr( $text_part, $start ) . '</pre>';

			$text .= substr( $text_part, 0, $start ) . $name;
			++$i;
		}

		$text .= $last_part;
	}
	// Change multiple <br>'s into two line breaks, which will turn into paragraphs.
	$text = preg_replace( '|<br\s*/?>\s*<br\s*/?>|', "\n\n", $text );

	$allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

	// Add a double line break above block-level opening tags.
	$text = preg_replace( '!(<' . $allblocks . '[\s/>])!', "\n\n$1", $text );

	// Add a double line break below block-level closing tags.
	$text = preg_replace( '!(</' . $allblocks . '>)!', "$1\n\n", $text );

	// Add a double line break after hr tags, which are self closing.
	$text = preg_replace( '!(<hr\s*?/?>)!', "$1\n\n", $text );

	// Standardize newline characters to "\n".
	$text = str_replace( array( "\r\n", "\r" ), "\n", $text );

	// Find newlines in all elements and add placeholders.
	$text = wp_replace_in_html_tags( $text, array( "\n" => ' <!-- wpnl --> ' ) );

	// Collapse line breaks before and after <option> elements so they don't get autop'd.
	if ( str_contains( $text, '<option' ) ) {
		$text = preg_replace( '|\s*<option|', '<option', $text );
		$text = preg_replace( '|</option>\s*|', '</option>', $text );
	}

	/*
	 * Collapse line breaks inside <object> elements, before <param> and <embed> elements
	 * so they don't get autop'd.
	 */
	if ( str_contains( $text, '</object>' ) ) {
		$text = preg_replace( '|(<object[^>]*>)\s*|', '$1', $text );
		$text = preg_replace( '|\s*</object>|', '</object>', $text );
		$text = preg_replace( '%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $text );
	}

	/*
	 * Collapse line breaks inside <audio> and <video> elements,
	 * before and after <source> and <track> elements.
	 */
	if ( str_contains( $text, '<source' ) || str_contains( $text, '<track' ) ) {
		$text = preg_replace( '%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $text );
		$text = preg_replace( '%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $text );
		$text = preg_replace( '%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $text );
	}

	// Collapse line breaks before and after <figcaption> elements.
	if ( str_contains( $text, '<figcaption' ) ) {
		$text = preg_replace( '|\s*(<figcaption[^>]*>)|', '$1', $text );
		$text = preg_replace( '|</figcaption>\s*|', '</figcaption>', $text );
	}

	// Remove more than two contiguous line breaks.
	$text = preg_replace( "/\n\n+/", "\n\n", $text );

	// Split up the contents into an array of strings, separated by double line breaks.
	$paragraphs = preg_split( '/\n\s*\n/', $text, -1, PREG_SPLIT_NO_EMPTY );

	// Reset $text prior to rebuilding.
	$text = '';

	// Rebuild the content as a string, wrapping every bit with a <p>.
	foreach ( $paragraphs as $paragraph ) {
		$text .= '<p>' . trim( $paragraph, "\n" ) . "</p>\n";
	}

	// Under certain strange conditions it could create a P of entirely whitespace.
	$text = preg_replace( '|<p>\s*</p>|', '', $text );

	// Add a closing <p> inside <div>, <address>, or <form> tag if missing.
	$text = preg_replace( '!<p>([^<]+)</(div|address|form)>!', '<p>$1</p></$2>', $text );

	// If an opening or closing block element tag is wrapped in a <p>, unwrap it.
	$text = preg_replace( '!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $text );

	// In some cases <li> may get wrapped in <p>, fix them.
	$text = preg_replace( '|<p>(<li.+?)</p>|', '$1', $text );

	// If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
	$text = preg_replace( '|<p><blockquote([^>]*)>|i', '<blockquote$1><p>', $text );
	$text = str_replace( '</blockquote></p>', '</p></blockquote>', $text );

	// If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
	$text = preg_replace( '!<p>\s*(</?' . $allblocks . '[^>]*>)!', '$1', $text );

	// If an opening or closing block element tag is followed by a closing <p> tag, remove it.
	$text = preg_replace( '!(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $text );

	// Optionally insert line breaks.
	if ( $br ) {
		// Replace newlines that shouldn't be touched with a placeholder.
		$text = preg_replace_callback( '/<(script|style|svg|math).*?<\/\\1>/s', '_autop_newline_preservation_helper', $text );

		// Normalize <br>.
		$text = str_replace( array( '<br>', '<br/>' ), '<br />', $text );

		// Replace any new line characters that aren't preceded by a <br /> with a <br />.
		$text = preg_replace( '|(?<!<br />)\s*\n|', "<br />\n", $text );

		// Replace newline placeholders with newlines.
		$text = str_replace( '<WPPreserveNewline />', "\n", $text );
	}

	// If a <br /> tag is after an opening or closing block tag, remove it.
	$text = preg_replace( '!(</?' . $allblocks . '[^>]*>)\s*<br />!', '$1', $text );

	// If a <br /> tag is before a subset of opening or closing block tags, remove it.
	$text = preg_replace( '!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $text );
	$text = preg_replace( "|\n</p>$|", '</p>', $text );

	// Replace placeholder <pre> tags with their original content.
	if ( ! empty( $pre_tags ) ) {
		$text = str_replace( array_keys( $pre_tags ), array_values( $pre_tags ), $text );
	}

	// Restore newlines in all elements.
	if ( str_contains( $text, '<!-- wpnl -->' ) ) {
		$text = str_replace( array( ' <!-- wpnl --> ', '<!-- wpnl -->' ), "\n", $text );
	}

	return $text;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_html_split( $input ) {
	return preg_split( get_html_split_regex(), $input, -1, PREG_SPLIT_DELIM_CAPTURE );
}

// wp-includes/formatting.php (WP 6.8.1)
function get_html_split_regex() {
	static $regex;

	if ( ! isset( $regex ) ) {
		// phpcs:disable Squiz.Strings.ConcatenationSpacing.PaddingFound -- don't remove regex indentation
		$comments =
			'!'             // Start of comment, after the <.
			. '(?:'         // Unroll the loop: Consume everything until --> is found.
			.     '-(?!->)' // Dash not followed by end of comment.
			.     '[^\-]*+' // Consume non-dashes.
			. ')*+'         // Loop possessively.
			. '(?:-->)?';   // End of comment. If not found, match all input.

		$cdata =
			'!\[CDATA\['    // Start of comment, after the <.
			. '[^\]]*+'     // Consume non-].
			. '(?:'         // Unroll the loop: Consume everything until ]]> is found.
			.     '](?!]>)' // One ] not followed by end of comment.
			.     '[^\]]*+' // Consume non-].
			. ')*+'         // Loop possessively.
			. '(?:]]>)?';   // End of comment. If not found, match all input.

		$escaped =
			'(?='             // Is the element escaped?
			.    '!--'
			. '|'
			.    '!\[CDATA\['
			. ')'
			. '(?(?=!-)'      // If yes, which type?
			.     $comments
			. '|'
			.     $cdata
			. ')';

		$regex =
			'/('                // Capture the entire match.
			.     '<'           // Find start of element.
			.     '(?'          // Conditional expression follows.
			.         $escaped  // Find end of escaped element.
			.     '|'           // ...else...
			.         '[^>]*>?' // Find end of normal element.
			.     ')'
			. ')/';
		// phpcs:enable
	}

	return $regex;
}

// wp-includes/formatting.php (WP 6.8.1)
function _get_wptexturize_split_regex( $shortcode_regex = '' ) {
	static $html_regex;

	if ( ! isset( $html_regex ) ) {
		// phpcs:disable Squiz.Strings.ConcatenationSpacing.PaddingFound -- don't remove regex indentation
		$comment_regex =
			'!'             // Start of comment, after the <.
			. '(?:'         // Unroll the loop: Consume everything until --> is found.
			.     '-(?!->)' // Dash not followed by end of comment.
			.     '[^\-]*+' // Consume non-dashes.
			. ')*+'         // Loop possessively.
			. '(?:-->)?';   // End of comment. If not found, match all input.

		$html_regex = // Needs replaced with wp_html_split() per Shortcode API Roadmap.
			'<'                  // Find start of element.
			. '(?(?=!--)'        // Is this a comment?
			.     $comment_regex // Find end of comment.
			. '|'
			.     '[^>]*>?'      // Find end of element. If not found, match all input.
			. ')';
		// phpcs:enable
	}

	if ( empty( $shortcode_regex ) ) {
		$regex = '/(' . $html_regex . ')/';
	} else {
		$regex = '/(' . $html_regex . '|' . $shortcode_regex . ')/';
	}

	return $regex;
}

// wp-includes/formatting.php (WP 6.8.1)
function _get_wptexturize_shortcode_regex( $tagnames ) {
	$tagregexp = implode( '|', array_map( 'preg_quote', $tagnames ) );
	$tagregexp = "(?:$tagregexp)(?=[\\s\\]\\/])"; // Excerpt of get_shortcode_regex().
	// phpcs:disable Squiz.Strings.ConcatenationSpacing.PaddingFound -- don't remove regex indentation
	$regex =
		'\['                // Find start of shortcode.
		. '[\/\[]?'         // Shortcodes may begin with [/ or [[.
		. $tagregexp        // Only match registered shortcodes, because performance.
		. '(?:'
		.     '[^\[\]<>]+'  // Shortcodes do not contain other shortcodes. Quantifier critical.
		. '|'
		.     '<[^\[\]>]*>' // HTML elements permitted. Prevents matching ] before >.
		. ')*+'             // Possessive critical.
		. '\]'              // Find end of shortcode.
		. '\]?';            // Shortcodes may end with ]].
	// phpcs:enable

	return $regex;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_replace_in_html_tags( $haystack, $replace_pairs ) {
	// Find all elements.
	$textarr = wp_html_split( $haystack );
	$changed = false;

	// Optimize when searching for one item.
	if ( 1 === count( $replace_pairs ) ) {
		// Extract $needle and $replace.
		$needle  = array_key_first( $replace_pairs );
		$replace = $replace_pairs[ $needle ];

		// Loop through delimiters (elements) only.
		for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
			if ( str_contains( $textarr[ $i ], $needle ) ) {
				$textarr[ $i ] = str_replace( $needle, $replace, $textarr[ $i ] );
				$changed       = true;
			}
		}
	} else {
		// Extract all $needles.
		$needles = array_keys( $replace_pairs );

		// Loop through delimiters (elements) only.
		for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
			foreach ( $needles as $needle ) {
				if ( str_contains( $textarr[ $i ], $needle ) ) {
					$textarr[ $i ] = strtr( $textarr[ $i ], $replace_pairs );
					$changed       = true;
					// After one strtr() break out of the foreach loop and look at next element.
					break;
				}
			}
		}
	}

	if ( $changed ) {
		$haystack = implode( $textarr );
	}

	return $haystack;
}

// wp-includes/formatting.php (WP 6.8.1)
function _autop_newline_preservation_helper( $matches ) {
	return str_replace( "\n", '<WPPreserveNewline />', $matches[0] );
}

// wp-includes/formatting.php (WP 6.8.1)
function shortcode_unautop( $text ) {
	global $shortcode_tags;

	if ( empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) ) {
		return $text;
	}

	$tagregexp = implode( '|', array_map( 'preg_quote', array_keys( $shortcode_tags ) ) );
	$spaces    = wp_spaces_regexp();

	// phpcs:disable Squiz.Strings.ConcatenationSpacing.PaddingFound,Universal.WhiteSpace.PrecisionAlignment.Found -- don't remove regex indentation
	$pattern =
		'/'
		. '<p>'                              // Opening paragraph.
		. '(?:' . $spaces . ')*+'            // Optional leading whitespace.
		. '('                                // 1: The shortcode.
		.     '\\['                          // Opening bracket.
		.     "($tagregexp)"                 // 2: Shortcode name.
		.     '(?![\\w-])'                   // Not followed by word character or hyphen.
											 // Unroll the loop: Inside the opening shortcode tag.
		.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash.
		.     '(?:'
		.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket.
		.         '[^\\]\\/]*'               // Not a closing bracket or forward slash.
		.     ')*?'
		.     '(?:'
		.         '\\/\\]'                   // Self closing tag and closing bracket.
		.     '|'
		.         '\\]'                      // Closing bracket.
		.         '(?:'                      // Unroll the loop: Optionally, anything between the opening and closing shortcode tags.
		.             '[^\\[]*+'             // Not an opening bracket.
		.             '(?:'
		.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag.
		.                 '[^\\[]*+'         // Not an opening bracket.
		.             ')*+'
		.             '\\[\\/\\2\\]'         // Closing shortcode tag.
		.         ')?'
		.     ')'
		. ')'
		. '(?:' . $spaces . ')*+'            // Optional trailing whitespace.
		. '<\\/p>'                           // Closing paragraph.
		. '/';
	// phpcs:enable

	return preg_replace( $pattern, '$1', $text );
}

// wp-includes/formatting.php (WP 6.8.1)
function seems_utf8( $str ) {
	mbstring_binary_safe_encoding();
	$length = strlen( $str );
	reset_mbstring_encoding();

	for ( $i = 0; $i < $length; $i++ ) {
		$c = ord( $str[ $i ] );

		if ( $c < 0x80 ) {
			$n = 0; // 0bbbbbbb
		} elseif ( ( $c & 0xE0 ) === 0xC0 ) {
			$n = 1; // 110bbbbb
		} elseif ( ( $c & 0xF0 ) === 0xE0 ) {
			$n = 2; // 1110bbbb
		} elseif ( ( $c & 0xF8 ) === 0xF0 ) {
			$n = 3; // 11110bbb
		} elseif ( ( $c & 0xFC ) === 0xF8 ) {
			$n = 4; // 111110bb
		} elseif ( ( $c & 0xFE ) === 0xFC ) {
			$n = 5; // 1111110b
		} else {
			return false; // Does not match any model.
		}

		for ( $j = 0; $j < $n; $j++ ) { // n bytes matching 10bbbbbb follow?
			if ( ( ++$i === $length ) || ( ( ord( $str[ $i ] ) & 0xC0 ) !== 0x80 ) ) {
				return false;
			}
		}
	}

	return true;
}

// wp-includes/formatting.php (WP 6.8.1)
function _wp_specialchars( $text, $quote_style = ENT_NOQUOTES, $charset = false, $double_encode = false ) {
	$text = (string) $text;

	if ( 0 === strlen( $text ) ) {
		return '';
	}

	// Don't bother if there are no specialchars - saves some processing.
	if ( ! preg_match( '/[&<>"\']/', $text ) ) {
		return $text;
	}

	// Account for the previous behavior of the function when the $quote_style is not an accepted value.
	if ( empty( $quote_style ) ) {
		$quote_style = ENT_NOQUOTES;
	} elseif ( ENT_XML1 === $quote_style ) {
		$quote_style = ENT_QUOTES | ENT_XML1;
	} elseif ( ! in_array( $quote_style, array( ENT_NOQUOTES, ENT_COMPAT, ENT_QUOTES, 'single', 'double' ), true ) ) {
		$quote_style = ENT_QUOTES;
	}

	$charset = _canonical_charset( $charset ? $charset : WPCOPY__OPTION_BLOG_CHARSET );

	$_quote_style = $quote_style;

	if ( 'double' === $quote_style ) {
		$quote_style  = ENT_COMPAT;
		$_quote_style = ENT_COMPAT;
	} elseif ( 'single' === $quote_style ) {
		$quote_style = ENT_NOQUOTES;
	}

	if ( ! $double_encode ) {
		/*
		 * Guarantee every &entity; is valid, convert &garbage; into &amp;garbage;
		 * This is required for PHP < 5.4.0 because ENT_HTML401 flag is unavailable.
		 */
		$text = wp_kses_normalize_entities( $text, ( $quote_style & ENT_XML1 ) ? 'xml' : 'html' );
	}

	$text = htmlspecialchars( $text, $quote_style, $charset, $double_encode );

	// Back-compat.
	if ( 'single' === $_quote_style ) {
		$text = str_replace( "'", '&#039;', $text );
	}

	return $text;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_specialchars_decode( $text, $quote_style = ENT_NOQUOTES ) {
	$text = (string) $text;

	if ( 0 === strlen( $text ) ) {
		return '';
	}

	// Don't bother if there are no entities - saves a lot of processing.
	if ( ! str_contains( $text, '&' ) ) {
		return $text;
	}

	// Match the previous behavior of _wp_specialchars() when the $quote_style is not an accepted value.
	if ( empty( $quote_style ) ) {
		$quote_style = ENT_NOQUOTES;
	} elseif ( ! in_array( $quote_style, array( 0, 2, 3, 'single', 'double' ), true ) ) {
		$quote_style = ENT_QUOTES;
	}

	// More complete than get_html_translation_table( HTML_SPECIALCHARS ).
	$single      = array(
		'&#039;' => '\'',
		'&#x27;' => '\'',
	);
	$single_preg = array(
		'/&#0*39;/'   => '&#039;',
		'/&#x0*27;/i' => '&#x27;',
	);
	$double      = array(
		'&quot;' => '"',
		'&#034;' => '"',
		'&#x22;' => '"',
	);
	$double_preg = array(
		'/&#0*34;/'   => '&#034;',
		'/&#x0*22;/i' => '&#x22;',
	);
	$others      = array(
		'&lt;'   => '<',
		'&#060;' => '<',
		'&gt;'   => '>',
		'&#062;' => '>',
		'&amp;'  => '&',
		'&#038;' => '&',
		'&#x26;' => '&',
	);
	$others_preg = array(
		'/&#0*60;/'   => '&#060;',
		'/&#0*62;/'   => '&#062;',
		'/&#0*38;/'   => '&#038;',
		'/&#x0*26;/i' => '&#x26;',
	);

	if ( ENT_QUOTES === $quote_style ) {
		$translation      = array_merge( $single, $double, $others );
		$translation_preg = array_merge( $single_preg, $double_preg, $others_preg );
	} elseif ( ENT_COMPAT === $quote_style || 'double' === $quote_style ) {
		$translation      = array_merge( $double, $others );
		$translation_preg = array_merge( $double_preg, $others_preg );
	} elseif ( 'single' === $quote_style ) {
		$translation      = array_merge( $single, $others );
		$translation_preg = array_merge( $single_preg, $others_preg );
	} elseif ( ENT_NOQUOTES === $quote_style ) {
		$translation      = $others;
		$translation_preg = $others_preg;
	}

	// Remove zero padding on numeric entities.
	$text = preg_replace( array_keys( $translation_preg ), array_values( $translation_preg ), $text );

	// Replace characters according to translation table.
	return strtr( $text, $translation );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_check_invalid_utf8( $text, $strip = false ) {
	$text = (string) $text;

	if ( 0 === strlen( $text ) ) {
		return '';
	}

	// Store the site charset as a static to avoid multiple calls to get_option().
	static $is_utf8 = null;
	if ( ! isset( $is_utf8 ) ) {
		$is_utf8 = is_utf8_charset();
	}
	if ( ! $is_utf8 ) {
		return $text;
	}

	// Check for support for utf8 in the installed PCRE library once and store the result in a static.
	static $utf8_pcre = null;
	if ( ! isset( $utf8_pcre ) ) {
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$utf8_pcre = @preg_match( '/^./u', 'a' );
	}
	// We can't demand utf8 in the PCRE installation, so just return the string in those cases.
	if ( ! $utf8_pcre ) {
		return $text;
	}

	// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- preg_match fails when it encounters invalid UTF8 in $text.
	if ( 1 === @preg_match( '/^./us', $text ) ) {
		return $text;
	}

	// Attempt to strip the bad chars if requested (not recommended).
	if ( $strip && function_exists( 'iconv' ) ) {
		return iconv( 'utf-8', 'utf-8', $text );
	}

	return '';
}

// wp-includes/formatting.php (WP 6.8.1)
function utf8_uri_encode( $utf8_string, $length = 0, $encode_ascii_characters = false ) {
	$unicode        = '';
	$values         = array();
	$num_octets     = 1;
	$unicode_length = 0;

	mbstring_binary_safe_encoding();
	$string_length = strlen( $utf8_string );
	reset_mbstring_encoding();

	for ( $i = 0; $i < $string_length; $i++ ) {

		$value = ord( $utf8_string[ $i ] );

		if ( $value < 128 ) {
			$char                = chr( $value );
			$encoded_char        = $encode_ascii_characters ? rawurlencode( $char ) : $char;
			$encoded_char_length = strlen( $encoded_char );
			if ( $length && ( $unicode_length + $encoded_char_length ) > $length ) {
				break;
			}
			$unicode        .= $encoded_char;
			$unicode_length += $encoded_char_length;
		} else {
			if ( count( $values ) === 0 ) {
				if ( $value < 224 ) {
					$num_octets = 2;
				} elseif ( $value < 240 ) {
					$num_octets = 3;
				} else {
					$num_octets = 4;
				}
			}

			$values[] = $value;

			if ( $length && ( $unicode_length + ( $num_octets * 3 ) ) > $length ) {
				break;
			}
			if ( count( $values ) === $num_octets ) {
				for ( $j = 0; $j < $num_octets; $j++ ) {
					$unicode .= '%' . dechex( $values[ $j ] );
				}

				$unicode_length += $num_octets * 3;

				$values     = array();
				$num_octets = 1;
			}
		}
	}

	return $unicode;
}

// wp-includes/formatting.php (WP 6.8.1)
function remove_accents( $text, $locale = '' ) {
	if ( ! preg_match( '/[\x80-\xff]/', $text ) ) {
		return $text;
	}

	if ( seems_utf8( $text ) ) {

		/*
		 * Unicode sequence normalization from NFD (Normalization Form Decomposed)
		 * to NFC (Normalization Form [Pre]Composed), the encoding used in this function.
		 */
		if ( function_exists( 'normalizer_is_normalized' )
			&& function_exists( 'normalizer_normalize' )
		) {
			if ( ! normalizer_is_normalized( $text ) ) {
				$text = normalizer_normalize( $text );
			}
		}

		$chars = array(
			// Decompositions for Latin-1 Supplement.
			'ª' => 'a',
			'º' => 'o',
			'À' => 'A',
			'Á' => 'A',
			'Â' => 'A',
			'Ã' => 'A',
			'Ä' => 'A',
			'Å' => 'A',
			'Æ' => 'AE',
			'Ç' => 'C',
			'È' => 'E',
			'É' => 'E',
			'Ê' => 'E',
			'Ë' => 'E',
			'Ì' => 'I',
			'Í' => 'I',
			'Î' => 'I',
			'Ï' => 'I',
			'Ð' => 'D',
			'Ñ' => 'N',
			'Ò' => 'O',
			'Ó' => 'O',
			'Ô' => 'O',
			'Õ' => 'O',
			'Ö' => 'O',
			'Ù' => 'U',
			'Ú' => 'U',
			'Û' => 'U',
			'Ü' => 'U',
			'Ý' => 'Y',
			'Þ' => 'TH',
			'ß' => 's',
			'à' => 'a',
			'á' => 'a',
			'â' => 'a',
			'ã' => 'a',
			'ä' => 'a',
			'å' => 'a',
			'æ' => 'ae',
			'ç' => 'c',
			'è' => 'e',
			'é' => 'e',
			'ê' => 'e',
			'ë' => 'e',
			'ì' => 'i',
			'í' => 'i',
			'î' => 'i',
			'ï' => 'i',
			'ð' => 'd',
			'ñ' => 'n',
			'ò' => 'o',
			'ó' => 'o',
			'ô' => 'o',
			'õ' => 'o',
			'ö' => 'o',
			'ø' => 'o',
			'ù' => 'u',
			'ú' => 'u',
			'û' => 'u',
			'ü' => 'u',
			'ý' => 'y',
			'þ' => 'th',
			'ÿ' => 'y',
			'Ø' => 'O',
			// Decompositions for Latin Extended-A.
			'Ā' => 'A',
			'ā' => 'a',
			'Ă' => 'A',
			'ă' => 'a',
			'Ą' => 'A',
			'ą' => 'a',
			'Ć' => 'C',
			'ć' => 'c',
			'Ĉ' => 'C',
			'ĉ' => 'c',
			'Ċ' => 'C',
			'ċ' => 'c',
			'Č' => 'C',
			'č' => 'c',
			'Ď' => 'D',
			'ď' => 'd',
			'Đ' => 'D',
			'đ' => 'd',
			'Ē' => 'E',
			'ē' => 'e',
			'Ĕ' => 'E',
			'ĕ' => 'e',
			'Ė' => 'E',
			'ė' => 'e',
			'Ę' => 'E',
			'ę' => 'e',
			'Ě' => 'E',
			'ě' => 'e',
			'Ĝ' => 'G',
			'ĝ' => 'g',
			'Ğ' => 'G',
			'ğ' => 'g',
			'Ġ' => 'G',
			'ġ' => 'g',
			'Ģ' => 'G',
			'ģ' => 'g',
			'Ĥ' => 'H',
			'ĥ' => 'h',
			'Ħ' => 'H',
			'ħ' => 'h',
			'Ĩ' => 'I',
			'ĩ' => 'i',
			'Ī' => 'I',
			'ī' => 'i',
			'Ĭ' => 'I',
			'ĭ' => 'i',
			'Į' => 'I',
			'į' => 'i',
			'İ' => 'I',
			'ı' => 'i',
			'Ĳ' => 'IJ',
			'ĳ' => 'ij',
			'Ĵ' => 'J',
			'ĵ' => 'j',
			'Ķ' => 'K',
			'ķ' => 'k',
			'ĸ' => 'k',
			'Ĺ' => 'L',
			'ĺ' => 'l',
			'Ļ' => 'L',
			'ļ' => 'l',
			'Ľ' => 'L',
			'ľ' => 'l',
			'Ŀ' => 'L',
			'ŀ' => 'l',
			'Ł' => 'L',
			'ł' => 'l',
			'Ń' => 'N',
			'ń' => 'n',
			'Ņ' => 'N',
			'ņ' => 'n',
			'Ň' => 'N',
			'ň' => 'n',
			'ŉ' => 'n',
			'Ŋ' => 'N',
			'ŋ' => 'n',
			'Ō' => 'O',
			'ō' => 'o',
			'Ŏ' => 'O',
			'ŏ' => 'o',
			'Ő' => 'O',
			'ő' => 'o',
			'Œ' => 'OE',
			'œ' => 'oe',
			'Ŕ' => 'R',
			'ŕ' => 'r',
			'Ŗ' => 'R',
			'ŗ' => 'r',
			'Ř' => 'R',
			'ř' => 'r',
			'Ś' => 'S',
			'ś' => 's',
			'Ŝ' => 'S',
			'ŝ' => 's',
			'Ş' => 'S',
			'ş' => 's',
			'Š' => 'S',
			'š' => 's',
			'Ţ' => 'T',
			'ţ' => 't',
			'Ť' => 'T',
			'ť' => 't',
			'Ŧ' => 'T',
			'ŧ' => 't',
			'Ũ' => 'U',
			'ũ' => 'u',
			'Ū' => 'U',
			'ū' => 'u',
			'Ŭ' => 'U',
			'ŭ' => 'u',
			'Ů' => 'U',
			'ů' => 'u',
			'Ű' => 'U',
			'ű' => 'u',
			'Ų' => 'U',
			'ų' => 'u',
			'Ŵ' => 'W',
			'ŵ' => 'w',
			'Ŷ' => 'Y',
			'ŷ' => 'y',
			'Ÿ' => 'Y',
			'Ź' => 'Z',
			'ź' => 'z',
			'Ż' => 'Z',
			'ż' => 'z',
			'Ž' => 'Z',
			'ž' => 'z',
			'ſ' => 's',
			// Decompositions for Latin Extended-B.
			'Ə' => 'E',
			'ǝ' => 'e',
			'Ș' => 'S',
			'ș' => 's',
			'Ț' => 'T',
			'ț' => 't',
			// Euro sign.
			'€' => 'E',
			// GBP (Pound) sign.
			'£' => '',
			// Vowels with diacritic (Vietnamese). Unmarked.
			'Ơ' => 'O',
			'ơ' => 'o',
			'Ư' => 'U',
			'ư' => 'u',
			// Grave accent.
			'Ầ' => 'A',
			'ầ' => 'a',
			'Ằ' => 'A',
			'ằ' => 'a',
			'Ề' => 'E',
			'ề' => 'e',
			'Ồ' => 'O',
			'ồ' => 'o',
			'Ờ' => 'O',
			'ờ' => 'o',
			'Ừ' => 'U',
			'ừ' => 'u',
			'Ỳ' => 'Y',
			'ỳ' => 'y',
			// Hook.
			'Ả' => 'A',
			'ả' => 'a',
			'Ẩ' => 'A',
			'ẩ' => 'a',
			'Ẳ' => 'A',
			'ẳ' => 'a',
			'Ẻ' => 'E',
			'ẻ' => 'e',
			'Ể' => 'E',
			'ể' => 'e',
			'Ỉ' => 'I',
			'ỉ' => 'i',
			'Ỏ' => 'O',
			'ỏ' => 'o',
			'Ổ' => 'O',
			'ổ' => 'o',
			'Ở' => 'O',
			'ở' => 'o',
			'Ủ' => 'U',
			'ủ' => 'u',
			'Ử' => 'U',
			'ử' => 'u',
			'Ỷ' => 'Y',
			'ỷ' => 'y',
			// Tilde.
			'Ẫ' => 'A',
			'ẫ' => 'a',
			'Ẵ' => 'A',
			'ẵ' => 'a',
			'Ẽ' => 'E',
			'ẽ' => 'e',
			'Ễ' => 'E',
			'ễ' => 'e',
			'Ỗ' => 'O',
			'ỗ' => 'o',
			'Ỡ' => 'O',
			'ỡ' => 'o',
			'Ữ' => 'U',
			'ữ' => 'u',
			'Ỹ' => 'Y',
			'ỹ' => 'y',
			// Acute accent.
			'Ấ' => 'A',
			'ấ' => 'a',
			'Ắ' => 'A',
			'ắ' => 'a',
			'Ế' => 'E',
			'ế' => 'e',
			'Ố' => 'O',
			'ố' => 'o',
			'Ớ' => 'O',
			'ớ' => 'o',
			'Ứ' => 'U',
			'ứ' => 'u',
			// Dot below.
			'Ạ' => 'A',
			'ạ' => 'a',
			'Ậ' => 'A',
			'ậ' => 'a',
			'Ặ' => 'A',
			'ặ' => 'a',
			'Ẹ' => 'E',
			'ẹ' => 'e',
			'Ệ' => 'E',
			'ệ' => 'e',
			'Ị' => 'I',
			'ị' => 'i',
			'Ọ' => 'O',
			'ọ' => 'o',
			'Ộ' => 'O',
			'ộ' => 'o',
			'Ợ' => 'O',
			'ợ' => 'o',
			'Ụ' => 'U',
			'ụ' => 'u',
			'Ự' => 'U',
			'ự' => 'u',
			'Ỵ' => 'Y',
			'ỵ' => 'y',
			// Vowels with diacritic (Chinese, Hanyu Pinyin).
			'ɑ' => 'a',
			// Macron.
			'Ǖ' => 'U',
			'ǖ' => 'u',
			// Acute accent.
			'Ǘ' => 'U',
			'ǘ' => 'u',
			// Caron.
			'Ǎ' => 'A',
			'ǎ' => 'a',
			'Ǐ' => 'I',
			'ǐ' => 'i',
			'Ǒ' => 'O',
			'ǒ' => 'o',
			'Ǔ' => 'U',
			'ǔ' => 'u',
			'Ǚ' => 'U',
			'ǚ' => 'u',
			// Grave accent.
			'Ǜ' => 'U',
			'ǜ' => 'u',
		);

		// Used for locale-specific rules.
		if ( empty( $locale ) ) {
			$locale = get_locale();
		}

		/*
		 * German has various locales (de_DE, de_CH, de_AT, ...) with formal and informal variants.
		 * There is no 3-letter locale like 'def', so checking for 'de' instead of 'de_' is safe,
		 * since 'de' itself would be a valid locale too.
		 */
		if ( str_starts_with( $locale, 'de' ) ) {
			$chars['Ä'] = 'Ae';
			$chars['ä'] = 'ae';
			$chars['Ö'] = 'Oe';
			$chars['ö'] = 'oe';
			$chars['Ü'] = 'Ue';
			$chars['ü'] = 'ue';
			$chars['ß'] = 'ss';
		} elseif ( 'da_DK' === $locale ) {
			$chars['Æ'] = 'Ae';
			$chars['æ'] = 'ae';
			$chars['Ø'] = 'Oe';
			$chars['ø'] = 'oe';
			$chars['Å'] = 'Aa';
			$chars['å'] = 'aa';
		} elseif ( 'ca' === $locale ) {
			$chars['l·l'] = 'll';
		} elseif ( 'sr_RS' === $locale || 'bs_BA' === $locale ) {
			$chars['Đ'] = 'DJ';
			$chars['đ'] = 'dj';
		}

		$text = strtr( $text, $chars );
	} else {
		$chars = array();
		// Assume ISO-8859-1 if not UTF-8.
		$chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
			. "\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
			. "\xc3\xc4\xc5\xc7\xc8\xc9\xca"
			. "\xcb\xcc\xcd\xce\xcf\xd1\xd2"
			. "\xd3\xd4\xd5\xd6\xd8\xd9\xda"
			. "\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
			. "\xe4\xe5\xe7\xe8\xe9\xea\xeb"
			. "\xec\xed\xee\xef\xf1\xf2\xf3"
			. "\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
			. "\xfc\xfd\xff";

		$chars['out'] = 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy';

		$text                = strtr( $text, $chars['in'], $chars['out'] );
		$double_chars        = array();
		$double_chars['in']  = array( "\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe" );
		$double_chars['out'] = array( 'OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th' );
		$text                = str_replace( $double_chars['in'], $double_chars['out'], $text );
	}

	return $text;
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_file_name( $filename ) {
	$filename_raw = $filename;
	$filename     = remove_accents( $filename );

	$special_chars = array( '?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', '’', '«', '»', '”', '“', chr( 0 ) );

	// Check for support for utf8 in the installed PCRE library once and store the result in a static.
	static $utf8_pcre = null;
	if ( ! isset( $utf8_pcre ) ) {
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$utf8_pcre = @preg_match( '/^./u', 'a' );
	}

	if ( ! seems_utf8( $filename ) ) {
		$_ext     = pathinfo( $filename, PATHINFO_EXTENSION );
		$_name    = pathinfo( $filename, PATHINFO_FILENAME );
		$filename = sanitize_title_with_dashes( $_name ) . '.' . $_ext;
	}

	if ( $utf8_pcre ) {
		$filename = preg_replace( "#\x{00a0}#siu", ' ', $filename );
	}

	/**
	 * Filters the list of characters to remove from a filename.
	 *
	 * @since 2.8.0
	 *
	 * @param string[] $special_chars Array of characters to remove.
	 * @param string   $filename_raw  The original filename to be sanitized.
	 */
	$special_chars = apply_filters( 'sanitize_file_name_chars', $special_chars, $filename_raw );

	$filename = str_replace( $special_chars, '', $filename );
	$filename = str_replace( array( '%20', '+' ), '-', $filename );
	$filename = preg_replace( '/\.{2,}/', '.', $filename );
	$filename = preg_replace( '/[\r\n\t -]+/', '-', $filename );
	$filename = trim( $filename, '.-_' );

	if ( ! str_contains( $filename, '.' ) ) {
		$mime_types = wp_get_mime_types();
		$filetype   = wp_check_filetype( 'test.' . $filename, $mime_types );
		if ( $filetype['ext'] === $filename ) {
			$filename = 'unnamed-file.' . $filetype['ext'];
		}
	}

	// Split the filename into a base and extension[s].
	$parts = explode( '.', $filename );

	// Return if only one extension.
	if ( count( $parts ) <= 2 ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'sanitize_file_name', $filename, $filename_raw );
	}

	// Process multiple extensions.
	$filename  = array_shift( $parts );
	$extension = array_pop( $parts );
	$mimes     = get_allowed_mime_types();

	/*
	 * Loop over any intermediate extensions. Postfix them with a trailing underscore
	 * if they are a 2 - 5 character long alpha string not in the allowed extension list.
	 */
	foreach ( (array) $parts as $part ) {
		$filename .= '.' . $part;

		if ( preg_match( '/^[a-zA-Z]{2,5}\d?$/', $part ) ) {
			$allowed = false;
			foreach ( $mimes as $ext_preg => $mime_match ) {
				$ext_preg = '!^(' . $ext_preg . ')$!i';
				if ( preg_match( $ext_preg, $part ) ) {
					$allowed = true;
					break;
				}
			}
			if ( ! $allowed ) {
				$filename .= '_';
			}
		}
	}

	$filename .= '.' . $extension;

	/**
	 * Filters a sanitized filename string.
	 *
	 * @since 2.8.0
	 *
	 * @param string $filename     Sanitized filename.
	 * @param string $filename_raw The filename prior to sanitization.
	 */
	return apply_filters( 'sanitize_file_name', $filename, $filename_raw );
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_user( $username, $strict = false ) {
	$raw_username = $username;
	$username     = wp_strip_all_tags( $username );
	$username     = remove_accents( $username );
	// Remove percent-encoded characters.
	$username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
	// Remove HTML entities.
	$username = preg_replace( '/&.+?;/', '', $username );

	// If strict, reduce to ASCII for max portability.
	if ( $strict ) {
		$username = preg_replace( '|[^a-z0-9 _.\-@]|i', '', $username );
	}

	$username = trim( $username );
	// Consolidate contiguous whitespace.
	$username = preg_replace( '|\s+|', ' ', $username );

	/**
	 * Filters a sanitized username string.
	 *
	 * @since 2.0.1
	 *
	 * @param string $username     Sanitized username.
	 * @param string $raw_username The username prior to sanitization.
	 * @param bool   $strict       Whether to limit the sanitization to specific characters.
	 */
	return apply_filters( 'sanitize_user', $username, $raw_username, $strict );
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_key( $key ) {
	$sanitized_key = '';

	if ( is_scalar( $key ) ) {
		$sanitized_key = strtolower( $key );
		$sanitized_key = preg_replace( '/[^a-z0-9_\-]/', '', $sanitized_key );
	}

	/**
	 * Filters a sanitized key string.
	 *
	 * @since 3.0.0
	 *
	 * @param string $sanitized_key Sanitized key.
	 * @param string $key           The key prior to sanitization.
	 */
	return apply_filters( 'sanitize_key', $sanitized_key, $key );
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_title( $title, $fallback_title = '', $context = 'save' ) {
	$raw_title = $title;

	if ( 'save' === $context ) {
		$title = remove_accents( $title );
	}

	/**
	 * Filters a sanitized title string.
	 *
	 * @since 1.2.0
	 *
	 * @param string $title     Sanitized title.
	 * @param string $raw_title The title prior to sanitization.
	 * @param string $context   The context for which the title is being sanitized.
	 */
	$title = apply_filters( 'sanitize_title', $title, $raw_title, $context );

	if ( '' === $title || false === $title ) {
		$title = $fallback_title;
	}

	return $title;
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_title_for_query( $title ) {
	return sanitize_title( $title, '', 'query' );
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_title_with_dashes( $title, $raw_title = '', $context = 'display' ) {
	$title = strip_tags( $title );
	// Preserve escaped octets.
	$title = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title );
	// Remove percent signs that are not part of an octet.
	$title = str_replace( '%', '', $title );
	// Restore octets.
	$title = preg_replace( '|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title );

	if ( seems_utf8( $title ) ) {
		if ( function_exists( 'mb_strtolower' ) ) {
			$title = mb_strtolower( $title, 'UTF-8' );
		}
		$title = utf8_uri_encode( $title, 200 );
	}

	$title = strtolower( $title );

	if ( 'save' === $context ) {
		// Convert &nbsp, &ndash, and &mdash to hyphens.
		$title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );
		// Convert &nbsp, &ndash, and &mdash HTML entities to hyphens.
		$title = str_replace( array( '&nbsp;', '&#160;', '&ndash;', '&#8211;', '&mdash;', '&#8212;' ), '-', $title );
		// Convert forward slash to hyphen.
		$title = str_replace( '/', '-', $title );

		// Strip these characters entirely.
		$title = str_replace(
			array(
				// Soft hyphens.
				'%c2%ad',
				// &iexcl and &iquest.
				'%c2%a1',
				'%c2%bf',
				// Angle quotes.
				'%c2%ab',
				'%c2%bb',
				'%e2%80%b9',
				'%e2%80%ba',
				// Curly quotes.
				'%e2%80%98',
				'%e2%80%99',
				'%e2%80%9c',
				'%e2%80%9d',
				'%e2%80%9a',
				'%e2%80%9b',
				'%e2%80%9e',
				'%e2%80%9f',
				// Bullet.
				'%e2%80%a2',
				// &copy, &reg, &deg, &hellip, and &trade.
				'%c2%a9',
				'%c2%ae',
				'%c2%b0',
				'%e2%80%a6',
				'%e2%84%a2',
				// Acute accents.
				'%c2%b4',
				'%cb%8a',
				'%cc%81',
				'%cd%81',
				// Grave accent, macron, caron.
				'%cc%80',
				'%cc%84',
				'%cc%8c',
				// Non-visible characters that display without a width.
				'%e2%80%8b', // Zero width space.
				'%e2%80%8c', // Zero width non-joiner.
				'%e2%80%8d', // Zero width joiner.
				'%e2%80%8e', // Left-to-right mark.
				'%e2%80%8f', // Right-to-left mark.
				'%e2%80%aa', // Left-to-right embedding.
				'%e2%80%ab', // Right-to-left embedding.
				'%e2%80%ac', // Pop directional formatting.
				'%e2%80%ad', // Left-to-right override.
				'%e2%80%ae', // Right-to-left override.
				'%ef%bb%bf', // Byte order mark.
				'%ef%bf%bc', // Object replacement character.
			),
			'',
			$title
		);

		// Convert non-visible characters that display with a width to hyphen.
		$title = str_replace(
			array(
				'%e2%80%80', // En quad.
				'%e2%80%81', // Em quad.
				'%e2%80%82', // En space.
				'%e2%80%83', // Em space.
				'%e2%80%84', // Three-per-em space.
				'%e2%80%85', // Four-per-em space.
				'%e2%80%86', // Six-per-em space.
				'%e2%80%87', // Figure space.
				'%e2%80%88', // Punctuation space.
				'%e2%80%89', // Thin space.
				'%e2%80%8a', // Hair space.
				'%e2%80%a8', // Line separator.
				'%e2%80%a9', // Paragraph separator.
				'%e2%80%af', // Narrow no-break space.
			),
			'-',
			$title
		);

		// Convert &times to 'x'.
		$title = str_replace( '%c3%97', 'x', $title );
	}

	// Remove HTML entities.
	$title = preg_replace( '/&.+?;/', '', $title );
	$title = str_replace( '.', '-', $title );

	$title = preg_replace( '/[^%a-z0-9 _-]/', '', $title );
	$title = preg_replace( '/\s+/', '-', $title );
	$title = preg_replace( '|-+|', '-', $title );
	$title = trim( $title, '-' );

	return $title;
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_sql_orderby( $orderby ) {
	if ( preg_match( '/^\s*(([a-z0-9_]+|`[a-z0-9_]+`)(\s+(ASC|DESC))?\s*(,\s*(?=[a-z0-9_`])|$))+$/i', $orderby ) || preg_match( '/^\s*RAND\(\s*\)\s*$/i', $orderby ) ) {
		return $orderby;
	}
	return false;
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_html_class( $classname, $fallback = '' ) {
	// Strip out any percent-encoded characters.
	$sanitized = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $classname );

	// Limit to A-Z, a-z, 0-9, '_', '-'.
	$sanitized = preg_replace( '/[^A-Za-z0-9_-]/', '', $sanitized );

	if ( '' === $sanitized && $fallback ) {
		return sanitize_html_class( $fallback );
	}
	/**
	 * Filters a sanitized HTML class string.
	 *
	 * @since 2.8.0
	 *
	 * @param string $sanitized The sanitized HTML class.
	 * @param string $classname HTML class before sanitization.
	 * @param string $fallback  The fallback string.
	 */
	return apply_filters( 'sanitize_html_class', $sanitized, $classname, $fallback );
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_locale_name( $locale_name ) {
	// Limit to A-Z, a-z, 0-9, '_', '-'.
	$sanitized = preg_replace( '/[^A-Za-z0-9_-]/', '', $locale_name );

	/**
	 * Filters a sanitized locale name string.
	 *
	 * @since 6.2.1
	 *
	 * @param string $sanitized   The sanitized locale name.
	 * @param string $locale_name The locale name before sanitization.
	 */
	return apply_filters( 'sanitize_locale_name', $sanitized, $locale_name );
}

// wp-includes/formatting.php (WP 6.8.1)
function convert_chars( $content, $deprecated = '' ) {
	if ( ! empty( $deprecated ) ) {
		_deprecated_argument( __FUNCTION__, '0.71' );
	}

	if ( str_contains( $content, '&' ) ) {
		$content = preg_replace( '/&([^#])(?![a-z1-4]{1,8};)/i', '&#038;$1', $content );
	}

	return $content;
}

// wp-includes/formatting.php (WP 6.8.1)
function convert_invalid_entities( $content ) {
	$wp_htmltranswinuni = array(
		'&#128;' => '&#8364;', // The Euro sign.
		'&#129;' => '',
		'&#130;' => '&#8218;', // These are Windows CP1252 specific characters.
		'&#131;' => '&#402;',  // They would look weird on non-Windows browsers.
		'&#132;' => '&#8222;',
		'&#133;' => '&#8230;',
		'&#134;' => '&#8224;',
		'&#135;' => '&#8225;',
		'&#136;' => '&#710;',
		'&#137;' => '&#8240;',
		'&#138;' => '&#352;',
		'&#139;' => '&#8249;',
		'&#140;' => '&#338;',
		'&#141;' => '',
		'&#142;' => '&#381;',
		'&#143;' => '',
		'&#144;' => '',
		'&#145;' => '&#8216;',
		'&#146;' => '&#8217;',
		'&#147;' => '&#8220;',
		'&#148;' => '&#8221;',
		'&#149;' => '&#8226;',
		'&#150;' => '&#8211;',
		'&#151;' => '&#8212;',
		'&#152;' => '&#732;',
		'&#153;' => '&#8482;',
		'&#154;' => '&#353;',
		'&#155;' => '&#8250;',
		'&#156;' => '&#339;',
		'&#157;' => '',
		'&#158;' => '&#382;',
		'&#159;' => '&#376;',
	);

	if ( str_contains( $content, '&#1' ) ) {
		$content = strtr( $content, $wp_htmltranswinuni );
	}

	return $content;
}

// wp-includes/formatting.php (WP 6.8.1)
function balanceTags( $text, $force = false ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	if ( $force || (int) get_option( 'use_balanceTags' ) === 1 ) {
		return force_balance_tags( $text );
	} else {
		return $text;
	}
}

// wp-includes/formatting.php (WP 6.8.1)
function force_balance_tags( $text ) {
	$tagstack  = array();
	$stacksize = 0;
	$tagqueue  = '';
	$newtext   = '';
	// Known single-entity/self-closing tags.
	$single_tags = array( 'area', 'base', 'basefont', 'br', 'col', 'command', 'embed', 'frame', 'hr', 'img', 'input', 'isindex', 'link', 'meta', 'param', 'source', 'track', 'wbr' );
	// Tags that can be immediately nested within themselves.
	$nestable_tags = array( 'article', 'aside', 'blockquote', 'details', 'div', 'figure', 'object', 'q', 'section', 'span' );

	// WP bug fix for comments - in case you REALLY meant to type '< !--'.
	$text = str_replace( '< !--', '<    !--', $text );
	// WP bug fix for LOVE <3 (and other situations with '<' before a number).
	$text = preg_replace( '#<([0-9]{1})#', '&lt;$1', $text );

	/**
	 * Matches supported tags.
	 *
	 * To get the pattern as a string without the comments paste into a PHP
	 * REPL like `php -a`.
	 *
	 * @see https://html.spec.whatwg.org/#elements-2
	 * @see https://html.spec.whatwg.org/multipage/custom-elements.html#valid-custom-element-name
	 *
	 * @example
	 * ~# php -a
	 * php > $s = [paste copied contents of expression below including parentheses];
	 * php > echo $s;
	 */
	$tag_pattern = (
		'#<' . // Start with an opening bracket.
		'(/?)' . // Group 1 - If it's a closing tag it'll have a leading slash.
		'(' . // Group 2 - Tag name.
			// Custom element tags have more lenient rules than HTML tag names.
			'(?:[a-z](?:[a-z0-9._]*)-(?:[a-z0-9._-]+)+)' .
				'|' .
			// Traditional tag rules approximate HTML tag names.
			'(?:[\w:]+)' .
		')' .
		'(?:' .
			// We either immediately close the tag with its '>' and have nothing here.
			'\s*' .
			'(/?)' . // Group 3 - "attributes" for empty tag.
				'|' .
			// Or we must start with space characters to separate the tag name from the attributes (or whitespace).
			'(\s+)' . // Group 4 - Pre-attribute whitespace.
			'([^>]*)' . // Group 5 - Attributes.
		')' .
		'>#' // End with a closing bracket.
	);

	while ( preg_match( $tag_pattern, $text, $regex ) ) {
		$full_match        = $regex[0];
		$has_leading_slash = ! empty( $regex[1] );
		$tag_name          = $regex[2];
		$tag               = strtolower( $tag_name );
		$is_single_tag     = in_array( $tag, $single_tags, true );
		$pre_attribute_ws  = isset( $regex[4] ) ? $regex[4] : '';
		$attributes        = trim( isset( $regex[5] ) ? $regex[5] : $regex[3] );
		$has_self_closer   = str_ends_with( $attributes, '/' );

		$newtext .= $tagqueue;

		$i = strpos( $text, $full_match );
		$l = strlen( $full_match );

		// Clear the shifter.
		$tagqueue = '';
		if ( $has_leading_slash ) { // End tag.
			// If too many closing tags.
			if ( $stacksize <= 0 ) {
				$tag = '';
				// Or close to be safe $tag = '/' . $tag.

				// If stacktop value = tag close value, then pop.
			} elseif ( $tagstack[ $stacksize - 1 ] === $tag ) { // Found closing tag.
				$tag = '</' . $tag . '>'; // Close tag.
				array_pop( $tagstack );
				--$stacksize;
			} else { // Closing tag not at top, search for it.
				for ( $j = $stacksize - 1; $j >= 0; $j-- ) {
					if ( $tagstack[ $j ] === $tag ) {
						// Add tag to tagqueue.
						for ( $k = $stacksize - 1; $k >= $j; $k-- ) {
							$tagqueue .= '</' . array_pop( $tagstack ) . '>';
							--$stacksize;
						}
						break;
					}
				}
				$tag = '';
			}
		} else { // Begin tag.
			if ( $has_self_closer ) {
				/*
				 * If it presents itself as a self-closing tag, but it isn't a known single-entity self-closing tag,
				 * then don't let it be treated as such and immediately close it with a closing tag.
				 * The tag will encapsulate no text as a result.
				 */
				if ( ! $is_single_tag ) {
					$attributes = trim( substr( $attributes, 0, -1 ) ) . "></$tag";
				}
			} elseif ( $is_single_tag ) {
				// Else if it's a known single-entity tag but it doesn't close itself, do so.
				$pre_attribute_ws = ' ';
				$attributes      .= '/';
			} else {
				/*
				 * It's not a single-entity tag.
				 * If the top of the stack is the same as the tag we want to push, close previous tag.
				 */
				if ( $stacksize > 0 && ! in_array( $tag, $nestable_tags, true ) && $tagstack[ $stacksize - 1 ] === $tag ) {
					$tagqueue = '</' . array_pop( $tagstack ) . '>';
					--$stacksize;
				}
				$stacksize = array_push( $tagstack, $tag );
			}

			// Attributes.
			if ( $has_self_closer && $is_single_tag ) {
				// We need some space - avoid <br/> and prefer <br />.
				$pre_attribute_ws = ' ';
			}

			$tag = '<' . $tag . $pre_attribute_ws . $attributes . '>';
			// If already queuing a close tag, then put this tag on too.
			if ( ! empty( $tagqueue ) ) {
				$tagqueue .= $tag;
				$tag       = '';
			}
		}
		$newtext .= substr( $text, 0, $i ) . $tag;
		$text     = substr( $text, $i + $l );
	}

	// Clear tag queue.
	$newtext .= $tagqueue;

	// Add remaining text.
	$newtext .= $text;

	while ( $x = array_pop( $tagstack ) ) {
		$newtext .= '</' . $x . '>'; // Add remaining tags to close.
	}

	// WP fix for the bug with HTML comments.
	$newtext = str_replace( '< !--', '<!--', $newtext );
	$newtext = str_replace( '<    !--', '< !--', $newtext );

	return $newtext;
}

// wp-includes/formatting.php (WP 6.8.1)
function format_to_edit( $content, $rich_text = false ) {
	/**
	 * Filters the text to be formatted for editing.
	 *
	 * @since 1.2.0
	 *
	 * @param string $content The text, prior to formatting for editing.
	 */
	$content = apply_filters( 'format_to_edit', $content );
	if ( ! $rich_text ) {
		$content = esc_textarea( $content );
	}
	return $content;
}

// wp-includes/formatting.php (WP 6.8.1)
function zeroise( $number, $threshold ) {
	return sprintf( '%0' . $threshold . 's', $number );
}

// wp-includes/formatting.php (WP 6.8.1)
function backslashit( $value ) {
	if ( isset( $value[0] ) && $value[0] >= '0' && $value[0] <= '9' ) {
		$value = '\\\\' . $value;
	}
	return addcslashes( $value, 'A..Za..z' );
}

// wp-includes/formatting.php (WP 6.8.1)
function trailingslashit( $value ) {
	return untrailingslashit( $value ) . '/';
}

// wp-includes/formatting.php (WP 6.8.1)
function untrailingslashit( $value ) {
	return rtrim( $value, '/\\' );
}

// wp-includes/formatting.php (WP 6.8.1)
function addslashes_gpc( $gpc ) {
	return wp_slash( $gpc );
}

// wp-includes/formatting.php (WP 6.8.1)
function stripslashes_deep( $value ) {
	return map_deep( $value, 'stripslashes_from_strings_only' );
}

// wp-includes/formatting.php (WP 6.8.1)
function stripslashes_from_strings_only( $value ) {
	return is_string( $value ) ? stripslashes( $value ) : $value;
}

// wp-includes/formatting.php (WP 6.8.1)
function urlencode_deep( $value ) {
	return map_deep( $value, 'urlencode' );
}

// wp-includes/formatting.php (WP 6.8.1)
function rawurlencode_deep( $value ) {
	return map_deep( $value, 'rawurlencode' );
}

// wp-includes/formatting.php (WP 6.8.1)
function urldecode_deep( $value ) {
	return map_deep( $value, 'urldecode' );
}

// wp-includes/formatting.php (WP 6.8.1)
function antispambot( $email_address, $hex_encoding = 0 ) {
	$email_no_spam_address = '';

	for ( $i = 0, $len = strlen( $email_address ); $i < $len; $i++ ) {
		$j = rand( 0, 1 + $hex_encoding );

		if ( 0 === $j ) {
			$email_no_spam_address .= '&#' . ord( $email_address[ $i ] ) . ';';
		} elseif ( 1 === $j ) {
			$email_no_spam_address .= $email_address[ $i ];
		} elseif ( 2 === $j ) {
			$email_no_spam_address .= '%' . zeroise( dechex( ord( $email_address[ $i ] ) ), 2 );
		}
	}

	return str_replace( '@', '&#64;', $email_no_spam_address );
}

// wp-includes/formatting.php (WP 6.8.1)
function _make_url_clickable_cb( $matches ) {
	$url = $matches[2];

	if ( ')' === $matches[3] && strpos( $url, '(' ) ) {
		/*
		 * If the trailing character is a closing parenthesis, and the URL has an opening parenthesis in it,
		 * add the closing parenthesis to the URL. Then we can let the parenthesis balancer do its thing below.
		 */
		$url   .= $matches[3];
		$suffix = '';
	} else {
		$suffix = $matches[3];
	}

	if ( isset( $matches[4] ) && ! empty( $matches[4] ) ) {
		$url .= $matches[4];
	}

	// Include parentheses in the URL only if paired.
	while ( substr_count( $url, '(' ) < substr_count( $url, ')' ) ) {
		$suffix = strrchr( $url, ')' ) . $suffix;
		$url    = substr( $url, 0, strrpos( $url, ')' ) );
	}

	$url = esc_url( $url );
	if ( empty( $url ) ) {
		return $matches[0];
	}

	$rel_attr = _make_clickable_rel_attr( $url );

	return $matches[1] . "<a href=\"{$url}\"{$rel_attr}>{$url}</a>" . $suffix;
}

// wp-includes/formatting.php (WP 6.8.1)
function _make_web_ftp_clickable_cb( $matches ) {
	$ret  = '';
	$dest = $matches[2];
	$dest = 'http://' . $dest;

	// Removed trailing [.,;:)] from URL.
	$last_char = substr( $dest, -1 );
	if ( in_array( $last_char, array( '.', ',', ';', ':', ')' ), true ) === true ) {
		$ret  = $last_char;
		$dest = substr( $dest, 0, strlen( $dest ) - 1 );
	}

	$dest = esc_url( $dest );
	if ( empty( $dest ) ) {
		return $matches[0];
	}

	$rel_attr = _make_clickable_rel_attr( $dest );

	return $matches[1] . "<a href=\"{$dest}\"{$rel_attr}>{$dest}</a>{$ret}";
}

// wp-includes/formatting.php (WP 6.8.1)
function _make_email_clickable_cb( $matches ) {
	$email = $matches[2] . '@' . $matches[3];

	return $matches[1] . "<a href=\"mailto:{$email}\">{$email}</a>";
}

// wp-includes/formatting.php (WP 6.8.1)
function _make_clickable_rel_attr( $url ) {
	$rel_parts        = array();
	$scheme           = strtolower( wp_parse_url( $url, PHP_URL_SCHEME ) );
	$nofollow_schemes = array_intersect( wp_allowed_protocols(), array( 'https', 'http' ) );

	// Apply "nofollow" to external links with qualifying URL schemes (mailto:, tel:, etc... shouldn't be followed).
	if ( ! wp_is_internal_link( $url ) && in_array( $scheme, $nofollow_schemes, true ) ) {
		$rel_parts[] = 'nofollow';
	}

	// Apply "ugc" when in comment context.
	if ( 'comment_text' === current_filter() ) {
		$rel_parts[] = 'ugc';
	}

	$rel = implode( ' ', $rel_parts );

	/**
	 * Filters the rel value that is added to URL matches converted to links.
	 *
	 * @since 5.3.0
	 *
	 * @param string $rel The rel value.
	 * @param string $url The matched URL being converted to a link tag.
	 */
	$rel = apply_filters( 'make_clickable_rel', $rel, $url );

	$rel_attr = $rel ? ' rel="' . esc_attr( $rel ) . '"' : '';

	return $rel_attr;
}

// wp-includes/formatting.php (WP 6.8.1)
function make_clickable( $text ) {
	$r               = '';
	$textarr         = preg_split( '/(<[^<>]+>)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE ); // Split out HTML tags.
	$nested_code_pre = 0; // Keep track of how many levels link is nested inside <pre> or <code>.
	foreach ( $textarr as $piece ) {

		if ( preg_match( '|^<code[\s>]|i', $piece )
			|| preg_match( '|^<pre[\s>]|i', $piece )
			|| preg_match( '|^<script[\s>]|i', $piece )
			|| preg_match( '|^<style[\s>]|i', $piece )
		) {
			++$nested_code_pre;
		} elseif ( $nested_code_pre
			&& ( '</code>' === strtolower( $piece )
				|| '</pre>' === strtolower( $piece )
				|| '</script>' === strtolower( $piece )
				|| '</style>' === strtolower( $piece )
			)
		) {
			--$nested_code_pre;
		}

		if ( $nested_code_pre
			|| empty( $piece )
			|| ( '<' === $piece[0] && ! preg_match( '|^<\s*[\w]{1,20}+://|', $piece ) )
		) {
			$r .= $piece;
			continue;
		}

		// Long strings might contain expensive edge cases...
		if ( 10000 < strlen( $piece ) ) {
			// ...break it up.
			foreach ( _split_str_by_whitespace( $piece, 2100 ) as $chunk ) { // 2100: Extra room for scheme and leading and trailing parentheses.
				if ( 2101 < strlen( $chunk ) ) {
					$r .= $chunk; // Too big, no whitespace: bail.
				} else {
					$r .= make_clickable( $chunk );
				}
			}
		} else {
			$ret = " $piece "; // Pad with whitespace to simplify the regexes.

			$url_clickable = '~
				([\\s(<.,;:!?])                                # 1: Leading whitespace, or punctuation.
				(                                              # 2: URL.
					[\\w]{1,20}+://                                # Scheme and hier-part prefix.
					(?=\S{1,2000}\s)                               # Limit to URLs less than about 2000 characters long.
					[\\w\\x80-\\xff#%\\~/@\\[\\]*(+=&$-]*+         # Non-punctuation URL character.
					(?:                                            # Unroll the Loop: Only allow punctuation URL character if followed by a non-punctuation URL character.
						[\'.,;:!?)]                                    # Punctuation URL character.
						[\\w\\x80-\\xff#%\\~/@\\[\\]*(+=&$-]++         # Non-punctuation URL character.
					)*
				)
				(\)?)                                          # 3: Trailing closing parenthesis (for parenthesis balancing post processing).
				(\\.\\w{2,6})?                                 # 4: Allowing file extensions (e.g., .jpg, .png).
			~xS';
			/*
			 * The regex is a non-anchored pattern and does not have a single fixed starting character.
			 * Tell PCRE to spend more time optimizing since, when used on a page load, it will probably be used several times.
			 */

			$ret = preg_replace_callback( $url_clickable, '_make_url_clickable_cb', $ret );

			$ret = preg_replace_callback( '#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]+)#is', '_make_web_ftp_clickable_cb', $ret );
			$ret = preg_replace_callback( '#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', '_make_email_clickable_cb', $ret );

			$ret = substr( $ret, 1, -1 ); // Remove our whitespace padding.
			$r  .= $ret;
		}
	}

	// Cleanup of accidental links within links.
	return preg_replace( '#(<a([ \r\n\t]+[^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i', '$1$3</a>', $r );
}

// wp-includes/formatting.php (WP 6.8.1)
function _split_str_by_whitespace( $text, $goal ) {
	$chunks = array();

	$string_nullspace = strtr( $text, "\r\n\t\v\f ", "\000\000\000\000\000\000" );

	while ( $goal < strlen( $string_nullspace ) ) {
		$pos = strrpos( substr( $string_nullspace, 0, $goal + 1 ), "\000" );

		if ( false === $pos ) {
			$pos = strpos( $string_nullspace, "\000", $goal + 1 );
			if ( false === $pos ) {
				break;
			}
		}

		$chunks[]         = substr( $text, 0, $pos + 1 );
		$text             = substr( $text, $pos + 1 );
		$string_nullspace = substr( $string_nullspace, $pos + 1 );
	}

	if ( $text ) {
		$chunks[] = $text;
	}

	return $chunks;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_rel_callback( $matches, $rel ) {
	$text = $matches[1];
	$atts = wp_kses_hair( $matches[1], wp_allowed_protocols() );

	if ( ! empty( $atts['href'] ) && wp_is_internal_link( $atts['href']['value'] ) ) {
		$rel = trim( str_replace( 'nofollow', '', $rel ) );
	}

	if ( ! empty( $atts['rel'] ) ) {
		$parts     = array_map( 'trim', explode( ' ', $atts['rel']['value'] ) );
		$rel_array = array_map( 'trim', explode( ' ', $rel ) );
		$parts     = array_unique( array_merge( $parts, $rel_array ) );
		$rel       = implode( ' ', $parts );
		unset( $atts['rel'] );

		$html = '';
		foreach ( $atts as $name => $value ) {
			if ( isset( $value['vless'] ) && 'y' === $value['vless'] ) {
				$html .= $name . ' ';
			} else {
				$html .= "{$name}=\"" . esc_attr( $value['value'] ) . '" ';
			}
		}
		$text = trim( $html );
	}

	$rel_attr = $rel ? ' rel="' . esc_attr( $rel ) . '"' : '';

	return "<a {$text}{$rel_attr}>";
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_rel_nofollow( $text ) {
	// This is a pre-save filter, so text is already escaped.
	$text = stripslashes( $text );
	$text = preg_replace_callback(
		'|<a (.+?)>|i',
		static function ( $matches ) {
			return wp_rel_callback( $matches, 'nofollow' );
		},
		$text
	);
	return wp_slash( $text );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_rel_nofollow_callback( $matches ) {
	return wp_rel_callback( $matches, 'nofollow' );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_rel_ugc( $text ) {
	// This is a pre-save filter, so text is already escaped.
	$text = stripslashes( $text );
	$text = preg_replace_callback(
		'|<a (.+?)>|i',
		static function ( $matches ) {
			return wp_rel_callback( $matches, 'nofollow ugc' );
		},
		$text
	);
	return wp_slash( $text );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_targeted_link_rel( $text ) {
	_deprecated_function( __FUNCTION__, '6.7.0' );

	// Don't run (more expensive) regex if no links with targets.
	if ( stripos( $text, 'target' ) === false || stripos( $text, '<a ' ) === false || is_serialized( $text ) ) {
		return $text;
	}

	$script_and_style_regex = '/<(script|style).*?<\/\\1>/si';

	preg_match_all( $script_and_style_regex, $text, $matches );
	$extra_parts = $matches[0];
	$html_parts  = preg_split( $script_and_style_regex, $text );

	foreach ( $html_parts as &$part ) {
		$part = preg_replace_callback( '|<a\s([^>]*target\s*=[^>]*)>|i', 'wp_targeted_link_rel_callback', $part );
	}

	$text = '';
	for ( $i = 0; $i < count( $html_parts ); $i++ ) {
		$text .= $html_parts[ $i ];
		if ( isset( $extra_parts[ $i ] ) ) {
			$text .= $extra_parts[ $i ];
		}
	}

	return $text;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_targeted_link_rel_callback( $matches ) {
	_deprecated_function( __FUNCTION__, '6.7.0' );

	$link_html          = $matches[1];
	$original_link_html = $link_html;

	// Consider the HTML escaped if there are no unescaped quotes.
	$is_escaped = ! preg_match( '/(^|[^\\\\])[\'"]/', $link_html );
	if ( $is_escaped ) {
		// Replace only the quotes so that they are parsable by wp_kses_hair(), leave the rest as is.
		$link_html = preg_replace( '/\\\\([\'"])/', '$1', $link_html );
	}

	$atts = wp_kses_hair( $link_html, wp_allowed_protocols() );

	/**
	 * Filters the rel values that are added to links with `target` attribute.
	 *
	 * @since 5.1.0
	 *
	 * @param string $rel       The rel values.
	 * @param string $link_html The matched content of the link tag including all HTML attributes.
	 */
	$rel = apply_filters( 'wp_targeted_link_rel', 'noopener', $link_html );

	// Return early if no rel values to be added or if no actual target attribute.
	if ( ! $rel || ! isset( $atts['target'] ) ) {
		return "<a $original_link_html>";
	}

	if ( isset( $atts['rel'] ) ) {
		$all_parts = preg_split( '/\s/', "{$atts['rel']['value']} $rel", -1, PREG_SPLIT_NO_EMPTY );
		$rel       = implode( ' ', array_unique( $all_parts ) );
	}

	$atts['rel']['whole'] = 'rel="' . esc_attr( $rel ) . '"';
	$link_html            = implode( ' ', array_column( $atts, 'whole' ) );

	if ( $is_escaped ) {
		$link_html = preg_replace( '/[\'"]/', '\\\\$0', $link_html );
	}

	return "<a $link_html>";
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_init_targeted_link_rel_filters() {
	_deprecated_function( __FUNCTION__, '6.7.0' );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_remove_targeted_link_rel_filters() {
	_deprecated_function( __FUNCTION__, '6.7.0' );
}

// wp-includes/formatting.php (WP 6.8.1)
function translate_smiley( $matches ) {
	global $wpsmiliestrans;

	if ( count( $matches ) === 0 ) {
		return '';
	}

	$smiley = trim( reset( $matches ) );
	$img    = $wpsmiliestrans[ $smiley ];

	$matches    = array();
	$ext        = preg_match( '/\.([^.]+)$/', $img, $matches ) ? strtolower( $matches[1] ) : false;
	$image_exts = array( 'jpg', 'jpeg', 'jpe', 'gif', 'png', 'webp', 'avif' );

	// Don't convert smilies that aren't images - they're probably emoji.
	if ( ! in_array( $ext, $image_exts, true ) ) {
		return $img;
	}

	/**
	 * Filters the Smiley image URL before it's used in the image element.
	 *
	 * @since 2.9.0
	 *
	 * @param string $smiley_url URL for the smiley image.
	 * @param string $img        Filename for the smiley image.
	 * @param string $site_url   Site URL, as returned by site_url().
	 */
	$src_url = apply_filters( 'smilies_src', includes_url( "images/smilies/$img" ), $img, site_url() );

	return sprintf( '<img src="%s" alt="%s" class="wp-smiley" style="height: 1em; max-height: 1em;" />', esc_url( $src_url ), esc_attr( $smiley ) );
}

// wp-includes/formatting.php (WP 6.8.1)
function convert_smilies( $text ) {
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

// wp-includes/formatting.php (WP 6.8.1)
function is_email( $email, $deprecated = false ) {
	if ( ! empty( $deprecated ) ) {
		_deprecated_argument( __FUNCTION__, '3.0.0' );
	}

	// Test for the minimum length the email can be.
	if ( strlen( $email ) < 6 ) {
		/**
		 * Filters whether an email address is valid.
		 *
		 * This filter is evaluated under several different contexts, such as 'email_too_short',
		 * 'email_no_at', 'local_invalid_chars', 'domain_period_sequence', 'domain_period_limits',
		 * 'domain_no_periods', 'sub_hyphen_limits', 'sub_invalid_chars', or no specific context.
		 *
		 * @since 2.8.0
		 *
		 * @param string|false $is_email The email address if successfully passed the is_email() checks, false otherwise.
		 * @param string       $email    The email address being checked.
		 * @param string       $context  Context under which the email was tested.
		 */
		return apply_filters( 'is_email', false, $email, 'email_too_short' );
	}

	// Test for an @ character after the first position.
	if ( strpos( $email, '@', 1 ) === false ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'is_email', false, $email, 'email_no_at' );
	}

	// Split out the local and domain parts.
	list( $local, $domain ) = explode( '@', $email, 2 );

	/*
	 * LOCAL PART
	 * Test for invalid characters.
	 */
	if ( ! preg_match( '/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local ) ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'is_email', false, $email, 'local_invalid_chars' );
	}

	/*
	 * DOMAIN PART
	 * Test for sequences of periods.
	 */
	if ( preg_match( '/\.{2,}/', $domain ) ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'is_email', false, $email, 'domain_period_sequence' );
	}

	// Test for leading and trailing periods and whitespace.
	if ( trim( $domain, " \t\n\r\0\x0B." ) !== $domain ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'is_email', false, $email, 'domain_period_limits' );
	}

	// Split the domain into subs.
	$subs = explode( '.', $domain );

	// Assume the domain will have at least two subs.
	if ( 2 > count( $subs ) ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'is_email', false, $email, 'domain_no_periods' );
	}

	// Loop through each sub.
	foreach ( $subs as $sub ) {
		// Test for leading and trailing hyphens and whitespace.
		if ( trim( $sub, " \t\n\r\0\x0B-" ) !== $sub ) {
			/** This filter is documented in wp-includes/formatting.php */
			return apply_filters( 'is_email', false, $email, 'sub_hyphen_limits' );
		}

		// Test for invalid characters.
		if ( ! preg_match( '/^[a-z0-9-]+$/i', $sub ) ) {
			/** This filter is documented in wp-includes/formatting.php */
			return apply_filters( 'is_email', false, $email, 'sub_invalid_chars' );
		}
	}

	// Congratulations, your email made it!
	/** This filter is documented in wp-includes/formatting.php */
	return apply_filters( 'is_email', $email, $email, null );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_iso_descrambler( $subject ) {
	/* this may only work with iso-8859-1, I'm afraid */
	if ( ! preg_match( '#\=\?(.+)\?Q\?(.+)\?\=#i', $subject, $matches ) ) {
		return $subject;
	}

	$subject = str_replace( '_', ' ', $matches[2] );
	return preg_replace_callback( '#\=([0-9a-f]{2})#i', '_wp_iso_convert', $subject );
}

// wp-includes/formatting.php (WP 6.8.1)
function _wp_iso_convert( $matches ) {
	return chr( hexdec( strtolower( $matches[1] ) ) );
}

// wp-includes/formatting.php (WP 6.8.1)
function get_gmt_from_date( $date_string, $format = 'Y-m-d H:i:s' ) {
	$datetime = date_create( $date_string, wp_timezone() );

	if ( false === $datetime ) {
		return gmdate( $format, 0 );
	}

	return $datetime->setTimezone( new DateTimeZone( 'UTC' ) )->format( $format );
}

// wp-includes/formatting.php (WP 6.8.1)
function get_date_from_gmt( $date_string, $format = 'Y-m-d H:i:s' ) {
	$datetime = date_create( $date_string, new DateTimeZone( 'UTC' ) );

	if ( false === $datetime ) {
		return gmdate( $format, 0 );
	}

	return $datetime->setTimezone( wp_timezone() )->format( $format );
}

// wp-includes/formatting.php (WP 6.8.1)
function iso8601_timezone_to_offset( $timezone ) {
	// $timezone is either 'Z' or '[+|-]hhmm'.
	if ( 'Z' === $timezone ) {
		$offset = 0;
	} else {
		$sign    = ( str_starts_with( $timezone, '+' ) ) ? 1 : -1;
		$hours   = (int) substr( $timezone, 1, 2 );
		$minutes = (int) substr( $timezone, 3, 4 ) / 60;
		$offset  = $sign * HOUR_IN_SECONDS * ( $hours + $minutes );
	}
	return $offset;
}

// wp-includes/formatting.php (WP 6.8.1)
function iso8601_to_datetime( $date_string, $timezone = 'user' ) {
	$timezone    = strtolower( $timezone );
	$wp_timezone = wp_timezone();
	$datetime    = date_create( $date_string, $wp_timezone ); // Timezone is ignored if input has one.

	if ( false === $datetime ) {
		return false;
	}

	if ( 'gmt' === $timezone ) {
		return $datetime->setTimezone( new DateTimeZone( 'UTC' ) )->format( 'Y-m-d H:i:s' );
	}

	if ( 'user' === $timezone ) {
		return $datetime->setTimezone( $wp_timezone )->format( 'Y-m-d H:i:s' );
	}

	return false;
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_email( $email ) {
	// Test for the minimum length the email can be.
	if ( strlen( $email ) < 6 ) {
		/**
		 * Filters a sanitized email address.
		 *
		 * This filter is evaluated under several contexts, including 'email_too_short',
		 * 'email_no_at', 'local_invalid_chars', 'domain_period_sequence', 'domain_period_limits',
		 * 'domain_no_periods', 'domain_no_valid_subs', or no context.
		 *
		 * @since 2.8.0
		 *
		 * @param string $sanitized_email The sanitized email address.
		 * @param string $email           The email address, as provided to sanitize_email().
		 * @param string|null $message    A message to pass to the user. null if email is sanitized.
		 */
		return apply_filters( 'sanitize_email', '', $email, 'email_too_short' );
	}

	// Test for an @ character after the first position.
	if ( strpos( $email, '@', 1 ) === false ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'sanitize_email', '', $email, 'email_no_at' );
	}

	// Split out the local and domain parts.
	list( $local, $domain ) = explode( '@', $email, 2 );

	/*
	 * LOCAL PART
	 * Test for invalid characters.
	 */
	$local = preg_replace( '/[^a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]/', '', $local );
	if ( '' === $local ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'sanitize_email', '', $email, 'local_invalid_chars' );
	}

	/*
	 * DOMAIN PART
	 * Test for sequences of periods.
	 */
	$domain = preg_replace( '/\.{2,}/', '', $domain );
	if ( '' === $domain ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'sanitize_email', '', $email, 'domain_period_sequence' );
	}

	// Test for leading and trailing periods and whitespace.
	$domain = trim( $domain, " \t\n\r\0\x0B." );
	if ( '' === $domain ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'sanitize_email', '', $email, 'domain_period_limits' );
	}

	// Split the domain into subs.
	$subs = explode( '.', $domain );

	// Assume the domain will have at least two subs.
	if ( 2 > count( $subs ) ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'sanitize_email', '', $email, 'domain_no_periods' );
	}

	// Create an array that will contain valid subs.
	$new_subs = array();

	// Loop through each sub.
	foreach ( $subs as $sub ) {
		// Test for leading and trailing hyphens.
		$sub = trim( $sub, " \t\n\r\0\x0B-" );

		// Test for invalid characters.
		$sub = preg_replace( '/[^a-z0-9-]+/i', '', $sub );

		// If there's anything left, add it to the valid subs.
		if ( '' !== $sub ) {
			$new_subs[] = $sub;
		}
	}

	// If there aren't 2 or more valid subs.
	if ( 2 > count( $new_subs ) ) {
		/** This filter is documented in wp-includes/formatting.php */
		return apply_filters( 'sanitize_email', '', $email, 'domain_no_valid_subs' );
	}

	// Join valid subs into the new domain.
	$domain = implode( '.', $new_subs );

	// Put the email back together.
	$sanitized_email = $local . '@' . $domain;

	// Congratulations, your email made it!
	/** This filter is documented in wp-includes/formatting.php */
	return apply_filters( 'sanitize_email', $sanitized_email, $email, null );
}

// wp-includes/formatting.php (WP 6.8.1)
function human_time_diff( $from, $to = 0 ) {
	if ( empty( $to ) ) {
		$to = time();
	}

	$diff = (int) abs( $to - $from );

	if ( $diff < MINUTE_IN_SECONDS ) {
		$secs = $diff;
		if ( $secs <= 1 ) {
			$secs = 1;
		}
		/* translators: Time difference between two dates, in seconds. %s: Number of seconds. */
		$since = sprintf( _n( '%s second', '%s seconds', $secs ), $secs );
	} elseif ( $diff < HOUR_IN_SECONDS && $diff >= MINUTE_IN_SECONDS ) {
		$mins = round( $diff / MINUTE_IN_SECONDS );
		if ( $mins <= 1 ) {
			$mins = 1;
		}
		/* translators: Time difference between two dates, in minutes. %s: Number of minutes. */
		$since = sprintf( _n( '%s minute', '%s minutes', $mins ), $mins );
	} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		if ( $hours <= 1 ) {
			$hours = 1;
		}
		/* translators: Time difference between two dates, in hours. %s: Number of hours. */
		$since = sprintf( _n( '%s hour', '%s hours', $hours ), $hours );
	} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
		$days = round( $diff / DAY_IN_SECONDS );
		if ( $days <= 1 ) {
			$days = 1;
		}
		/* translators: Time difference between two dates, in days. %s: Number of days. */
		$since = sprintf( _n( '%s day', '%s days', $days ), $days );
	} elseif ( $diff < MONTH_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
		$weeks = round( $diff / WEEK_IN_SECONDS );
		if ( $weeks <= 1 ) {
			$weeks = 1;
		}
		/* translators: Time difference between two dates, in weeks. %s: Number of weeks. */
		$since = sprintf( _n( '%s week', '%s weeks', $weeks ), $weeks );
	} elseif ( $diff < YEAR_IN_SECONDS && $diff >= MONTH_IN_SECONDS ) {
		$months = round( $diff / MONTH_IN_SECONDS );
		if ( $months <= 1 ) {
			$months = 1;
		}
		/* translators: Time difference between two dates, in months. %s: Number of months. */
		$since = sprintf( _n( '%s month', '%s months', $months ), $months );
	} elseif ( $diff >= YEAR_IN_SECONDS ) {
		$years = round( $diff / YEAR_IN_SECONDS );
		if ( $years <= 1 ) {
			$years = 1;
		}
		/* translators: Time difference between two dates, in years. %s: Number of years. */
		$since = sprintf( _n( '%s year', '%s years', $years ), $years );
	}

	/**
	 * Filters the human-readable difference between two timestamps.
	 *
	 * @since 4.0.0
	 *
	 * @param string $since The difference in human-readable text.
	 * @param int    $diff  The difference in seconds.
	 * @param int    $from  Unix timestamp from which the difference begins.
	 * @param int    $to    Unix timestamp to end the time difference.
	 */
	return apply_filters( 'human_time_diff', $since, $diff, $from, $to );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_trim_excerpt( $text = '', $post = null ) {
	$raw_excerpt = $text;

	if ( '' === trim( $text ) ) {
		$post = get_post( $post );
		$text = get_the_content( '', false, $post );

		$text = strip_shortcodes( $text );
		$text = excerpt_remove_blocks( $text );
		$text = excerpt_remove_footnotes( $text );

		/*
		 * Temporarily unhook wp_filter_content_tags() since any tags
		 * within the excerpt are stripped out. Modifying the tags here
		 * is wasteful and can lead to bugs in the image counting logic.
		 */
		$filter_image_removed = remove_filter( 'the_content', 'wp_filter_content_tags', 12 );

		/*
		 * Temporarily unhook do_blocks() since excerpt_remove_blocks( $text )
		 * handles block rendering needed for excerpt.
		 */
		$filter_block_removed = remove_filter( 'the_content', 'do_blocks', 9 );

		/** This filter is documented in wp-includes/post-template.php */
		$text = apply_filters( 'the_content', $text );
		$text = str_replace( ']]>', ']]&gt;', $text );

		// Restore the original filter if removed.
		if ( $filter_block_removed ) {
			add_filter( 'the_content', 'do_blocks', 9 );
		}

		/*
		 * Only restore the filter callback if it was removed above. The logic
		 * to unhook and restore only applies on the default priority of 10,
		 * which is generally used for the filter callback in WordPress core.
		 */
		if ( $filter_image_removed ) {
			add_filter( 'the_content', 'wp_filter_content_tags', 12 );
		}

		/* translators: Maximum number of words used in a post excerpt. */
		$excerpt_length = (int) _x( '55', 'excerpt_length' );

		/**
		 * Filters the maximum number of words in a post excerpt.
		 *
		 * @since 2.7.0
		 *
		 * @param int $number The maximum number of words. Default 55.
		 */
		$excerpt_length = (int) apply_filters( 'excerpt_length', $excerpt_length );

		/**
		 * Filters the string in the "more" link displayed after a trimmed excerpt.
		 *
		 * @since 2.9.0
		 *
		 * @param string $more_string The string shown within the more link.
		 */
		$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
		$text         = wp_trim_words( $text, $excerpt_length, $excerpt_more );

	}

	/**
	 * Filters the trimmed excerpt string.
	 *
	 * @since 2.8.0
	 *
	 * @param string $text        The trimmed text.
	 * @param string $raw_excerpt The text prior to trimming.
	 */
	return apply_filters( 'wp_trim_excerpt', $text, $raw_excerpt );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_trim_words( $text, $num_words = 55, $more = null ) {
	if ( null === $more ) {
		$more = __( '&hellip;' );
	}

	$original_text = $text;
	$text          = wp_strip_all_tags( $text );
	$num_words     = (int) $num_words;

	if ( str_starts_with( wp_get_word_count_type(), 'characters' ) && preg_match( '/^utf\-?8$/i', WPCOPY__OPTION_BLOG_CHARSET ) ) {
		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
		preg_match_all( '/./u', $text, $words_array );
		$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
		$sep         = '';
	} else {
		$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
		$sep         = ' ';
	}

	if ( count( $words_array ) > $num_words ) {
		array_pop( $words_array );
		$text = implode( $sep, $words_array );
		$text = $text . $more;
	} else {
		$text = implode( $sep, $words_array );
	}

	/**
	 * Filters the text content after words have been trimmed.
	 *
	 * @since 3.3.0
	 *
	 * @param string $text          The trimmed text.
	 * @param int    $num_words     The number of words to trim the text to. Default 55.
	 * @param string $more          An optional string to append to the end of the trimmed text, e.g. &hellip;.
	 * @param string $original_text The text before it was trimmed.
	 */
	return apply_filters( 'wp_trim_words', $text, $num_words, $more, $original_text );
}

// wp-includes/formatting.php (WP 6.8.1)
function ent2ncr( $text ) {

	/**
	 * Filters text before named entities are converted into numbered entities.
	 *
	 * A non-null string must be returned for the filter to be evaluated.
	 *
	 * @since 3.3.0
	 *
	 * @param string|null $converted_text The text to be converted. Default null.
	 * @param string      $text           The text prior to entity conversion.
	 */
	$filtered = apply_filters( 'pre_ent2ncr', null, $text );
	if ( null !== $filtered ) {
		return $filtered;
	}

	$to_ncr = array(
		'&quot;'     => '&#34;',
		'&amp;'      => '&#38;',
		'&lt;'       => '&#60;',
		'&gt;'       => '&#62;',
		'|'          => '&#124;',
		'&nbsp;'     => '&#160;',
		'&iexcl;'    => '&#161;',
		'&cent;'     => '&#162;',
		'&pound;'    => '&#163;',
		'&curren;'   => '&#164;',
		'&yen;'      => '&#165;',
		'&brvbar;'   => '&#166;',
		'&brkbar;'   => '&#166;',
		'&sect;'     => '&#167;',
		'&uml;'      => '&#168;',
		'&die;'      => '&#168;',
		'&copy;'     => '&#169;',
		'&ordf;'     => '&#170;',
		'&laquo;'    => '&#171;',
		'&not;'      => '&#172;',
		'&shy;'      => '&#173;',
		'&reg;'      => '&#174;',
		'&macr;'     => '&#175;',
		'&hibar;'    => '&#175;',
		'&deg;'      => '&#176;',
		'&plusmn;'   => '&#177;',
		'&sup2;'     => '&#178;',
		'&sup3;'     => '&#179;',
		'&acute;'    => '&#180;',
		'&micro;'    => '&#181;',
		'&para;'     => '&#182;',
		'&middot;'   => '&#183;',
		'&cedil;'    => '&#184;',
		'&sup1;'     => '&#185;',
		'&ordm;'     => '&#186;',
		'&raquo;'    => '&#187;',
		'&frac14;'   => '&#188;',
		'&frac12;'   => '&#189;',
		'&frac34;'   => '&#190;',
		'&iquest;'   => '&#191;',
		'&Agrave;'   => '&#192;',
		'&Aacute;'   => '&#193;',
		'&Acirc;'    => '&#194;',
		'&Atilde;'   => '&#195;',
		'&Auml;'     => '&#196;',
		'&Aring;'    => '&#197;',
		'&AElig;'    => '&#198;',
		'&Ccedil;'   => '&#199;',
		'&Egrave;'   => '&#200;',
		'&Eacute;'   => '&#201;',
		'&Ecirc;'    => '&#202;',
		'&Euml;'     => '&#203;',
		'&Igrave;'   => '&#204;',
		'&Iacute;'   => '&#205;',
		'&Icirc;'    => '&#206;',
		'&Iuml;'     => '&#207;',
		'&ETH;'      => '&#208;',
		'&Ntilde;'   => '&#209;',
		'&Ograve;'   => '&#210;',
		'&Oacute;'   => '&#211;',
		'&Ocirc;'    => '&#212;',
		'&Otilde;'   => '&#213;',
		'&Ouml;'     => '&#214;',
		'&times;'    => '&#215;',
		'&Oslash;'   => '&#216;',
		'&Ugrave;'   => '&#217;',
		'&Uacute;'   => '&#218;',
		'&Ucirc;'    => '&#219;',
		'&Uuml;'     => '&#220;',
		'&Yacute;'   => '&#221;',
		'&THORN;'    => '&#222;',
		'&szlig;'    => '&#223;',
		'&agrave;'   => '&#224;',
		'&aacute;'   => '&#225;',
		'&acirc;'    => '&#226;',
		'&atilde;'   => '&#227;',
		'&auml;'     => '&#228;',
		'&aring;'    => '&#229;',
		'&aelig;'    => '&#230;',
		'&ccedil;'   => '&#231;',
		'&egrave;'   => '&#232;',
		'&eacute;'   => '&#233;',
		'&ecirc;'    => '&#234;',
		'&euml;'     => '&#235;',
		'&igrave;'   => '&#236;',
		'&iacute;'   => '&#237;',
		'&icirc;'    => '&#238;',
		'&iuml;'     => '&#239;',
		'&eth;'      => '&#240;',
		'&ntilde;'   => '&#241;',
		'&ograve;'   => '&#242;',
		'&oacute;'   => '&#243;',
		'&ocirc;'    => '&#244;',
		'&otilde;'   => '&#245;',
		'&ouml;'     => '&#246;',
		'&divide;'   => '&#247;',
		'&oslash;'   => '&#248;',
		'&ugrave;'   => '&#249;',
		'&uacute;'   => '&#250;',
		'&ucirc;'    => '&#251;',
		'&uuml;'     => '&#252;',
		'&yacute;'   => '&#253;',
		'&thorn;'    => '&#254;',
		'&yuml;'     => '&#255;',
		'&OElig;'    => '&#338;',
		'&oelig;'    => '&#339;',
		'&Scaron;'   => '&#352;',
		'&scaron;'   => '&#353;',
		'&Yuml;'     => '&#376;',
		'&fnof;'     => '&#402;',
		'&circ;'     => '&#710;',
		'&tilde;'    => '&#732;',
		'&Alpha;'    => '&#913;',
		'&Beta;'     => '&#914;',
		'&Gamma;'    => '&#915;',
		'&Delta;'    => '&#916;',
		'&Epsilon;'  => '&#917;',
		'&Zeta;'     => '&#918;',
		'&Eta;'      => '&#919;',
		'&Theta;'    => '&#920;',
		'&Iota;'     => '&#921;',
		'&Kappa;'    => '&#922;',
		'&Lambda;'   => '&#923;',
		'&Mu;'       => '&#924;',
		'&Nu;'       => '&#925;',
		'&Xi;'       => '&#926;',
		'&Omicron;'  => '&#927;',
		'&Pi;'       => '&#928;',
		'&Rho;'      => '&#929;',
		'&Sigma;'    => '&#931;',
		'&Tau;'      => '&#932;',
		'&Upsilon;'  => '&#933;',
		'&Phi;'      => '&#934;',
		'&Chi;'      => '&#935;',
		'&Psi;'      => '&#936;',
		'&Omega;'    => '&#937;',
		'&alpha;'    => '&#945;',
		'&beta;'     => '&#946;',
		'&gamma;'    => '&#947;',
		'&delta;'    => '&#948;',
		'&epsilon;'  => '&#949;',
		'&zeta;'     => '&#950;',
		'&eta;'      => '&#951;',
		'&theta;'    => '&#952;',
		'&iota;'     => '&#953;',
		'&kappa;'    => '&#954;',
		'&lambda;'   => '&#955;',
		'&mu;'       => '&#956;',
		'&nu;'       => '&#957;',
		'&xi;'       => '&#958;',
		'&omicron;'  => '&#959;',
		'&pi;'       => '&#960;',
		'&rho;'      => '&#961;',
		'&sigmaf;'   => '&#962;',
		'&sigma;'    => '&#963;',
		'&tau;'      => '&#964;',
		'&upsilon;'  => '&#965;',
		'&phi;'      => '&#966;',
		'&chi;'      => '&#967;',
		'&psi;'      => '&#968;',
		'&omega;'    => '&#969;',
		'&thetasym;' => '&#977;',
		'&upsih;'    => '&#978;',
		'&piv;'      => '&#982;',
		'&ensp;'     => '&#8194;',
		'&emsp;'     => '&#8195;',
		'&thinsp;'   => '&#8201;',
		'&zwnj;'     => '&#8204;',
		'&zwj;'      => '&#8205;',
		'&lrm;'      => '&#8206;',
		'&rlm;'      => '&#8207;',
		'&ndash;'    => '&#8211;',
		'&mdash;'    => '&#8212;',
		'&lsquo;'    => '&#8216;',
		'&rsquo;'    => '&#8217;',
		'&sbquo;'    => '&#8218;',
		'&ldquo;'    => '&#8220;',
		'&rdquo;'    => '&#8221;',
		'&bdquo;'    => '&#8222;',
		'&dagger;'   => '&#8224;',
		'&Dagger;'   => '&#8225;',
		'&bull;'     => '&#8226;',
		'&hellip;'   => '&#8230;',
		'&permil;'   => '&#8240;',
		'&prime;'    => '&#8242;',
		'&Prime;'    => '&#8243;',
		'&lsaquo;'   => '&#8249;',
		'&rsaquo;'   => '&#8250;',
		'&oline;'    => '&#8254;',
		'&frasl;'    => '&#8260;',
		'&euro;'     => '&#8364;',
		'&image;'    => '&#8465;',
		'&weierp;'   => '&#8472;',
		'&real;'     => '&#8476;',
		'&trade;'    => '&#8482;',
		'&alefsym;'  => '&#8501;',
		'&crarr;'    => '&#8629;',
		'&lArr;'     => '&#8656;',
		'&uArr;'     => '&#8657;',
		'&rArr;'     => '&#8658;',
		'&dArr;'     => '&#8659;',
		'&hArr;'     => '&#8660;',
		'&forall;'   => '&#8704;',
		'&part;'     => '&#8706;',
		'&exist;'    => '&#8707;',
		'&empty;'    => '&#8709;',
		'&nabla;'    => '&#8711;',
		'&isin;'     => '&#8712;',
		'&notin;'    => '&#8713;',
		'&ni;'       => '&#8715;',
		'&prod;'     => '&#8719;',
		'&sum;'      => '&#8721;',
		'&minus;'    => '&#8722;',
		'&lowast;'   => '&#8727;',
		'&radic;'    => '&#8730;',
		'&prop;'     => '&#8733;',
		'&infin;'    => '&#8734;',
		'&ang;'      => '&#8736;',
		'&and;'      => '&#8743;',
		'&or;'       => '&#8744;',
		'&cap;'      => '&#8745;',
		'&cup;'      => '&#8746;',
		'&int;'      => '&#8747;',
		'&there4;'   => '&#8756;',
		'&sim;'      => '&#8764;',
		'&cong;'     => '&#8773;',
		'&asymp;'    => '&#8776;',
		'&ne;'       => '&#8800;',
		'&equiv;'    => '&#8801;',
		'&le;'       => '&#8804;',
		'&ge;'       => '&#8805;',
		'&sub;'      => '&#8834;',
		'&sup;'      => '&#8835;',
		'&nsub;'     => '&#8836;',
		'&sube;'     => '&#8838;',
		'&supe;'     => '&#8839;',
		'&oplus;'    => '&#8853;',
		'&otimes;'   => '&#8855;',
		'&perp;'     => '&#8869;',
		'&sdot;'     => '&#8901;',
		'&lceil;'    => '&#8968;',
		'&rceil;'    => '&#8969;',
		'&lfloor;'   => '&#8970;',
		'&rfloor;'   => '&#8971;',
		'&lang;'     => '&#9001;',
		'&rang;'     => '&#9002;',
		'&larr;'     => '&#8592;',
		'&uarr;'     => '&#8593;',
		'&rarr;'     => '&#8594;',
		'&darr;'     => '&#8595;',
		'&harr;'     => '&#8596;',
		'&loz;'      => '&#9674;',
		'&spades;'   => '&#9824;',
		'&clubs;'    => '&#9827;',
		'&hearts;'   => '&#9829;',
		'&diams;'    => '&#9830;',
	);

	return str_replace( array_keys( $to_ncr ), array_values( $to_ncr ), $text );
}

// wp-includes/formatting.php (WP 6.8.1)
function format_for_editor( $text, $default_editor = null ) {
	if ( $text ) {
		$text = htmlspecialchars( $text, ENT_NOQUOTES, WPCOPY__OPTION_BLOG_CHARSET );
	}

	/**
	 * Filters the text after it is formatted for the editor.
	 *
	 * @since 4.3.0
	 *
	 * @param string $text           The formatted text.
	 * @param string $default_editor The default editor for the current user.
	 *                               It is usually either 'html' or 'tinymce'.
	 */
	return apply_filters( 'format_for_editor', $text, $default_editor );
}

// wp-includes/formatting.php (WP 6.8.1)
function _deep_replace( $search, $subject ) {
	$subject = (string) $subject;

	$count = 1;
	while ( $count ) {
		$subject = str_replace( $search, '', $subject, $count );
	}

	return $subject;
}

// wp-includes/formatting.php (WP 6.8.1)
function esc_sql( $data ) {
	global $wpdb;
	return $wpdb->_escape( $data );
}

// wp-includes/formatting.php (WP 6.8.1)
function esc_url( $url, $protocols = null, $_context = 'display' ) {
	$original_url = $url;

	if ( '' === $url ) {
		return $url;
	}

	$url = str_replace( ' ', '%20', ltrim( $url ) );
	$url = preg_replace( '|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '', $url );

	if ( '' === $url ) {
		return $url;
	}

	if ( 0 !== stripos( $url, 'mailto:' ) ) {
		$strip = array( '%0d', '%0a', '%0D', '%0A' );
		$url   = _deep_replace( $strip, $url );
	}

	$url = str_replace( ';//', '://', $url );
	/*
	 * If the URL doesn't appear to contain a scheme, we presume
	 * it needs http:// prepended (unless it's a relative link
	 * starting with /, # or ?, or a PHP file).
	 */
	if ( ! str_contains( $url, ':' ) && ! in_array( $url[0], array( '/', '#', '?' ), true ) &&
		! preg_match( '/^[a-z0-9-]+?\.php/i', $url )
	) {
		$url = 'http://' . $url;
	}

	// Replace ampersands and single quotes only when displaying.
	if ( 'display' === $_context ) {
		$url = wp_kses_normalize_entities( $url );
		$url = str_replace( '&amp;', '&#038;', $url );
		$url = str_replace( "'", '&#039;', $url );
	}

	if ( str_contains( $url, '[' ) || str_contains( $url, ']' ) ) {

		$parsed = wp_parse_url( $url );
		$front  = '';

		if ( isset( $parsed['scheme'] ) ) {
			$front .= $parsed['scheme'] . '://';
		} elseif ( '/' === $url[0] ) {
			$front .= '//';
		}

		if ( isset( $parsed['user'] ) ) {
			$front .= $parsed['user'];
		}

		if ( isset( $parsed['pass'] ) ) {
			$front .= ':' . $parsed['pass'];
		}

		if ( isset( $parsed['user'] ) || isset( $parsed['pass'] ) ) {
			$front .= '@';
		}

		if ( isset( $parsed['host'] ) ) {
			$front .= $parsed['host'];
		}

		if ( isset( $parsed['port'] ) ) {
			$front .= ':' . $parsed['port'];
		}

		$end_dirty = str_replace( $front, '', $url );
		$end_clean = str_replace( array( '[', ']' ), array( '%5B', '%5D' ), $end_dirty );
		$url       = str_replace( $end_dirty, $end_clean, $url );

	}

	if ( '/' === $url[0] ) {
		$good_protocol_url = $url;
	} else {
		if ( ! is_array( $protocols ) ) {
			$protocols = wp_allowed_protocols();
		}
		$good_protocol_url = wp_kses_bad_protocol( $url, $protocols );
		if ( strtolower( $good_protocol_url ) !== strtolower( $url ) ) {
			return '';
		}
	}

	/**
	 * Filters a string cleaned and escaped for output as a URL.
	 *
	 * @since 2.3.0
	 *
	 * @param string $good_protocol_url The cleaned URL to be returned.
	 * @param string $original_url      The URL prior to cleaning.
	 * @param string $_context          If 'display', replace ampersands and single quotes only.
	 */
	return apply_filters( 'clean_url', $good_protocol_url, $original_url, $_context );
}

// wp-includes/formatting.php (WP 6.8.1)
function esc_url_raw( $url, $protocols = null ) {
	return sanitize_url( $url, $protocols );
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_url( $url, $protocols = null ) {
	return esc_url( $url, $protocols, 'db' );
}

// wp-includes/formatting.php (WP 6.8.1)
function htmlentities2( $text ) {
	$translation_table = get_html_translation_table( HTML_ENTITIES, ENT_QUOTES );

	$translation_table[ chr( 38 ) ] = '&';

	return preg_replace( '/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/', '&amp;', strtr( $text, $translation_table ) );
}

// wp-includes/formatting.php (WP 6.8.1)
function esc_js( $text ) {
	$safe_text = wp_check_invalid_utf8( $text );
	$safe_text = _wp_specialchars( $safe_text, ENT_COMPAT );
	$safe_text = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", stripslashes( $safe_text ) );
	$safe_text = str_replace( "\r", '', $safe_text );
	$safe_text = str_replace( "\n", '\\n', addslashes( $safe_text ) );
	/**
	 * Filters a string cleaned and escaped for output in JavaScript.
	 *
	 * Text passed to esc_js() is stripped of invalid or special characters,
	 * and properly slashed for output.
	 *
	 * @since 2.0.6
	 *
	 * @param string $safe_text The text after it has been escaped.
	 * @param string $text      The text prior to being escaped.
	 */
	return apply_filters( 'js_escape', $safe_text, $text );
}

// wp-includes/formatting.php (WP 6.8.1)
function esc_html( $text ) {
	$safe_text = wp_check_invalid_utf8( $text );
	$safe_text = _wp_specialchars( $safe_text, ENT_QUOTES );
	/**
	 * Filters a string cleaned and escaped for output in HTML.
	 *
	 * Text passed to esc_html() is stripped of invalid or special characters
	 * before output.
	 *
	 * @since 2.8.0
	 *
	 * @param string $safe_text The text after it has been escaped.
	 * @param string $text      The text prior to being escaped.
	 */
	return apply_filters( 'esc_html', $safe_text, $text );
}

// wp-includes/formatting.php (WP 6.8.1)
function esc_attr( $text ) {
	$safe_text = wp_check_invalid_utf8( $text );
	$safe_text = _wp_specialchars( $safe_text, ENT_QUOTES );
	/**
	 * Filters a string cleaned and escaped for output in an HTML attribute.
	 *
	 * Text passed to esc_attr() is stripped of invalid or special characters
	 * before output.
	 *
	 * @since 2.0.6
	 *
	 * @param string $safe_text The text after it has been escaped.
	 * @param string $text      The text prior to being escaped.
	 */
	return apply_filters( 'attribute_escape', $safe_text, $text );
}

// wp-includes/formatting.php (WP 6.8.1)
function esc_textarea( $text ) {
	$safe_text = htmlspecialchars( $text, ENT_QUOTES, WPCOPY__OPTION_BLOG_CHARSET );
	/**
	 * Filters a string cleaned and escaped for output in a textarea element.
	 *
	 * @since 3.1.0
	 *
	 * @param string $safe_text The text after it has been escaped.
	 * @param string $text      The text prior to being escaped.
	 */
	return apply_filters( 'esc_textarea', $safe_text, $text );
}

// wp-includes/formatting.php (WP 6.8.1)
function esc_xml( $text ) {
	$safe_text = wp_check_invalid_utf8( $text );

	$cdata_regex = '\<\!\[CDATA\[.*?\]\]\>';
	$regex       = <<<EOF
/
	(?=.*?{$cdata_regex})                 # lookahead that will match anything followed by a CDATA Section
	(?<non_cdata_followed_by_cdata>(.*?)) # the "anything" matched by the lookahead
	(?<cdata>({$cdata_regex}))            # the CDATA Section matched by the lookahead

|	                                      # alternative

	(?<non_cdata>(.*))                    # non-CDATA Section
/sx
EOF;

	$safe_text = (string) preg_replace_callback(
		$regex,
		static function ( $matches ) {
			if ( ! isset( $matches[0] ) ) {
				return '';
			}

			if ( isset( $matches['non_cdata'] ) ) {
				// escape HTML entities in the non-CDATA Section.
				return _wp_specialchars( $matches['non_cdata'], ENT_XML1 );
			}

			// Return the CDATA Section unchanged, escape HTML entities in the rest.
			return _wp_specialchars( $matches['non_cdata_followed_by_cdata'], ENT_XML1 ) . $matches['cdata'];
		},
		$safe_text
	);

	/**
	 * Filters a string cleaned and escaped for output in XML.
	 *
	 * Text passed to esc_xml() is stripped of invalid or special characters
	 * before output. HTML named character references are converted to their
	 * equivalent code points.
	 *
	 * @since 5.5.0
	 *
	 * @param string $safe_text The text after it has been escaped.
	 * @param string $text      The text prior to being escaped.
	 */
	return apply_filters( 'esc_xml', $safe_text, $text );
}

// wp-includes/formatting.php (WP 6.8.1)
function tag_escape( $tag_name ) {
	$safe_tag = strtolower( preg_replace( '/[^a-zA-Z0-9-_:]/', '', $tag_name ) );
	/**
	 * Filters a string cleaned and escaped for output as an HTML tag.
	 *
	 * @since 2.8.0
	 *
	 * @param string $safe_tag The tag name after it has been escaped.
	 * @param string $tag_name The text before it was escaped.
	 */
	return apply_filters( 'tag_escape', $safe_tag, $tag_name );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_make_link_relative( $link ) {
	return preg_replace( '|^(https?:)?//[^/]+(/?.*)|i', '$2', $link );
}

// wp-includes/formatting.php (WP 6.8.1)
function map_deep( $value, $callback ) {
	if ( is_array( $value ) ) {
		foreach ( $value as $index => $item ) {
			$value[ $index ] = map_deep( $item, $callback );
		}
	} elseif ( is_object( $value ) ) {
		$object_vars = get_object_vars( $value );
		foreach ( $object_vars as $property_name => $property_value ) {
			$value->$property_name = map_deep( $property_value, $callback );
		}
	} else {
		$value = call_user_func( $callback, $value );
	}

	return $value;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_parse_str( $input_string, &$result ) {
	parse_str( (string) $input_string, $result );

	/**
	 * Filters the array of variables derived from a parsed string.
	 *
	 * @since 2.2.1
	 *
	 * @param array $result The array populated with variables.
	 */
	$result = apply_filters( 'wp_parse_str', $result );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_pre_kses_less_than( $content ) {
	return preg_replace_callback( '%<[^>]*?((?=<)|>|$)%', 'wp_pre_kses_less_than_callback', $content );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_pre_kses_less_than_callback( $matches ) {
	if ( ! str_contains( $matches[0], '>' ) ) {
		return esc_html( $matches[0] );
	}
	return $matches[0];
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_pre_kses_block_attributes( $content, $allowed_html, $allowed_protocols ) {
	/*
	 * `filter_block_content` is expected to call `wp_kses`. Temporarily remove
	 * the filter to avoid recursion.
	 */
	remove_filter( 'pre_kses', 'wp_pre_kses_block_attributes', 10 );
	$content = filter_block_content( $content, $allowed_html, $allowed_protocols );
	add_filter( 'pre_kses', 'wp_pre_kses_block_attributes', 10, 3 );

	return $content;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_sprintf( $pattern, ...$args ) {
	$len       = strlen( $pattern );
	$start     = 0;
	$result    = '';
	$arg_index = 0;

	while ( $len > $start ) {
		// Last character: append and break.
		if ( strlen( $pattern ) - 1 === $start ) {
			$result .= substr( $pattern, -1 );
			break;
		}

		// Literal %: append and continue.
		if ( '%%' === substr( $pattern, $start, 2 ) ) {
			$start  += 2;
			$result .= '%';
			continue;
		}

		// Get fragment before next %.
		$end = strpos( $pattern, '%', $start + 1 );
		if ( false === $end ) {
			$end = $len;
		}
		$fragment = substr( $pattern, $start, $end - $start );

		// Fragment has a specifier.
		if ( '%' === $pattern[ $start ] ) {
			// Find numbered arguments or take the next one in order.
			if ( preg_match( '/^%(\d+)\$/', $fragment, $matches ) ) {
				$index    = $matches[1] - 1; // 0-based array vs 1-based sprintf() arguments.
				$arg      = isset( $args[ $index ] ) ? $args[ $index ] : '';
				$fragment = str_replace( "%{$matches[1]}$", '%', $fragment );
			} else {
				$arg = isset( $args[ $arg_index ] ) ? $args[ $arg_index ] : '';
				++$arg_index;
			}

			/**
			 * Filters a fragment from the pattern passed to wp_sprintf().
			 *
			 * If the fragment is unchanged, then sprintf() will be run on the fragment.
			 *
			 * @since 2.5.0
			 *
			 * @param string $fragment A fragment from the pattern.
			 * @param string $arg      The argument.
			 */
			$_fragment = apply_filters( 'wp_sprintf', $fragment, $arg );

			if ( $_fragment !== $fragment ) {
				$fragment = $_fragment;
			} else {
				$fragment = sprintf( $fragment, (string) $arg );
			}
		}

		// Append to result and move to next fragment.
		$result .= $fragment;
		$start   = $end;
	}

	return $result;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_sprintf_l( $pattern, $args ) {
	// Not a match.
	if ( ! str_starts_with( $pattern, '%l' ) ) {
		return $pattern;
	}

	// Nothing to work with.
	if ( empty( $args ) ) {
		return '';
	}

	/**
	 * Filters the translated delimiters used by wp_sprintf_l().
	 * Placeholders (%s) are included to assist translators and then
	 * removed before the array of strings reaches the filter.
	 *
	 * Please note: Ampersands and entities should be avoided here.
	 *
	 * @since 2.5.0
	 *
	 * @param array $delimiters An array of translated delimiters.
	 */
	$l = apply_filters(
		'wp_sprintf_l',
		array(
			/* translators: Used to join items in a list with more than 2 items. */
			'between'          => sprintf( __( '%1$s, %2$s' ), '', '' ),
			/* translators: Used to join last two items in a list with more than 2 times. */
			'between_last_two' => sprintf( __( '%1$s, and %2$s' ), '', '' ),
			/* translators: Used to join items in a list with only 2 items. */
			'between_only_two' => sprintf( __( '%1$s and %2$s' ), '', '' ),
		)
	);

	$args   = (array) $args;
	$result = array_shift( $args );
	if ( count( $args ) === 1 ) {
		$result .= $l['between_only_two'] . array_shift( $args );
	}

	// Loop when more than two args.
	$i = count( $args );
	while ( $i ) {
		$arg = array_shift( $args );
		--$i;
		if ( 0 === $i ) {
			$result .= $l['between_last_two'] . $arg;
		} else {
			$result .= $l['between'] . $arg;
		}
	}

	return $result . substr( $pattern, 2 );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_html_excerpt( $str, $count, $more = null ) {
	if ( null === $more ) {
		$more = '';
	}

	$str     = wp_strip_all_tags( $str, true );
	$excerpt = mb_substr( $str, 0, $count );

	// Remove part of an entity at the end.
	$excerpt = preg_replace( '/&[^;\s]{0,6}$/', '', $excerpt );

	if ( $str !== $excerpt ) {
		$excerpt = trim( $excerpt ) . $more;
	}

	return $excerpt;
}

// wp-includes/formatting.php (WP 6.8.1)
function links_add_base_url( $content, $base, $attrs = array( 'src', 'href' ) ) {
	global $_links_add_base;
	$_links_add_base = $base;
	$attrs           = implode( '|', (array) $attrs );
	return preg_replace_callback( "!($attrs)=(['\"])(.+?)\\2!i", '_links_add_base', $content );
}

// wp-includes/formatting.php (WP 6.8.1)
function _links_add_base( $m ) {
	global $_links_add_base;
	// 1 = attribute name  2 = quotation mark  3 = URL.
	return $m[1] . '=' . $m[2] .
		( preg_match( '#^(\w{1,20}):#', $m[3], $protocol ) && in_array( $protocol[1], wp_allowed_protocols(), true ) ?
			$m[3] :
			WP_Http::make_absolute_url( $m[3], $_links_add_base )
		)
		. $m[2];
}

// wp-includes/formatting.php (WP 6.8.1)
function links_add_target( $content, $target = '_blank', $tags = array( 'a' ) ) {
	global $_links_add_target;
	$_links_add_target = $target;
	$tags              = implode( '|', (array) $tags );
	return preg_replace_callback( "!<($tags)((\s[^>]*)?)>!i", '_links_add_target', $content );
}

// wp-includes/formatting.php (WP 6.8.1)
function _links_add_target( $m ) {
	global $_links_add_target;
	$tag  = $m[1];
	$link = preg_replace( '|( target=([\'"])(.*?)\2)|i', '', $m[2] );
	return '<' . $tag . $link . ' target="' . esc_attr( $_links_add_target ) . '">';
}

// wp-includes/formatting.php (WP 6.8.1)
function normalize_whitespace( $str ) {
	$str = trim( $str );
	$str = str_replace( "\r", "\n", $str );
	$str = preg_replace( array( '/\n+/', '/[ \t]+/' ), array( "\n", ' ' ), $str );
	return $str;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_strip_all_tags( $text, $remove_breaks = false ) {
	if ( is_null( $text ) ) {
		return '';
	}

	if ( ! is_scalar( $text ) ) {
		/*
		 * To maintain consistency with pre-PHP 8 error levels,
		 * wp_trigger_error() is used to trigger an E_USER_WARNING,
		 * rather than _doing_it_wrong(), which triggers an E_USER_NOTICE.
		 */
		wp_trigger_error(
			'',
			sprintf(
				/* translators: 1: The function name, 2: The argument number, 3: The argument name, 4: The expected type, 5: The provided type. */
				__( 'Warning: %1$s expects parameter %2$s (%3$s) to be a %4$s, %5$s given.' ),
				__FUNCTION__,
				'#1',
				'$text',
				'string',
				gettype( $text )
			),
			E_USER_WARNING
		);

		return '';
	}

	$text = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $text );
	$text = strip_tags( $text );

	if ( $remove_breaks ) {
		$text = preg_replace( '/[\r\n\t ]+/', ' ', $text );
	}

	return trim( $text );
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_text_field( $str ) {
	$filtered = _sanitize_text_fields( $str, false );

	/**
	 * Filters a sanitized text field string.
	 *
	 * @since 2.9.0
	 *
	 * @param string $filtered The sanitized string.
	 * @param string $str      The string prior to being sanitized.
	 */
	return apply_filters( 'sanitize_text_field', $filtered, $str );
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_textarea_field( $str ) {
	$filtered = _sanitize_text_fields( $str, true );

	/**
	 * Filters a sanitized textarea field string.
	 *
	 * @since 4.7.0
	 *
	 * @param string $filtered The sanitized string.
	 * @param string $str      The string prior to being sanitized.
	 */
	return apply_filters( 'sanitize_textarea_field', $filtered, $str );
}

// wp-includes/formatting.php (WP 6.8.1)
function _sanitize_text_fields( $str, $keep_newlines = false ) {
	if ( is_object( $str ) || is_array( $str ) ) {
		return '';
	}

	$str = (string) $str;

	$filtered = wp_check_invalid_utf8( $str );

	if ( str_contains( $filtered, '<' ) ) {
		$filtered = wp_pre_kses_less_than( $filtered );
		// This will strip extra whitespace for us.
		$filtered = wp_strip_all_tags( $filtered, false );

		/*
		 * Use HTML entities in a special case to make sure that
		 * later newline stripping stages cannot lead to a functional tag.
		 */
		$filtered = str_replace( "<\n", "&lt;\n", $filtered );
	}

	if ( ! $keep_newlines ) {
		$filtered = preg_replace( '/[\r\n\t ]+/', ' ', $filtered );
	}
	$filtered = trim( $filtered );

	// Remove percent-encoded characters.
	$found = false;
	while ( preg_match( '/%[a-f0-9]{2}/i', $filtered, $match ) ) {
		$filtered = str_replace( $match[0], '', $filtered );
		$found    = true;
	}

	if ( $found ) {
		// Strip out the whitespace that may now exist after removing percent-encoded characters.
		$filtered = trim( preg_replace( '/ +/', ' ', $filtered ) );
	}

	return $filtered;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_basename( $path, $suffix = '' ) {
	return urldecode( basename( str_replace( array( '%2F', '%5C' ), '/', urlencode( $path ) ), $suffix ) );
}

// wp-includes/formatting.php (WP 6.8.1)
function capital_P_dangit( $text ) {
	// Simple replacement for titles.
	$current_filter = current_filter();
	if ( 'the_title' === $current_filter || 'wp_title' === $current_filter ) {
		return str_replace( 'Wordpress', 'WordPress', $text );
	}
	// Still here? Use the more judicious replacement.
	static $dblq = false;
	if ( false === $dblq ) {
		$dblq = _x( '&#8220;', 'opening curly double quote' );
	}
	return str_replace(
		array( ' Wordpress', '&#8216;Wordpress', $dblq . 'Wordpress', '>Wordpress', '(Wordpress' ),
		array( ' WordPress', '&#8216;WordPress', $dblq . 'WordPress', '>WordPress', '(WordPress' ),
		$text
	);
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_mime_type( $mime_type ) {
	$sani_mime_type = preg_replace( '/[^-+*.a-zA-Z0-9\/]/', '', $mime_type );
	/**
	 * Filters a mime type following sanitization.
	 *
	 * @since 3.1.3
	 *
	 * @param string $sani_mime_type The sanitized mime type.
	 * @param string $mime_type      The mime type prior to sanitization.
	 */
	return apply_filters( 'sanitize_mime_type', $sani_mime_type, $mime_type );
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_trackback_urls( $to_ping ) {
	$urls_to_ping = preg_split( '/[\r\n\t ]/', trim( $to_ping ), -1, PREG_SPLIT_NO_EMPTY );
	foreach ( $urls_to_ping as $k => $url ) {
		if ( ! preg_match( '#^https?://.#i', $url ) ) {
			unset( $urls_to_ping[ $k ] );
		}
	}
	$urls_to_ping = array_map( 'sanitize_url', $urls_to_ping );
	$urls_to_ping = implode( "\n", $urls_to_ping );
	/**
	 * Filters a list of trackback URLs following sanitization.
	 *
	 * The string returned here consists of a space or carriage return-delimited list
	 * of trackback URLs.
	 *
	 * @since 3.4.0
	 *
	 * @param string $urls_to_ping Sanitized space or carriage return separated URLs.
	 * @param string $to_ping      Space or carriage return separated URLs before sanitization.
	 */
	return apply_filters( 'sanitize_trackback_urls', $urls_to_ping, $to_ping );
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_slash( $value ) {
	if ( is_array( $value ) ) {
		$value = array_map( 'wp_slash', $value );
	}

	if ( is_string( $value ) ) {
		return addslashes( $value );
	}

	return $value;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_unslash( $value ) {
	return stripslashes_deep( $value );
}

// wp-includes/formatting.php (WP 6.8.1)
function get_url_in_content( $content ) {
	if ( empty( $content ) ) {
		return false;
	}

	if ( preg_match( '/<a\s[^>]*?href=([\'"])(.+?)\1/is', $content, $matches ) ) {
		return sanitize_url( $matches[2] );
	}

	return false;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_spaces_regexp() {
	static $spaces = '';

	if ( empty( $spaces ) ) {
		/**
		 * Filters the regexp for common whitespace characters.
		 *
		 * This string is substituted for the \s sequence as needed in regular
		 * expressions. For websites not written in English, different characters
		 * may represent whitespace. For websites not encoded in UTF-8, the 0xC2 0xA0
		 * sequence may not be in use.
		 *
		 * @since 4.0.0
		 *
		 * @param string $spaces Regexp pattern for matching common whitespace characters.
		 */
		$spaces = apply_filters( 'wp_spaces_regexp', '[\r\n\t ]|\xC2\xA0|&nbsp;' );
	}

	return $spaces;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_enqueue_emoji_styles() {
	// Back-compat for plugins that disable functionality by unhooking this action.
	$action = is_admin() ? 'admin_print_styles' : 'wp_print_styles';
	if ( ! has_action( $action, 'print_emoji_styles' ) ) {
		return;
	}
	remove_action( $action, 'print_emoji_styles' );

	$emoji_styles = '
	img.wp-smiley, img.emoji {
		display: inline !important;
		border: none !important;
		box-shadow: none !important;
		height: 1em !important;
		width: 1em !important;
		margin: 0 0.07em !important;
		vertical-align: -0.1em !important;
		background: none !important;
		padding: 0 !important;
	}';
	$handle       = 'wp-emoji-styles';
	wp_register_style( $handle, false );
	wp_add_inline_style( $handle, $emoji_styles );
	wp_enqueue_style( $handle );
}

// wp-includes/formatting.php (WP 6.8.1)
function print_emoji_detection_script() {
	static $printed = false;

	if ( $printed ) {
		return;
	}

	$printed = true;

	_print_emoji_detection_script();
}

// wp-includes/formatting.php (WP 6.8.1)
function _print_emoji_detection_script() {
	$settings = array(
		/**
		 * Filters the URL where emoji png images are hosted.
		 *
		 * @since 4.2.0
		 *
		 * @param string $url The emoji base URL for png images.
		 */
		'baseUrl' => apply_filters( 'emoji_url', 'https://s.w.org/images/core/emoji/15.1.0/72x72/' ),

		/**
		 * Filters the extension of the emoji png files.
		 *
		 * @since 4.2.0
		 *
		 * @param string $extension The emoji extension for png files. Default .png.
		 */
		'ext'     => apply_filters( 'emoji_ext', '.png' ),

		/**
		 * Filters the URL where emoji SVG images are hosted.
		 *
		 * @since 4.6.0
		 *
		 * @param string $url The emoji base URL for svg images.
		 */
		'svgUrl'  => apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/15.1.0/svg/' ),

		/**
		 * Filters the extension of the emoji SVG files.
		 *
		 * @since 4.6.0
		 *
		 * @param string $extension The emoji extension for svg files. Default .svg.
		 */
		'svgExt'  => apply_filters( 'emoji_svg_ext', '.svg' ),
	);

	$version = 'ver=' . get_bloginfo( 'version' );

	if ( SCRIPT_DEBUG ) {
		$settings['source'] = array(
			/** This filter is documented in wp-includes/class-wp-scripts.php */
			'wpemoji' => apply_filters( 'script_loader_src', includes_url( "js/wp-emoji.js?$version" ), 'wpemoji' ),
			/** This filter is documented in wp-includes/class-wp-scripts.php */
			'twemoji' => apply_filters( 'script_loader_src', includes_url( "js/twemoji.js?$version" ), 'twemoji' ),
		);
	} else {
		$settings['source'] = array(
			/** This filter is documented in wp-includes/class-wp-scripts.php */
			'concatemoji' => apply_filters( 'script_loader_src', includes_url( "js/wp-emoji-release.min.js?$version" ), 'concatemoji' ),
		);
	}

	wp_print_inline_script_tag(
		sprintf( 'window._wpemojiSettings = %s;', wp_json_encode( $settings ) ) . "\n" .
			file_get_contents( ABSPATH . WPINC . '/js/wp-emoji-loader' . wp_scripts_get_suffix() . '.js' )
	);
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_encode_emoji( $content ) {
	$emoji = _wp_emoji_list( 'partials' );

	foreach ( $emoji as $emojum ) {
		$emoji_char = html_entity_decode( $emojum );
		if ( str_contains( $content, $emoji_char ) ) {
			$content = preg_replace( "/$emoji_char/", $emojum, $content );
		}
	}

	return $content;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_staticize_emoji( $text ) {
	if ( ! str_contains( $text, '&#x' ) ) {
		if ( ( function_exists( 'mb_check_encoding' ) && mb_check_encoding( $text, 'ASCII' ) ) || ! preg_match( '/[^\x00-\x7F]/', $text ) ) {
			// The text doesn't contain anything that might be emoji, so we can return early.
			return $text;
		} else {
			$encoded_text = wp_encode_emoji( $text );
			if ( $encoded_text === $text ) {
				return $encoded_text;
			}

			$text = $encoded_text;
		}
	}

	$emoji = _wp_emoji_list( 'entities' );

	// Quickly narrow down the list of emoji that might be in the text and need replacing.
	$possible_emoji = array();
	foreach ( $emoji as $emojum ) {
		if ( str_contains( $text, $emojum ) ) {
			$possible_emoji[ $emojum ] = html_entity_decode( $emojum );
		}
	}

	if ( ! $possible_emoji ) {
		return $text;
	}

	/** This filter is documented in wp-includes/formatting.php */
	$cdn_url = apply_filters( 'emoji_url', 'https://s.w.org/images/core/emoji/15.1.0/72x72/' );

	/** This filter is documented in wp-includes/formatting.php */
	$ext = apply_filters( 'emoji_ext', '.png' );

	$output = '';
	/*
	 * HTML loop taken from smiley function, which was taken from texturize function.
	 * It'll never be consolidated.
	 *
	 * First, capture the tags as well as in between.
	 */
	$textarr = preg_split( '/(<.*>)/U', $text, -1, PREG_SPLIT_DELIM_CAPTURE );
	$stop    = count( $textarr );

	// Ignore processing of specific tags.
	$tags_to_ignore       = 'code|pre|style|script|textarea';
	$ignore_block_element = '';

	for ( $i = 0; $i < $stop; $i++ ) {
		$content = $textarr[ $i ];

		// If we're in an ignore block, wait until we find its closing tag.
		if ( '' === $ignore_block_element && preg_match( '/^<(' . $tags_to_ignore . ')>/', $content, $matches ) ) {
			$ignore_block_element = $matches[1];
		}

		// If it's not a tag and not in ignore block.
		if ( '' === $ignore_block_element && strlen( $content ) > 0 && '<' !== $content[0] && str_contains( $content, '&#x' ) ) {
			foreach ( $possible_emoji as $emojum => $emoji_char ) {
				if ( ! str_contains( $content, $emojum ) ) {
					continue;
				}

				$file = str_replace( ';&#x', '-', $emojum );
				$file = str_replace( array( '&#x', ';' ), '', $file );

				$entity = sprintf( '<img src="%s" alt="%s" class="wp-smiley" style="height: 1em; max-height: 1em;" />', $cdn_url . $file . $ext, $emoji_char );

				$content = str_replace( $emojum, $entity, $content );
			}
		}

		// Did we exit ignore block?
		if ( '' !== $ignore_block_element && '</' . $ignore_block_element . '>' === $content ) {
			$ignore_block_element = '';
		}

		$output .= $content;
	}

	// Finally, remove any stray U+FE0F characters.
	$output = str_replace( '&#xfe0f;', '', $output );

	return $output;
}

// wp-includes/formatting.php (WP 6.8.1)
function wp_staticize_emoji_for_email( $mail ) {
	if ( ! isset( $mail['message'] ) ) {
		return $mail;
	}

	/*
	 * We can only transform the emoji into images if it's a `text/html` email.
	 * To do that, here's a cut down version of the same process that happens
	 * in wp_mail() - get the `Content-Type` from the headers, if there is one,
	 * then pass it through the {@see 'wp_mail_content_type'} filter, in case
	 * a plugin is handling changing the `Content-Type`.
	 */
	$headers = array();
	if ( isset( $mail['headers'] ) ) {
		if ( is_array( $mail['headers'] ) ) {
			$headers = $mail['headers'];
		} else {
			$headers = explode( "\n", str_replace( "\r\n", "\n", $mail['headers'] ) );
		}
	}

	foreach ( $headers as $header ) {
		if ( ! str_contains( $header, ':' ) ) {
			continue;
		}

		// Explode them out.
		list( $name, $content ) = explode( ':', trim( $header ), 2 );

		// Cleanup crew.
		$name    = trim( $name );
		$content = trim( $content );

		if ( 'content-type' === strtolower( $name ) ) {
			if ( str_contains( $content, ';' ) ) {
				list( $type, $charset ) = explode( ';', $content );
				$content_type           = trim( $type );
			} else {
				$content_type = trim( $content );
			}
			break;
		}
	}

	// Set Content-Type if we don't have a content-type from the input headers.
	if ( ! isset( $content_type ) ) {
		$content_type = 'text/plain';
	}

	/** This filter is documented in wp-includes/pluggable.php */
	$content_type = apply_filters( 'wp_mail_content_type', $content_type );

	if ( 'text/html' === $content_type ) {
		$mail['message'] = wp_staticize_emoji( $mail['message'] );
	}

	return $mail;
}

// wp-includes/formatting.php (WP 6.8.1)
function _wp_emoji_list( $type = 'entities' ) {
	// Do not remove the START/END comments - they're used to find where to insert the arrays.

	// START: emoji arrays
	$entities = array( '&#x1f468;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f468;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f468;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;', '&#x1f469;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f468;', '&#x1f469;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f48b;&#x200d;&#x1f469;', '&#x1f3c3;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3f4;&#xe0067;&#xe0062;&#xe0065;&#xe006e;&#xe0067;&#xe007f;', '&#x1f3f4;&#xe0067;&#xe0062;&#xe0073;&#xe0063;&#xe0074;&#xe007f;', '&#x1f3f4;&#xe0067;&#xe0062;&#xe0077;&#xe006c;&#xe0073;&#xe007f;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3ff;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fb;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fc;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fd;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f468;&#x1f3fe;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f469;&#x1f3fe;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;&#x1f3ff;', '&#x1f468;&#x200d;&#x1f468;&#x200d;&#x1f466;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f468;&#x200d;&#x1f467;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f468;&#x200d;&#x1f467;&#x200d;&#x1f467;', '&#x1f468;&#x200d;&#x1f469;&#x200d;&#x1f466;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f467;', '&#x1f469;&#x200d;&#x1f469;&#x200d;&#x1f466;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f467;', '&#x1f9d1;&#x200d;&#x1f9d1;&#x200d;&#x1f9d2;&#x200d;&#x1f9d2;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x200d;&#x2640;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x200d;&#x2642;&#xfe0f;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f468;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;', '&#x1f469;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f469;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f468;', '&#x1f469;&#x200d;&#x2764;&#xfe0f;&#x200d;&#x1f469;', '&#x1f9d1;&#x200d;&#x1f9af;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x200d;&#x1f9bc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9d1;&#x200d;&#x1f9bd;&#x200d;&#x27a1;&#xfe0f;', '&#x1faf1;&#x1f3fb;&#x200d;&#x1faf2;&#x1f3fc;', '&#x1faf1;&#x1f3fb;&#x200d;&#x1faf2;&#x1f3fd;', '&#x1faf1;&#x1f3fb;&#x200d;&#x1faf2;&#x1f3fe;', '&#x1faf1;&#x1f3fb;&#x200d;&#x1faf2;&#x1f3ff;', '&#x1faf1;&#x1f3fc;&#x200d;&#x1faf2;&#x1f3fb;', '&#x1faf1;&#x1f3fc;&#x200d;&#x1faf2;&#x1f3fd;', '&#x1faf1;&#x1f3fc;&#x200d;&#x1faf2;&#x1f3fe;', '&#x1faf1;&#x1f3fc;&#x200d;&#x1faf2;&#x1f3ff;', '&#x1faf1;&#x1f3fd;&#x200d;&#x1faf2;&#x1f3fb;', '&#x1faf1;&#x1f3fd;&#x200d;&#x1faf2;&#x1f3fc;', '&#x1faf1;&#x1f3fd;&#x200d;&#x1faf2;&#x1f3fe;', '&#x1faf1;&#x1f3fd;&#x200d;&#x1faf2;&#x1f3ff;', '&#x1faf1;&#x1f3fe;&#x200d;&#x1faf2;&#x1f3fb;', '&#x1faf1;&#x1f3fe;&#x200d;&#x1faf2;&#x1f3fc;', '&#x1faf1;&#x1f3fe;&#x200d;&#x1faf2;&#x1f3fd;', '&#x1faf1;&#x1f3fe;&#x200d;&#x1faf2;&#x1f3ff;', '&#x1faf1;&#x1f3ff;&#x200d;&#x1faf2;&#x1f3fb;', '&#x1faf1;&#x1f3ff;&#x200d;&#x1faf2;&#x1f3fc;', '&#x1faf1;&#x1f3ff;&#x200d;&#x1faf2;&#x1f3fd;', '&#x1faf1;&#x1f3ff;&#x200d;&#x1faf2;&#x1f3fe;', '&#x1f468;&#x200d;&#x1f466;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f467;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f467;&#x200d;&#x1f467;', '&#x1f468;&#x200d;&#x1f468;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f468;&#x200d;&#x1f467;', '&#x1f468;&#x200d;&#x1f469;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f469;&#x200d;&#x1f467;', '&#x1f469;&#x200d;&#x1f466;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f467;&#x200d;&#x1f467;', '&#x1f469;&#x200d;&#x1f469;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f469;&#x200d;&#x1f467;', '&#x1f9d1;&#x200d;&#x1f91d;&#x200d;&#x1f9d1;', '&#x1f9d1;&#x200d;&#x1f9d1;&#x200d;&#x1f9d2;', '&#x1f9d1;&#x200d;&#x1f9d2;&#x200d;&#x1f9d2;', '&#x1f3c3;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c3;&#x1f3fb;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c3;&#x1f3fc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c3;&#x1f3fd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c3;&#x1f3fe;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c3;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c3;&#x1f3ff;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c4;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c4;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c4;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c4;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c4;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f468;&#x1f3fb;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x1f3fb;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x1f3fb;&#x200d;&#x2708;&#xfe0f;', '&#x1f468;&#x1f3fc;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x1f3fc;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x1f3fc;&#x200d;&#x2708;&#xfe0f;', '&#x1f468;&#x1f3fd;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x1f3fd;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x1f3fd;&#x200d;&#x2708;&#xfe0f;', '&#x1f468;&#x1f3fe;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x1f3fe;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x1f3fe;&#x200d;&#x2708;&#xfe0f;', '&#x1f468;&#x1f3ff;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x1f3ff;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x1f3ff;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x1f3fb;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x1f3fb;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x1f3fb;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x1f3fc;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x1f3fc;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x1f3fc;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x1f3fd;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x1f3fd;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x1f3fd;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x1f3fe;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x1f3fe;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x1f3fe;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x1f3ff;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x1f3ff;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x1f3ff;&#x200d;&#x2708;&#xfe0f;', '&#x1f46e;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f46e;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f46e;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f46e;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f46e;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f574;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f574;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f574;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f574;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f574;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f645;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f645;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f645;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f645;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f645;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x1f3fb;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x1f3fc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x1f3fd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x1f3fe;&#x200d;&#x27a1;&#xfe0f;', '&#x1f6b6;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x1f3ff;&#x200d;&#x27a1;&#xfe0f;', '&#x1f926;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f926;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f926;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f926;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f926;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x1f3fb;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x1f3fc;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x1f3fd;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x1f3fe;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9ce;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x1f3ff;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9cf;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cf;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cf;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cf;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cf;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d4;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d4;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d4;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d4;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d4;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d4;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d4;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d4;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d4;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d4;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d6;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d6;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d6;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d6;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d6;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cb;&#xfe0f;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cb;&#xfe0f;&#x200d;&#x2642;&#xfe0f;', '&#x1f3cc;&#xfe0f;&#x200d;&#x2640;&#xfe0f;', '&#x1f3cc;&#xfe0f;&#x200d;&#x2642;&#xfe0f;', '&#x1f3f3;&#xfe0f;&#x200d;&#x26a7;&#xfe0f;', '&#x1f574;&#xfe0f;&#x200d;&#x2640;&#xfe0f;', '&#x1f574;&#xfe0f;&#x200d;&#x2642;&#xfe0f;', '&#x1f575;&#xfe0f;&#x200d;&#x2640;&#xfe0f;', '&#x1f575;&#xfe0f;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#x1f3fb;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#x1f3fb;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#x1f3fc;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#x1f3fc;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#x1f3fd;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#x1f3fd;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#x1f3fe;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#x1f3fe;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#x1f3ff;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#x1f3ff;&#x200d;&#x2642;&#xfe0f;', '&#x26f9;&#xfe0f;&#x200d;&#x2640;&#xfe0f;', '&#x26f9;&#xfe0f;&#x200d;&#x2642;&#xfe0f;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f33e;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f373;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f37c;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f384;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f393;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f3a4;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f3a8;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f3eb;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f3ed;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f4bb;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f4bc;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f527;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f52c;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f680;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f692;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9af;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9b0;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9b1;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9b2;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9b3;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9bc;', '&#x1f468;&#x1f3fb;&#x200d;&#x1f9bd;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f33e;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f373;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f37c;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f384;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f393;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f3a4;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f3a8;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f3eb;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f3ed;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f4bb;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f4bc;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f527;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f52c;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f680;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f692;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9af;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9b0;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9b1;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9b2;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9b3;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9bc;', '&#x1f468;&#x1f3fc;&#x200d;&#x1f9bd;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f33e;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f373;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f37c;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f384;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f393;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f3a4;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f3a8;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f3eb;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f3ed;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f4bb;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f4bc;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f527;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f52c;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f680;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f692;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9af;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9b0;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9b1;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9b2;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9b3;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9bc;', '&#x1f468;&#x1f3fd;&#x200d;&#x1f9bd;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f33e;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f373;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f37c;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f384;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f393;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f3a4;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f3a8;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f3eb;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f3ed;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f4bb;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f4bc;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f527;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f52c;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f680;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f692;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9af;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9b0;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9b1;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9b2;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9b3;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9bc;', '&#x1f468;&#x1f3fe;&#x200d;&#x1f9bd;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f33e;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f373;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f37c;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f384;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f393;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f3a4;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f3a8;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f3eb;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f3ed;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f4bb;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f4bc;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f527;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f52c;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f680;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f692;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9af;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9b0;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9b1;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9b2;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9b3;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9bc;', '&#x1f468;&#x1f3ff;&#x200d;&#x1f9bd;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f33e;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f373;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f37c;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f384;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f393;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f3a4;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f3a8;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f3eb;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f3ed;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f4bb;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f4bc;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f527;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f52c;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f680;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f692;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9af;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9b0;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9b1;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9b2;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9b3;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9bc;', '&#x1f469;&#x1f3fb;&#x200d;&#x1f9bd;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f33e;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f373;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f37c;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f384;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f393;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f3a4;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f3a8;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f3eb;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f3ed;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f4bb;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f4bc;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f527;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f52c;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f680;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f692;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9af;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9b0;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9b1;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9b2;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9b3;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9bc;', '&#x1f469;&#x1f3fc;&#x200d;&#x1f9bd;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f33e;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f373;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f37c;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f384;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f393;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f3a4;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f3a8;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f3eb;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f3ed;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f4bb;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f4bc;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f527;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f52c;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f680;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f692;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9af;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9b0;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9b1;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9b2;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9b3;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9bc;', '&#x1f469;&#x1f3fd;&#x200d;&#x1f9bd;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f33e;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f373;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f37c;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f384;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f393;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f3a4;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f3a8;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f3eb;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f3ed;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f4bb;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f4bc;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f527;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f52c;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f680;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f692;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9af;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9b0;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9b1;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9b2;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9b3;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9bc;', '&#x1f469;&#x1f3fe;&#x200d;&#x1f9bd;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f33e;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f373;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f37c;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f384;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f393;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f3a4;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f3a8;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f3eb;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f3ed;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f4bb;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f4bc;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f527;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f52c;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f680;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f692;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9af;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9b0;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9b1;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9b2;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9b3;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9bc;', '&#x1f469;&#x1f3ff;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f33e;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f373;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f37c;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f384;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f393;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f527;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f52c;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f680;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f692;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9af;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x1f3fb;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f33e;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f373;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f37c;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f384;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f393;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f527;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f52c;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f680;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f692;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9af;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x1f3fc;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f33e;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f373;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f37c;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f384;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f393;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f527;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f52c;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f680;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f692;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9af;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x1f3fd;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f33e;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f373;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f37c;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f384;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f393;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f527;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f52c;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f680;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f692;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9af;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x1f3fe;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f33e;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f373;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f37c;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f384;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f393;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f527;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f52c;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f680;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f692;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9af;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x1f3ff;&#x200d;&#x1f9bd;', '&#x1f3f3;&#xfe0f;&#x200d;&#x1f308;', '&#x1f636;&#x200d;&#x1f32b;&#xfe0f;', '&#x1f3c3;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c3;&#x200d;&#x2642;&#xfe0f;', '&#x1f3c3;&#x200d;&#x27a1;&#xfe0f;', '&#x1f3c4;&#x200d;&#x2640;&#xfe0f;', '&#x1f3c4;&#x200d;&#x2642;&#xfe0f;', '&#x1f3ca;&#x200d;&#x2640;&#xfe0f;', '&#x1f3ca;&#x200d;&#x2642;&#xfe0f;', '&#x1f3f4;&#x200d;&#x2620;&#xfe0f;', '&#x1f43b;&#x200d;&#x2744;&#xfe0f;', '&#x1f468;&#x200d;&#x2695;&#xfe0f;', '&#x1f468;&#x200d;&#x2696;&#xfe0f;', '&#x1f468;&#x200d;&#x2708;&#xfe0f;', '&#x1f469;&#x200d;&#x2695;&#xfe0f;', '&#x1f469;&#x200d;&#x2696;&#xfe0f;', '&#x1f469;&#x200d;&#x2708;&#xfe0f;', '&#x1f46e;&#x200d;&#x2640;&#xfe0f;', '&#x1f46e;&#x200d;&#x2642;&#xfe0f;', '&#x1f46f;&#x200d;&#x2640;&#xfe0f;', '&#x1f46f;&#x200d;&#x2642;&#xfe0f;', '&#x1f470;&#x200d;&#x2640;&#xfe0f;', '&#x1f470;&#x200d;&#x2642;&#xfe0f;', '&#x1f471;&#x200d;&#x2640;&#xfe0f;', '&#x1f471;&#x200d;&#x2642;&#xfe0f;', '&#x1f473;&#x200d;&#x2640;&#xfe0f;', '&#x1f473;&#x200d;&#x2642;&#xfe0f;', '&#x1f477;&#x200d;&#x2640;&#xfe0f;', '&#x1f477;&#x200d;&#x2642;&#xfe0f;', '&#x1f481;&#x200d;&#x2640;&#xfe0f;', '&#x1f481;&#x200d;&#x2642;&#xfe0f;', '&#x1f482;&#x200d;&#x2640;&#xfe0f;', '&#x1f482;&#x200d;&#x2642;&#xfe0f;', '&#x1f486;&#x200d;&#x2640;&#xfe0f;', '&#x1f486;&#x200d;&#x2642;&#xfe0f;', '&#x1f487;&#x200d;&#x2640;&#xfe0f;', '&#x1f487;&#x200d;&#x2642;&#xfe0f;', '&#x1f642;&#x200d;&#x2194;&#xfe0f;', '&#x1f642;&#x200d;&#x2195;&#xfe0f;', '&#x1f645;&#x200d;&#x2640;&#xfe0f;', '&#x1f645;&#x200d;&#x2642;&#xfe0f;', '&#x1f646;&#x200d;&#x2640;&#xfe0f;', '&#x1f646;&#x200d;&#x2642;&#xfe0f;', '&#x1f647;&#x200d;&#x2640;&#xfe0f;', '&#x1f647;&#x200d;&#x2642;&#xfe0f;', '&#x1f64b;&#x200d;&#x2640;&#xfe0f;', '&#x1f64b;&#x200d;&#x2642;&#xfe0f;', '&#x1f64d;&#x200d;&#x2640;&#xfe0f;', '&#x1f64d;&#x200d;&#x2642;&#xfe0f;', '&#x1f64e;&#x200d;&#x2640;&#xfe0f;', '&#x1f64e;&#x200d;&#x2642;&#xfe0f;', '&#x1f6a3;&#x200d;&#x2640;&#xfe0f;', '&#x1f6a3;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b4;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b4;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b5;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b5;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x200d;&#x2640;&#xfe0f;', '&#x1f6b6;&#x200d;&#x2642;&#xfe0f;', '&#x1f6b6;&#x200d;&#x27a1;&#xfe0f;', '&#x1f926;&#x200d;&#x2640;&#xfe0f;', '&#x1f926;&#x200d;&#x2642;&#xfe0f;', '&#x1f935;&#x200d;&#x2640;&#xfe0f;', '&#x1f935;&#x200d;&#x2642;&#xfe0f;', '&#x1f937;&#x200d;&#x2640;&#xfe0f;', '&#x1f937;&#x200d;&#x2642;&#xfe0f;', '&#x1f938;&#x200d;&#x2640;&#xfe0f;', '&#x1f938;&#x200d;&#x2642;&#xfe0f;', '&#x1f939;&#x200d;&#x2640;&#xfe0f;', '&#x1f939;&#x200d;&#x2642;&#xfe0f;', '&#x1f93c;&#x200d;&#x2640;&#xfe0f;', '&#x1f93c;&#x200d;&#x2642;&#xfe0f;', '&#x1f93d;&#x200d;&#x2640;&#xfe0f;', '&#x1f93d;&#x200d;&#x2642;&#xfe0f;', '&#x1f93e;&#x200d;&#x2640;&#xfe0f;', '&#x1f93e;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b8;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b8;&#x200d;&#x2642;&#xfe0f;', '&#x1f9b9;&#x200d;&#x2640;&#xfe0f;', '&#x1f9b9;&#x200d;&#x2642;&#xfe0f;', '&#x1f9cd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x200d;&#x2640;&#xfe0f;', '&#x1f9ce;&#x200d;&#x2642;&#xfe0f;', '&#x1f9ce;&#x200d;&#x27a1;&#xfe0f;', '&#x1f9cf;&#x200d;&#x2640;&#xfe0f;', '&#x1f9cf;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d1;&#x200d;&#x2695;&#xfe0f;', '&#x1f9d1;&#x200d;&#x2696;&#xfe0f;', '&#x1f9d1;&#x200d;&#x2708;&#xfe0f;', '&#x1f9d4;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d4;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d6;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d6;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d7;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d7;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d8;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d8;&#x200d;&#x2642;&#xfe0f;', '&#x1f9d9;&#x200d;&#x2640;&#xfe0f;', '&#x1f9d9;&#x200d;&#x2642;&#xfe0f;', '&#x1f9da;&#x200d;&#x2640;&#xfe0f;', '&#x1f9da;&#x200d;&#x2642;&#xfe0f;', '&#x1f9db;&#x200d;&#x2640;&#xfe0f;', '&#x1f9db;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dc;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dc;&#x200d;&#x2642;&#xfe0f;', '&#x1f9dd;&#x200d;&#x2640;&#xfe0f;', '&#x1f9dd;&#x200d;&#x2642;&#xfe0f;', '&#x1f9de;&#x200d;&#x2640;&#xfe0f;', '&#x1f9de;&#x200d;&#x2642;&#xfe0f;', '&#x1f9df;&#x200d;&#x2640;&#xfe0f;', '&#x1f9df;&#x200d;&#x2642;&#xfe0f;', '&#x26d3;&#xfe0f;&#x200d;&#x1f4a5;', '&#x2764;&#xfe0f;&#x200d;&#x1f525;', '&#x2764;&#xfe0f;&#x200d;&#x1fa79;', '&#x1f344;&#x200d;&#x1f7eb;', '&#x1f34b;&#x200d;&#x1f7e9;', '&#x1f415;&#x200d;&#x1f9ba;', '&#x1f426;&#x200d;&#x1f525;', '&#x1f441;&#x200d;&#x1f5e8;', '&#x1f468;&#x200d;&#x1f33e;', '&#x1f468;&#x200d;&#x1f373;', '&#x1f468;&#x200d;&#x1f37c;', '&#x1f468;&#x200d;&#x1f384;', '&#x1f468;&#x200d;&#x1f393;', '&#x1f468;&#x200d;&#x1f3a4;', '&#x1f468;&#x200d;&#x1f3a8;', '&#x1f468;&#x200d;&#x1f3eb;', '&#x1f468;&#x200d;&#x1f3ed;', '&#x1f468;&#x200d;&#x1f466;', '&#x1f468;&#x200d;&#x1f467;', '&#x1f468;&#x200d;&#x1f4bb;', '&#x1f468;&#x200d;&#x1f4bc;', '&#x1f468;&#x200d;&#x1f527;', '&#x1f468;&#x200d;&#x1f52c;', '&#x1f468;&#x200d;&#x1f680;', '&#x1f468;&#x200d;&#x1f692;', '&#x1f468;&#x200d;&#x1f9af;', '&#x1f468;&#x200d;&#x1f9b0;', '&#x1f468;&#x200d;&#x1f9b1;', '&#x1f468;&#x200d;&#x1f9b2;', '&#x1f468;&#x200d;&#x1f9b3;', '&#x1f468;&#x200d;&#x1f9bc;', '&#x1f468;&#x200d;&#x1f9bd;', '&#x1f469;&#x200d;&#x1f33e;', '&#x1f469;&#x200d;&#x1f373;', '&#x1f469;&#x200d;&#x1f37c;', '&#x1f469;&#x200d;&#x1f384;', '&#x1f469;&#x200d;&#x1f393;', '&#x1f469;&#x200d;&#x1f3a4;', '&#x1f469;&#x200d;&#x1f3a8;', '&#x1f469;&#x200d;&#x1f3eb;', '&#x1f469;&#x200d;&#x1f3ed;', '&#x1f469;&#x200d;&#x1f466;', '&#x1f469;&#x200d;&#x1f467;', '&#x1f469;&#x200d;&#x1f4bb;', '&#x1f469;&#x200d;&#x1f4bc;', '&#x1f469;&#x200d;&#x1f527;', '&#x1f469;&#x200d;&#x1f52c;', '&#x1f469;&#x200d;&#x1f680;', '&#x1f469;&#x200d;&#x1f692;', '&#x1f469;&#x200d;&#x1f9af;', '&#x1f469;&#x200d;&#x1f9b0;', '&#x1f469;&#x200d;&#x1f9b1;', '&#x1f469;&#x200d;&#x1f9b2;', '&#x1f469;&#x200d;&#x1f9b3;', '&#x1f469;&#x200d;&#x1f9bc;', '&#x1f469;&#x200d;&#x1f9bd;', '&#x1f62e;&#x200d;&#x1f4a8;', '&#x1f635;&#x200d;&#x1f4ab;', '&#x1f9d1;&#x200d;&#x1f33e;', '&#x1f9d1;&#x200d;&#x1f373;', '&#x1f9d1;&#x200d;&#x1f37c;', '&#x1f9d1;&#x200d;&#x1f384;', '&#x1f9d1;&#x200d;&#x1f393;', '&#x1f9d1;&#x200d;&#x1f3a4;', '&#x1f9d1;&#x200d;&#x1f3a8;', '&#x1f9d1;&#x200d;&#x1f3eb;', '&#x1f9d1;&#x200d;&#x1f3ed;', '&#x1f9d1;&#x200d;&#x1f4bb;', '&#x1f9d1;&#x200d;&#x1f4bc;', '&#x1f9d1;&#x200d;&#x1f527;', '&#x1f9d1;&#x200d;&#x1f52c;', '&#x1f9d1;&#x200d;&#x1f680;', '&#x1f9d1;&#x200d;&#x1f692;', '&#x1f9d1;&#x200d;&#x1f9af;', '&#x1f9d1;&#x200d;&#x1f9b0;', '&#x1f9d1;&#x200d;&#x1f9b1;', '&#x1f9d1;&#x200d;&#x1f9b2;', '&#x1f9d1;&#x200d;&#x1f9b3;', '&#x1f9d1;&#x200d;&#x1f9bc;', '&#x1f9d1;&#x200d;&#x1f9bd;', '&#x1f9d1;&#x200d;&#x1f9d2;', '&#x1f408;&#x200d;&#x2b1b;', '&#x1f426;&#x200d;&#x2b1b;', '&#x1f1e6;&#x1f1e8;', '&#x1f1e6;&#x1f1e9;', '&#x1f1e6;&#x1f1ea;', '&#x1f1e6;&#x1f1eb;', '&#x1f1e6;&#x1f1ec;', '&#x1f1e6;&#x1f1ee;', '&#x1f1e6;&#x1f1f1;', '&#x1f1e6;&#x1f1f2;', '&#x1f1e6;&#x1f1f4;', '&#x1f1e6;&#x1f1f6;', '&#x1f1e6;&#x1f1f7;', '&#x1f1e6;&#x1f1f8;', '&#x1f1e6;&#x1f1f9;', '&#x1f1e6;&#x1f1fa;', '&#x1f1e6;&#x1f1fc;', '&#x1f1e6;&#x1f1fd;', '&#x1f1e6;&#x1f1ff;', '&#x1f1e7;&#x1f1e6;', '&#x1f1e7;&#x1f1e7;', '&#x1f1e7;&#x1f1e9;', '&#x1f1e7;&#x1f1ea;', '&#x1f1e7;&#x1f1eb;', '&#x1f1e7;&#x1f1ec;', '&#x1f1e7;&#x1f1ed;', '&#x1f1e7;&#x1f1ee;', '&#x1f1e7;&#x1f1ef;', '&#x1f1e7;&#x1f1f1;', '&#x1f1e7;&#x1f1f2;', '&#x1f1e7;&#x1f1f3;', '&#x1f1e7;&#x1f1f4;', '&#x1f1e7;&#x1f1f6;', '&#x1f1e7;&#x1f1f7;', '&#x1f1e7;&#x1f1f8;', '&#x1f1e7;&#x1f1f9;', '&#x1f1e7;&#x1f1fb;', '&#x1f1e7;&#x1f1fc;', '&#x1f1e7;&#x1f1fe;', '&#x1f1e7;&#x1f1ff;', '&#x1f1e8;&#x1f1e6;', '&#x1f1e8;&#x1f1e8;', '&#x1f1e8;&#x1f1e9;', '&#x1f1e8;&#x1f1eb;', '&#x1f1e8;&#x1f1ec;', '&#x1f1e8;&#x1f1ed;', '&#x1f1e8;&#x1f1ee;', '&#x1f1e8;&#x1f1f0;', '&#x1f1e8;&#x1f1f1;', '&#x1f1e8;&#x1f1f2;', '&#x1f1e8;&#x1f1f3;', '&#x1f1e8;&#x1f1f4;', '&#x1f1e8;&#x1f1f5;', '&#x1f1e8;&#x1f1f7;', '&#x1f1e8;&#x1f1fa;', '&#x1f1e8;&#x1f1fb;', '&#x1f1e8;&#x1f1fc;', '&#x1f1e8;&#x1f1fd;', '&#x1f1e8;&#x1f1fe;', '&#x1f1e8;&#x1f1ff;', '&#x1f1e9;&#x1f1ea;', '&#x1f1e9;&#x1f1ec;', '&#x1f1e9;&#x1f1ef;', '&#x1f1e9;&#x1f1f0;', '&#x1f1e9;&#x1f1f2;', '&#x1f1e9;&#x1f1f4;', '&#x1f1e9;&#x1f1ff;', '&#x1f1ea;&#x1f1e6;', '&#x1f1ea;&#x1f1e8;', '&#x1f1ea;&#x1f1ea;', '&#x1f1ea;&#x1f1ec;', '&#x1f1ea;&#x1f1ed;', '&#x1f1ea;&#x1f1f7;', '&#x1f1ea;&#x1f1f8;', '&#x1f1ea;&#x1f1f9;', '&#x1f1ea;&#x1f1fa;', '&#x1f1eb;&#x1f1ee;', '&#x1f1eb;&#x1f1ef;', '&#x1f1eb;&#x1f1f0;', '&#x1f1eb;&#x1f1f2;', '&#x1f1eb;&#x1f1f4;', '&#x1f1eb;&#x1f1f7;', '&#x1f1ec;&#x1f1e6;', '&#x1f1ec;&#x1f1e7;', '&#x1f1ec;&#x1f1e9;', '&#x1f1ec;&#x1f1ea;', '&#x1f1ec;&#x1f1eb;', '&#x1f1ec;&#x1f1ec;', '&#x1f1ec;&#x1f1ed;', '&#x1f1ec;&#x1f1ee;', '&#x1f1ec;&#x1f1f1;', '&#x1f1ec;&#x1f1f2;', '&#x1f1ec;&#x1f1f3;', '&#x1f1ec;&#x1f1f5;', '&#x1f1ec;&#x1f1f6;', '&#x1f1ec;&#x1f1f7;', '&#x1f1ec;&#x1f1f8;', '&#x1f1ec;&#x1f1f9;', '&#x1f1ec;&#x1f1fa;', '&#x1f1ec;&#x1f1fc;', '&#x1f1ec;&#x1f1fe;', '&#x1f1ed;&#x1f1f0;', '&#x1f1ed;&#x1f1f2;', '&#x1f1ed;&#x1f1f3;', '&#x1f1ed;&#x1f1f7;', '&#x1f1ed;&#x1f1f9;', '&#x1f1ed;&#x1f1fa;', '&#x1f1ee;&#x1f1e8;', '&#x1f1ee;&#x1f1e9;', '&#x1f1ee;&#x1f1ea;', '&#x1f1ee;&#x1f1f1;', '&#x1f1ee;&#x1f1f2;', '&#x1f1ee;&#x1f1f3;', '&#x1f1ee;&#x1f1f4;', '&#x1f1ee;&#x1f1f6;', '&#x1f1ee;&#x1f1f7;', '&#x1f1ee;&#x1f1f8;', '&#x1f1ee;&#x1f1f9;', '&#x1f1ef;&#x1f1ea;', '&#x1f1ef;&#x1f1f2;', '&#x1f1ef;&#x1f1f4;', '&#x1f1ef;&#x1f1f5;', '&#x1f1f0;&#x1f1ea;', '&#x1f1f0;&#x1f1ec;', '&#x1f1f0;&#x1f1ed;', '&#x1f1f0;&#x1f1ee;', '&#x1f1f0;&#x1f1f2;', '&#x1f1f0;&#x1f1f3;', '&#x1f1f0;&#x1f1f5;', '&#x1f1f0;&#x1f1f7;', '&#x1f1f0;&#x1f1fc;', '&#x1f1f0;&#x1f1fe;', '&#x1f1f0;&#x1f1ff;', '&#x1f1f1;&#x1f1e6;', '&#x1f1f1;&#x1f1e7;', '&#x1f1f1;&#x1f1e8;', '&#x1f1f1;&#x1f1ee;', '&#x1f1f1;&#x1f1f0;', '&#x1f1f1;&#x1f1f7;', '&#x1f1f1;&#x1f1f8;', '&#x1f1f1;&#x1f1f9;', '&#x1f1f1;&#x1f1fa;', '&#x1f1f1;&#x1f1fb;', '&#x1f1f1;&#x1f1fe;', '&#x1f1f2;&#x1f1e6;', '&#x1f1f2;&#x1f1e8;', '&#x1f1f2;&#x1f1e9;', '&#x1f1f2;&#x1f1ea;', '&#x1f1f2;&#x1f1eb;', '&#x1f1f2;&#x1f1ec;', '&#x1f1f2;&#x1f1ed;', '&#x1f1f2;&#x1f1f0;', '&#x1f1f2;&#x1f1f1;', '&#x1f1f2;&#x1f1f2;', '&#x1f1f2;&#x1f1f3;', '&#x1f1f2;&#x1f1f4;', '&#x1f1f2;&#x1f1f5;', '&#x1f1f2;&#x1f1f6;', '&#x1f1f2;&#x1f1f7;', '&#x1f1f2;&#x1f1f8;', '&#x1f1f2;&#x1f1f9;', '&#x1f1f2;&#x1f1fa;', '&#x1f1f2;&#x1f1fb;', '&#x1f1f2;&#x1f1fc;', '&#x1f1f2;&#x1f1fd;', '&#x1f1f2;&#x1f1fe;', '&#x1f1f2;&#x1f1ff;', '&#x1f1f3;&#x1f1e6;', '&#x1f1f3;&#x1f1e8;', '&#x1f1f3;&#x1f1ea;', '&#x1f1f3;&#x1f1eb;', '&#x1f1f3;&#x1f1ec;', '&#x1f1f3;&#x1f1ee;', '&#x1f1f3;&#x1f1f1;', '&#x1f1f3;&#x1f1f4;', '&#x1f1f3;&#x1f1f5;', '&#x1f1f3;&#x1f1f7;', '&#x1f1f3;&#x1f1fa;', '&#x1f1f3;&#x1f1ff;', '&#x1f1f4;&#x1f1f2;', '&#x1f1f5;&#x1f1e6;', '&#x1f1f5;&#x1f1ea;', '&#x1f1f5;&#x1f1eb;', '&#x1f1f5;&#x1f1ec;', '&#x1f1f5;&#x1f1ed;', '&#x1f1f5;&#x1f1f0;', '&#x1f1f5;&#x1f1f1;', '&#x1f1f5;&#x1f1f2;', '&#x1f1f5;&#x1f1f3;', '&#x1f1f5;&#x1f1f7;', '&#x1f1f5;&#x1f1f8;', '&#x1f1f5;&#x1f1f9;', '&#x1f1f5;&#x1f1fc;', '&#x1f1f5;&#x1f1fe;', '&#x1f1f6;&#x1f1e6;', '&#x1f1f7;&#x1f1ea;', '&#x1f1f7;&#x1f1f4;', '&#x1f1f7;&#x1f1f8;', '&#x1f1f7;&#x1f1fa;', '&#x1f1f7;&#x1f1fc;', '&#x1f1f8;&#x1f1e6;', '&#x1f1f8;&#x1f1e7;', '&#x1f1f8;&#x1f1e8;', '&#x1f1f8;&#x1f1e9;', '&#x1f1f8;&#x1f1ea;', '&#x1f1f8;&#x1f1ec;', '&#x1f1f8;&#x1f1ed;', '&#x1f1f8;&#x1f1ee;', '&#x1f1f8;&#x1f1ef;', '&#x1f1f8;&#x1f1f0;', '&#x1f1f8;&#x1f1f1;', '&#x1f1f8;&#x1f1f2;', '&#x1f1f8;&#x1f1f3;', '&#x1f1f8;&#x1f1f4;', '&#x1f1f8;&#x1f1f7;', '&#x1f1f8;&#x1f1f8;', '&#x1f1f8;&#x1f1f9;', '&#x1f1f8;&#x1f1fb;', '&#x1f1f8;&#x1f1fd;', '&#x1f1f8;&#x1f1fe;', '&#x1f1f8;&#x1f1ff;', '&#x1f1f9;&#x1f1e6;', '&#x1f1f9;&#x1f1e8;', '&#x1f1f9;&#x1f1e9;', '&#x1f1f9;&#x1f1eb;', '&#x1f1f9;&#x1f1ec;', '&#x1f1f9;&#x1f1ed;', '&#x1f1f9;&#x1f1ef;', '&#x1f1f9;&#x1f1f0;', '&#x1f1f9;&#x1f1f1;', '&#x1f1f9;&#x1f1f2;', '&#x1f1f9;&#x1f1f3;', '&#x1f1f9;&#x1f1f4;', '&#x1f1f9;&#x1f1f7;', '&#x1f1f9;&#x1f1f9;', '&#x1f1f9;&#x1f1fb;', '&#x1f1f9;&#x1f1fc;', '&#x1f1f9;&#x1f1ff;', '&#x1f1fa;&#x1f1e6;', '&#x1f1fa;&#x1f1ec;', '&#x1f1fa;&#x1f1f2;', '&#x1f1fa;&#x1f1f3;', '&#x1f1fa;&#x1f1f8;', '&#x1f1fa;&#x1f1fe;', '&#x1f1fa;&#x1f1ff;', '&#x1f1fb;&#x1f1e6;', '&#x1f1fb;&#x1f1e8;', '&#x1f1fb;&#x1f1ea;', '&#x1f1fb;&#x1f1ec;', '&#x1f1fb;&#x1f1ee;', '&#x1f1fb;&#x1f1f3;', '&#x1f1fb;&#x1f1fa;', '&#x1f1fc;&#x1f1eb;', '&#x1f1fc;&#x1f1f8;', '&#x1f1fd;&#x1f1f0;', '&#x1f1fe;&#x1f1ea;', '&#x1f1fe;&#x1f1f9;', '&#x1f1ff;&#x1f1e6;', '&#x1f1ff;&#x1f1f2;', '&#x1f1ff;&#x1f1fc;', '&#x1f385;&#x1f3fb;', '&#x1f385;&#x1f3fc;', '&#x1f385;&#x1f3fd;', '&#x1f385;&#x1f3fe;', '&#x1f385;&#x1f3ff;', '&#x1f3c2;&#x1f3fb;', '&#x1f3c2;&#x1f3fc;', '&#x1f3c2;&#x1f3fd;', '&#x1f3c2;&#x1f3fe;', '&#x1f3c2;&#x1f3ff;', '&#x1f3c3;&#x1f3fb;', '&#x1f3c3;&#x1f3fc;', '&#x1f3c3;&#x1f3fd;', '&#x1f3c3;&#x1f3fe;', '&#x1f3c3;&#x1f3ff;', '&#x1f3c4;&#x1f3fb;', '&#x1f3c4;&#x1f3fc;', '&#x1f3c4;&#x1f3fd;', '&#x1f3c4;&#x1f3fe;', '&#x1f3c4;&#x1f3ff;', '&#x1f3c7;&#x1f3fb;', '&#x1f3c7;&#x1f3fc;', '&#x1f3c7;&#x1f3fd;', '&#x1f3c7;&#x1f3fe;', '&#x1f3c7;&#x1f3ff;', '&#x1f3ca;&#x1f3fb;', '&#x1f3ca;&#x1f3fc;', '&#x1f3ca;&#x1f3fd;', '&#x1f3ca;&#x1f3fe;', '&#x1f3ca;&#x1f3ff;', '&#x1f3cb;&#x1f3fb;', '&#x1f3cb;&#x1f3fc;', '&#x1f3cb;&#x1f3fd;', '&#x1f3cb;&#x1f3fe;', '&#x1f3cb;&#x1f3ff;', '&#x1f3cc;&#x1f3fb;', '&#x1f3cc;&#x1f3fc;', '&#x1f3cc;&#x1f3fd;', '&#x1f3cc;&#x1f3fe;', '&#x1f3cc;&#x1f3ff;', '&#x1f442;&#x1f3fb;', '&#x1f442;&#x1f3fc;', '&#x1f442;&#x1f3fd;', '&#x1f442;&#x1f3fe;', '&#x1f442;&#x1f3ff;', '&#x1f443;&#x1f3fb;', '&#x1f443;&#x1f3fc;', '&#x1f443;&#x1f3fd;', '&#x1f443;&#x1f3fe;', '&#x1f443;&#x1f3ff;', '&#x1f446;&#x1f3fb;', '&#x1f446;&#x1f3fc;', '&#x1f446;&#x1f3fd;', '&#x1f446;&#x1f3fe;', '&#x1f446;&#x1f3ff;', '&#x1f447;&#x1f3fb;', '&#x1f447;&#x1f3fc;', '&#x1f447;&#x1f3fd;', '&#x1f447;&#x1f3fe;', '&#x1f447;&#x1f3ff;', '&#x1f448;&#x1f3fb;', '&#x1f448;&#x1f3fc;', '&#x1f448;&#x1f3fd;', '&#x1f448;&#x1f3fe;', '&#x1f448;&#x1f3ff;', '&#x1f449;&#x1f3fb;', '&#x1f449;&#x1f3fc;', '&#x1f449;&#x1f3fd;', '&#x1f449;&#x1f3fe;', '&#x1f449;&#x1f3ff;', '&#x1f44a;&#x1f3fb;', '&#x1f44a;&#x1f3fc;', '&#x1f44a;&#x1f3fd;', '&#x1f44a;&#x1f3fe;', '&#x1f44a;&#x1f3ff;', '&#x1f44b;&#x1f3fb;', '&#x1f44b;&#x1f3fc;', '&#x1f44b;&#x1f3fd;', '&#x1f44b;&#x1f3fe;', '&#x1f44b;&#x1f3ff;', '&#x1f44c;&#x1f3fb;', '&#x1f44c;&#x1f3fc;', '&#x1f44c;&#x1f3fd;', '&#x1f44c;&#x1f3fe;', '&#x1f44c;&#x1f3ff;', '&#x1f44d;&#x1f3fb;', '&#x1f44d;&#x1f3fc;', '&#x1f44d;&#x1f3fd;', '&#x1f44d;&#x1f3fe;', '&#x1f44d;&#x1f3ff;', '&#x1f44e;&#x1f3fb;', '&#x1f44e;&#x1f3fc;', '&#x1f44e;&#x1f3fd;', '&#x1f44e;&#x1f3fe;', '&#x1f44e;&#x1f3ff;', '&#x1f44f;&#x1f3fb;', '&#x1f44f;&#x1f3fc;', '&#x1f44f;&#x1f3fd;', '&#x1f44f;&#x1f3fe;', '&#x1f44f;&#x1f3ff;', '&#x1f450;&#x1f3fb;', '&#x1f450;&#x1f3fc;', '&#x1f450;&#x1f3fd;', '&#x1f450;&#x1f3fe;', '&#x1f450;&#x1f3ff;', '&#x1f466;&#x1f3fb;', '&#x1f466;&#x1f3fc;', '&#x1f466;&#x1f3fd;', '&#x1f466;&#x1f3fe;', '&#x1f466;&#x1f3ff;', '&#x1f467;&#x1f3fb;', '&#x1f467;&#x1f3fc;', '&#x1f467;&#x1f3fd;', '&#x1f467;&#x1f3fe;', '&#x1f467;&#x1f3ff;', '&#x1f468;&#x1f3fb;', '&#x1f468;&#x1f3fc;', '&#x1f468;&#x1f3fd;', '&#x1f468;&#x1f3fe;', '&#x1f468;&#x1f3ff;', '&#x1f469;&#x1f3fb;', '&#x1f469;&#x1f3fc;', '&#x1f469;&#x1f3fd;', '&#x1f469;&#x1f3fe;', '&#x1f469;&#x1f3ff;', '&#x1f46b;&#x1f3fb;', '&#x1f46b;&#x1f3fc;', '&#x1f46b;&#x1f3fd;', '&#x1f46b;&#x1f3fe;', '&#x1f46b;&#x1f3ff;', '&#x1f46c;&#x1f3fb;', '&#x1f46c;&#x1f3fc;', '&#x1f46c;&#x1f3fd;', '&#x1f46c;&#x1f3fe;', '&#x1f46c;&#x1f3ff;', '&#x1f46d;&#x1f3fb;', '&#x1f46d;&#x1f3fc;', '&#x1f46d;&#x1f3fd;', '&#x1f46d;&#x1f3fe;', '&#x1f46d;&#x1f3ff;', '&#x1f46e;&#x1f3fb;', '&#x1f46e;&#x1f3fc;', '&#x1f46e;&#x1f3fd;', '&#x1f46e;&#x1f3fe;', '&#x1f46e;&#x1f3ff;', '&#x1f470;&#x1f3fb;', '&#x1f470;&#x1f3fc;', '&#x1f470;&#x1f3fd;', '&#x1f470;&#x1f3fe;', '&#x1f470;&#x1f3ff;', '&#x1f471;&#x1f3fb;', '&#x1f471;&#x1f3fc;', '&#x1f471;&#x1f3fd;', '&#x1f471;&#x1f3fe;', '&#x1f471;&#x1f3ff;', '&#x1f472;&#x1f3fb;', '&#x1f472;&#x1f3fc;', '&#x1f472;&#x1f3fd;', '&#x1f472;&#x1f3fe;', '&#x1f472;&#x1f3ff;', '&#x1f473;&#x1f3fb;', '&#x1f473;&#x1f3fc;', '&#x1f473;&#x1f3fd;', '&#x1f473;&#x1f3fe;', '&#x1f473;&#x1f3ff;', '&#x1f474;&#x1f3fb;', '&#x1f474;&#x1f3fc;', '&#x1f474;&#x1f3fd;', '&#x1f474;&#x1f3fe;', '&#x1f474;&#x1f3ff;', '&#x1f475;&#x1f3fb;', '&#x1f475;&#x1f3fc;', '&#x1f475;&#x1f3fd;', '&#x1f475;&#x1f3fe;', '&#x1f475;&#x1f3ff;', '&#x1f476;&#x1f3fb;', '&#x1f476;&#x1f3fc;', '&#x1f476;&#x1f3fd;', '&#x1f476;&#x1f3fe;', '&#x1f476;&#x1f3ff;', '&#x1f477;&#x1f3fb;', '&#x1f477;&#x1f3fc;', '&#x1f477;&#x1f3fd;', '&#x1f477;&#x1f3fe;', '&#x1f477;&#x1f3ff;', '&#x1f478;&#x1f3fb;', '&#x1f478;&#x1f3fc;', '&#x1f478;&#x1f3fd;', '&#x1f478;&#x1f3fe;', '&#x1f478;&#x1f3ff;', '&#x1f47c;&#x1f3fb;', '&#x1f47c;&#x1f3fc;', '&#x1f47c;&#x1f3fd;', '&#x1f47c;&#x1f3fe;', '&#x1f47c;&#x1f3ff;', '&#x1f481;&#x1f3fb;', '&#x1f481;&#x1f3fc;', '&#x1f481;&#x1f3fd;', '&#x1f481;&#x1f3fe;', '&#x1f481;&#x1f3ff;', '&#x1f482;&#x1f3fb;', '&#x1f482;&#x1f3fc;', '&#x1f482;&#x1f3fd;', '&#x1f482;&#x1f3fe;', '&#x1f482;&#x1f3ff;', '&#x1f483;&#x1f3fb;', '&#x1f483;&#x1f3fc;', '&#x1f483;&#x1f3fd;', '&#x1f483;&#x1f3fe;', '&#x1f483;&#x1f3ff;', '&#x1f485;&#x1f3fb;', '&#x1f485;&#x1f3fc;', '&#x1f485;&#x1f3fd;', '&#x1f485;&#x1f3fe;', '&#x1f485;&#x1f3ff;', '&#x1f486;&#x1f3fb;', '&#x1f486;&#x1f3fc;', '&#x1f486;&#x1f3fd;', '&#x1f486;&#x1f3fe;', '&#x1f486;&#x1f3ff;', '&#x1f487;&#x1f3fb;', '&#x1f487;&#x1f3fc;', '&#x1f487;&#x1f3fd;', '&#x1f487;&#x1f3fe;', '&#x1f487;&#x1f3ff;', '&#x1f48f;&#x1f3fb;', '&#x1f48f;&#x1f3fc;', '&#x1f48f;&#x1f3fd;', '&#x1f48f;&#x1f3fe;', '&#x1f48f;&#x1f3ff;', '&#x1f491;&#x1f3fb;', '&#x1f491;&#x1f3fc;', '&#x1f491;&#x1f3fd;', '&#x1f491;&#x1f3fe;', '&#x1f491;&#x1f3ff;', '&#x1f4aa;&#x1f3fb;', '&#x1f4aa;&#x1f3fc;', '&#x1f4aa;&#x1f3fd;', '&#x1f4aa;&#x1f3fe;', '&#x1f4aa;&#x1f3ff;', '&#x1f574;&#x1f3fb;', '&#x1f574;&#x1f3fc;', '&#x1f574;&#x1f3fd;', '&#x1f574;&#x1f3fe;', '&#x1f574;&#x1f3ff;', '&#x1f575;&#x1f3fb;', '&#x1f575;&#x1f3fc;', '&#x1f575;&#x1f3fd;', '&#x1f575;&#x1f3fe;', '&#x1f575;&#x1f3ff;', '&#x1f57a;&#x1f3fb;', '&#x1f57a;&#x1f3fc;', '&#x1f57a;&#x1f3fd;', '&#x1f57a;&#x1f3fe;', '&#x1f57a;&#x1f3ff;', '&#x1f590;&#x1f3fb;', '&#x1f590;&#x1f3fc;', '&#x1f590;&#x1f3fd;', '&#x1f590;&#x1f3fe;', '&#x1f590;&#x1f3ff;', '&#x1f595;&#x1f3fb;', '&#x1f595;&#x1f3fc;', '&#x1f595;&#x1f3fd;', '&#x1f595;&#x1f3fe;', '&#x1f595;&#x1f3ff;', '&#x1f596;&#x1f3fb;', '&#x1f596;&#x1f3fc;', '&#x1f596;&#x1f3fd;', '&#x1f596;&#x1f3fe;', '&#x1f596;&#x1f3ff;', '&#x1f645;&#x1f3fb;', '&#x1f645;&#x1f3fc;', '&#x1f645;&#x1f3fd;', '&#x1f645;&#x1f3fe;', '&#x1f645;&#x1f3ff;', '&#x1f646;&#x1f3fb;', '&#x1f646;&#x1f3fc;', '&#x1f646;&#x1f3fd;', '&#x1f646;&#x1f3fe;', '&#x1f646;&#x1f3ff;', '&#x1f647;&#x1f3fb;', '&#x1f647;&#x1f3fc;', '&#x1f647;&#x1f3fd;', '&#x1f647;&#x1f3fe;', '&#x1f647;&#x1f3ff;', '&#x1f64b;&#x1f3fb;', '&#x1f64b;&#x1f3fc;', '&#x1f64b;&#x1f3fd;', '&#x1f64b;&#x1f3fe;', '&#x1f64b;&#x1f3ff;', '&#x1f64c;&#x1f3fb;', '&#x1f64c;&#x1f3fc;', '&#x1f64c;&#x1f3fd;', '&#x1f64c;&#x1f3fe;', '&#x1f64c;&#x1f3ff;', '&#x1f64d;&#x1f3fb;', '&#x1f64d;&#x1f3fc;', '&#x1f64d;&#x1f3fd;', '&#x1f64d;&#x1f3fe;', '&#x1f64d;&#x1f3ff;', '&#x1f64e;&#x1f3fb;', '&#x1f64e;&#x1f3fc;', '&#x1f64e;&#x1f3fd;', '&#x1f64e;&#x1f3fe;', '&#x1f64e;&#x1f3ff;', '&#x1f64f;&#x1f3fb;', '&#x1f64f;&#x1f3fc;', '&#x1f64f;&#x1f3fd;', '&#x1f64f;&#x1f3fe;', '&#x1f64f;&#x1f3ff;', '&#x1f6a3;&#x1f3fb;', '&#x1f6a3;&#x1f3fc;', '&#x1f6a3;&#x1f3fd;', '&#x1f6a3;&#x1f3fe;', '&#x1f6a3;&#x1f3ff;', '&#x1f6b4;&#x1f3fb;', '&#x1f6b4;&#x1f3fc;', '&#x1f6b4;&#x1f3fd;', '&#x1f6b4;&#x1f3fe;', '&#x1f6b4;&#x1f3ff;', '&#x1f6b5;&#x1f3fb;', '&#x1f6b5;&#x1f3fc;', '&#x1f6b5;&#x1f3fd;', '&#x1f6b5;&#x1f3fe;', '&#x1f6b5;&#x1f3ff;', '&#x1f6b6;&#x1f3fb;', '&#x1f6b6;&#x1f3fc;', '&#x1f6b6;&#x1f3fd;', '&#x1f6b6;&#x1f3fe;', '&#x1f6b6;&#x1f3ff;', '&#x1f6c0;&#x1f3fb;', '&#x1f6c0;&#x1f3fc;', '&#x1f6c0;&#x1f3fd;', '&#x1f6c0;&#x1f3fe;', '&#x1f6c0;&#x1f3ff;', '&#x1f6cc;&#x1f3fb;', '&#x1f6cc;&#x1f3fc;', '&#x1f6cc;&#x1f3fd;', '&#x1f6cc;&#x1f3fe;', '&#x1f6cc;&#x1f3ff;', '&#x1f90c;&#x1f3fb;', '&#x1f90c;&#x1f3fc;', '&#x1f90c;&#x1f3fd;', '&#x1f90c;&#x1f3fe;', '&#x1f90c;&#x1f3ff;', '&#x1f90f;&#x1f3fb;', '&#x1f90f;&#x1f3fc;', '&#x1f90f;&#x1f3fd;', '&#x1f90f;&#x1f3fe;', '&#x1f90f;&#x1f3ff;', '&#x1f918;&#x1f3fb;', '&#x1f918;&#x1f3fc;', '&#x1f918;&#x1f3fd;', '&#x1f918;&#x1f3fe;', '&#x1f918;&#x1f3ff;', '&#x1f919;&#x1f3fb;', '&#x1f919;&#x1f3fc;', '&#x1f919;&#x1f3fd;', '&#x1f919;&#x1f3fe;', '&#x1f919;&#x1f3ff;', '&#x1f91a;&#x1f3fb;', '&#x1f91a;&#x1f3fc;', '&#x1f91a;&#x1f3fd;', '&#x1f91a;&#x1f3fe;', '&#x1f91a;&#x1f3ff;', '&#x1f91b;&#x1f3fb;', '&#x1f91b;&#x1f3fc;', '&#x1f91b;&#x1f3fd;', '&#x1f91b;&#x1f3fe;', '&#x1f91b;&#x1f3ff;', '&#x1f91c;&#x1f3fb;', '&#x1f91c;&#x1f3fc;', '&#x1f91c;&#x1f3fd;', '&#x1f91c;&#x1f3fe;', '&#x1f91c;&#x1f3ff;', '&#x1f91d;&#x1f3fb;', '&#x1f91d;&#x1f3fc;', '&#x1f91d;&#x1f3fd;', '&#x1f91d;&#x1f3fe;', '&#x1f91d;&#x1f3ff;', '&#x1f91e;&#x1f3fb;', '&#x1f91e;&#x1f3fc;', '&#x1f91e;&#x1f3fd;', '&#x1f91e;&#x1f3fe;', '&#x1f91e;&#x1f3ff;', '&#x1f91f;&#x1f3fb;', '&#x1f91f;&#x1f3fc;', '&#x1f91f;&#x1f3fd;', '&#x1f91f;&#x1f3fe;', '&#x1f91f;&#x1f3ff;', '&#x1f926;&#x1f3fb;', '&#x1f926;&#x1f3fc;', '&#x1f926;&#x1f3fd;', '&#x1f926;&#x1f3fe;', '&#x1f926;&#x1f3ff;', '&#x1f930;&#x1f3fb;', '&#x1f930;&#x1f3fc;', '&#x1f930;&#x1f3fd;', '&#x1f930;&#x1f3fe;', '&#x1f930;&#x1f3ff;', '&#x1f931;&#x1f3fb;', '&#x1f931;&#x1f3fc;', '&#x1f931;&#x1f3fd;', '&#x1f931;&#x1f3fe;', '&#x1f931;&#x1f3ff;', '&#x1f932;&#x1f3fb;', '&#x1f932;&#x1f3fc;', '&#x1f932;&#x1f3fd;', '&#x1f932;&#x1f3fe;', '&#x1f932;&#x1f3ff;', '&#x1f933;&#x1f3fb;', '&#x1f933;&#x1f3fc;', '&#x1f933;&#x1f3fd;', '&#x1f933;&#x1f3fe;', '&#x1f933;&#x1f3ff;', '&#x1f934;&#x1f3fb;', '&#x1f934;&#x1f3fc;', '&#x1f934;&#x1f3fd;', '&#x1f934;&#x1f3fe;', '&#x1f934;&#x1f3ff;', '&#x1f935;&#x1f3fb;', '&#x1f935;&#x1f3fc;', '&#x1f935;&#x1f3fd;', '&#x1f935;&#x1f3fe;', '&#x1f935;&#x1f3ff;', '&#x1f936;&#x1f3fb;', '&#x1f936;&#x1f3fc;', '&#x1f936;&#x1f3fd;', '&#x1f936;&#x1f3fe;', '&#x1f936;&#x1f3ff;', '&#x1f937;&#x1f3fb;', '&#x1f937;&#x1f3fc;', '&#x1f937;&#x1f3fd;', '&#x1f937;&#x1f3fe;', '&#x1f937;&#x1f3ff;', '&#x1f938;&#x1f3fb;', '&#x1f938;&#x1f3fc;', '&#x1f938;&#x1f3fd;', '&#x1f938;&#x1f3fe;', '&#x1f938;&#x1f3ff;', '&#x1f939;&#x1f3fb;', '&#x1f939;&#x1f3fc;', '&#x1f939;&#x1f3fd;', '&#x1f939;&#x1f3fe;', '&#x1f939;&#x1f3ff;', '&#x1f93d;&#x1f3fb;', '&#x1f93d;&#x1f3fc;', '&#x1f93d;&#x1f3fd;', '&#x1f93d;&#x1f3fe;', '&#x1f93d;&#x1f3ff;', '&#x1f93e;&#x1f3fb;', '&#x1f93e;&#x1f3fc;', '&#x1f93e;&#x1f3fd;', '&#x1f93e;&#x1f3fe;', '&#x1f93e;&#x1f3ff;', '&#x1f977;&#x1f3fb;', '&#x1f977;&#x1f3fc;', '&#x1f977;&#x1f3fd;', '&#x1f977;&#x1f3fe;', '&#x1f977;&#x1f3ff;', '&#x1f9b5;&#x1f3fb;', '&#x1f9b5;&#x1f3fc;', '&#x1f9b5;&#x1f3fd;', '&#x1f9b5;&#x1f3fe;', '&#x1f9b5;&#x1f3ff;', '&#x1f9b6;&#x1f3fb;', '&#x1f9b6;&#x1f3fc;', '&#x1f9b6;&#x1f3fd;', '&#x1f9b6;&#x1f3fe;', '&#x1f9b6;&#x1f3ff;', '&#x1f9b8;&#x1f3fb;', '&#x1f9b8;&#x1f3fc;', '&#x1f9b8;&#x1f3fd;', '&#x1f9b8;&#x1f3fe;', '&#x1f9b8;&#x1f3ff;', '&#x1f9b9;&#x1f3fb;', '&#x1f9b9;&#x1f3fc;', '&#x1f9b9;&#x1f3fd;', '&#x1f9b9;&#x1f3fe;', '&#x1f9b9;&#x1f3ff;', '&#x1f9bb;&#x1f3fb;', '&#x1f9bb;&#x1f3fc;', '&#x1f9bb;&#x1f3fd;', '&#x1f9bb;&#x1f3fe;', '&#x1f9bb;&#x1f3ff;', '&#x1f9cd;&#x1f3fb;', '&#x1f9cd;&#x1f3fc;', '&#x1f9cd;&#x1f3fd;', '&#x1f9cd;&#x1f3fe;', '&#x1f9cd;&#x1f3ff;', '&#x1f9ce;&#x1f3fb;', '&#x1f9ce;&#x1f3fc;', '&#x1f9ce;&#x1f3fd;', '&#x1f9ce;&#x1f3fe;', '&#x1f9ce;&#x1f3ff;', '&#x1f9cf;&#x1f3fb;', '&#x1f9cf;&#x1f3fc;', '&#x1f9cf;&#x1f3fd;', '&#x1f9cf;&#x1f3fe;', '&#x1f9cf;&#x1f3ff;', '&#x1f9d1;&#x1f3fb;', '&#x1f9d1;&#x1f3fc;', '&#x1f9d1;&#x1f3fd;', '&#x1f9d1;&#x1f3fe;', '&#x1f9d1;&#x1f3ff;', '&#x1f9d2;&#x1f3fb;', '&#x1f9d2;&#x1f3fc;', '&#x1f9d2;&#x1f3fd;', '&#x1f9d2;&#x1f3fe;', '&#x1f9d2;&#x1f3ff;', '&#x1f9d3;&#x1f3fb;', '&#x1f9d3;&#x1f3fc;', '&#x1f9d3;&#x1f3fd;', '&#x1f9d3;&#x1f3fe;', '&#x1f9d3;&#x1f3ff;', '&#x1f9d4;&#x1f3fb;', '&#x1f9d4;&#x1f3fc;', '&#x1f9d4;&#x1f3fd;', '&#x1f9d4;&#x1f3fe;', '&#x1f9d4;&#x1f3ff;', '&#x1f9d5;&#x1f3fb;', '&#x1f9d5;&#x1f3fc;', '&#x1f9d5;&#x1f3fd;', '&#x1f9d5;&#x1f3fe;', '&#x1f9d5;&#x1f3ff;', '&#x1f9d6;&#x1f3fb;', '&#x1f9d6;&#x1f3fc;', '&#x1f9d6;&#x1f3fd;', '&#x1f9d6;&#x1f3fe;', '&#x1f9d6;&#x1f3ff;', '&#x1f9d7;&#x1f3fb;', '&#x1f9d7;&#x1f3fc;', '&#x1f9d7;&#x1f3fd;', '&#x1f9d7;&#x1f3fe;', '&#x1f9d7;&#x1f3ff;', '&#x1f9d8;&#x1f3fb;', '&#x1f9d8;&#x1f3fc;', '&#x1f9d8;&#x1f3fd;', '&#x1f9d8;&#x1f3fe;', '&#x1f9d8;&#x1f3ff;', '&#x1f9d9;&#x1f3fb;', '&#x1f9d9;&#x1f3fc;', '&#x1f9d9;&#x1f3fd;', '&#x1f9d9;&#x1f3fe;', '&#x1f9d9;&#x1f3ff;', '&#x1f9da;&#x1f3fb;', '&#x1f9da;&#x1f3fc;', '&#x1f9da;&#x1f3fd;', '&#x1f9da;&#x1f3fe;', '&#x1f9da;&#x1f3ff;', '&#x1f9db;&#x1f3fb;', '&#x1f9db;&#x1f3fc;', '&#x1f9db;&#x1f3fd;', '&#x1f9db;&#x1f3fe;', '&#x1f9db;&#x1f3ff;', '&#x1f9dc;&#x1f3fb;', '&#x1f9dc;&#x1f3fc;', '&#x1f9dc;&#x1f3fd;', '&#x1f9dc;&#x1f3fe;', '&#x1f9dc;&#x1f3ff;', '&#x1f9dd;&#x1f3fb;', '&#x1f9dd;&#x1f3fc;', '&#x1f9dd;&#x1f3fd;', '&#x1f9dd;&#x1f3fe;', '&#x1f9dd;&#x1f3ff;', '&#x1fac3;&#x1f3fb;', '&#x1fac3;&#x1f3fc;', '&#x1fac3;&#x1f3fd;', '&#x1fac3;&#x1f3fe;', '&#x1fac3;&#x1f3ff;', '&#x1fac4;&#x1f3fb;', '&#x1fac4;&#x1f3fc;', '&#x1fac4;&#x1f3fd;', '&#x1fac4;&#x1f3fe;', '&#x1fac4;&#x1f3ff;', '&#x1fac5;&#x1f3fb;', '&#x1fac5;&#x1f3fc;', '&#x1fac5;&#x1f3fd;', '&#x1fac5;&#x1f3fe;', '&#x1fac5;&#x1f3ff;', '&#x1faf0;&#x1f3fb;', '&#x1faf0;&#x1f3fc;', '&#x1faf0;&#x1f3fd;', '&#x1faf0;&#x1f3fe;', '&#x1faf0;&#x1f3ff;', '&#x1faf1;&#x1f3fb;', '&#x1faf1;&#x1f3fc;', '&#x1faf1;&#x1f3fd;', '&#x1faf1;&#x1f3fe;', '&#x1faf1;&#x1f3ff;', '&#x1faf2;&#x1f3fb;', '&#x1faf2;&#x1f3fc;', '&#x1faf2;&#x1f3fd;', '&#x1faf2;&#x1f3fe;', '&#x1faf2;&#x1f3ff;', '&#x1faf3;&#x1f3fb;', '&#x1faf3;&#x1f3fc;', '&#x1faf3;&#x1f3fd;', '&#x1faf3;&#x1f3fe;', '&#x1faf3;&#x1f3ff;', '&#x1faf4;&#x1f3fb;', '&#x1faf4;&#x1f3fc;', '&#x1faf4;&#x1f3fd;', '&#x1faf4;&#x1f3fe;', '&#x1faf4;&#x1f3ff;', '&#x1faf5;&#x1f3fb;', '&#x1faf5;&#x1f3fc;', '&#x1faf5;&#x1f3fd;', '&#x1faf5;&#x1f3fe;', '&#x1faf5;&#x1f3ff;', '&#x1faf6;&#x1f3fb;', '&#x1faf6;&#x1f3fc;', '&#x1faf6;&#x1f3fd;', '&#x1faf6;&#x1f3fe;', '&#x1faf6;&#x1f3ff;', '&#x1faf7;&#x1f3fb;', '&#x1faf7;&#x1f3fc;', '&#x1faf7;&#x1f3fd;', '&#x1faf7;&#x1f3fe;', '&#x1faf7;&#x1f3ff;', '&#x1faf8;&#x1f3fb;', '&#x1faf8;&#x1f3fc;', '&#x1faf8;&#x1f3fd;', '&#x1faf8;&#x1f3fe;', '&#x1faf8;&#x1f3ff;', '&#x261d;&#x1f3fb;', '&#x261d;&#x1f3fc;', '&#x261d;&#x1f3fd;', '&#x261d;&#x1f3fe;', '&#x261d;&#x1f3ff;', '&#x26f7;&#x1f3fb;', '&#x26f7;&#x1f3fc;', '&#x26f7;&#x1f3fd;', '&#x26f7;&#x1f3fe;', '&#x26f7;&#x1f3ff;', '&#x26f9;&#x1f3fb;', '&#x26f9;&#x1f3fc;', '&#x26f9;&#x1f3fd;', '&#x26f9;&#x1f3fe;', '&#x26f9;&#x1f3ff;', '&#x270a;&#x1f3fb;', '&#x270a;&#x1f3fc;', '&#x270a;&#x1f3fd;', '&#x270a;&#x1f3fe;', '&#x270a;&#x1f3ff;', '&#x270b;&#x1f3fb;', '&#x270b;&#x1f3fc;', '&#x270b;&#x1f3fd;', '&#x270b;&#x1f3fe;', '&#x270b;&#x1f3ff;', '&#x270c;&#x1f3fb;', '&#x270c;&#x1f3fc;', '&#x270c;&#x1f3fd;', '&#x270c;&#x1f3fe;', '&#x270c;&#x1f3ff;', '&#x270d;&#x1f3fb;', '&#x270d;&#x1f3fc;', '&#x270d;&#x1f3fd;', '&#x270d;&#x1f3fe;', '&#x270d;&#x1f3ff;', '&#x23;&#x20e3;', '&#x2a;&#x20e3;', '&#x30;&#x20e3;', '&#x31;&#x20e3;', '&#x32;&#x20e3;', '&#x33;&#x20e3;', '&#x34;&#x20e3;', '&#x35;&#x20e3;', '&#x36;&#x20e3;', '&#x37;&#x20e3;', '&#x38;&#x20e3;', '&#x39;&#x20e3;', '&#x1f004;', '&#x1f0cf;', '&#x1f170;', '&#x1f171;', '&#x1f17e;', '&#x1f17f;', '&#x1f18e;', '&#x1f191;', '&#x1f192;', '&#x1f193;', '&#x1f194;', '&#x1f195;', '&#x1f196;', '&#x1f197;', '&#x1f198;', '&#x1f199;', '&#x1f19a;', '&#x1f1e6;', '&#x1f1e7;', '&#x1f1e8;', '&#x1f1e9;', '&#x1f1ea;', '&#x1f1eb;', '&#x1f1ec;', '&#x1f1ed;', '&#x1f1ee;', '&#x1f1ef;', '&#x1f1f0;', '&#x1f1f1;', '&#x1f1f2;', '&#x1f1f3;', '&#x1f1f4;', '&#x1f1f5;', '&#x1f1f6;', '&#x1f1f7;', '&#x1f1f8;', '&#x1f1f9;', '&#x1f1fa;', '&#x1f1fb;', '&#x1f1fc;', '&#x1f1fd;', '&#x1f1fe;', '&#x1f1ff;', '&#x1f201;', '&#x1f202;', '&#x1f21a;', '&#x1f22f;', '&#x1f232;', '&#x1f233;', '&#x1f234;', '&#x1f235;', '&#x1f236;', '&#x1f237;', '&#x1f238;', '&#x1f239;', '&#x1f23a;', '&#x1f250;', '&#x1f251;', '&#x1f300;', '&#x1f301;', '&#x1f302;', '&#x1f303;', '&#x1f304;', '&#x1f305;', '&#x1f306;', '&#x1f307;', '&#x1f308;', '&#x1f309;', '&#x1f30a;', '&#x1f30b;', '&#x1f30c;', '&#x1f30d;', '&#x1f30e;', '&#x1f30f;', '&#x1f310;', '&#x1f311;', '&#x1f312;', '&#x1f313;', '&#x1f314;', '&#x1f315;', '&#x1f316;', '&#x1f317;', '&#x1f318;', '&#x1f319;', '&#x1f31a;', '&#x1f31b;', '&#x1f31c;', '&#x1f31d;', '&#x1f31e;', '&#x1f31f;', '&#x1f320;', '&#x1f321;', '&#x1f324;', '&#x1f325;', '&#x1f326;', '&#x1f327;', '&#x1f328;', '&#x1f329;', '&#x1f32a;', '&#x1f32b;', '&#x1f32c;', '&#x1f32d;', '&#x1f32e;', '&#x1f32f;', '&#x1f330;', '&#x1f331;', '&#x1f332;', '&#x1f333;', '&#x1f334;', '&#x1f335;', '&#x1f336;', '&#x1f337;', '&#x1f338;', '&#x1f339;', '&#x1f33a;', '&#x1f33b;', '&#x1f33c;', '&#x1f33d;', '&#x1f33e;', '&#x1f33f;', '&#x1f340;', '&#x1f341;', '&#x1f342;', '&#x1f343;', '&#x1f344;', '&#x1f345;', '&#x1f346;', '&#x1f347;', '&#x1f348;', '&#x1f349;', '&#x1f34a;', '&#x1f34b;', '&#x1f34c;', '&#x1f34d;', '&#x1f34e;', '&#x1f34f;', '&#x1f350;', '&#x1f351;', '&#x1f352;', '&#x1f353;', '&#x1f354;', '&#x1f355;', '&#x1f356;', '&#x1f357;', '&#x1f358;', '&#x1f359;', '&#x1f35a;', '&#x1f35b;', '&#x1f35c;', '&#x1f35d;', '&#x1f35e;', '&#x1f35f;', '&#x1f360;', '&#x1f361;', '&#x1f362;', '&#x1f363;', '&#x1f364;', '&#x1f365;', '&#x1f366;', '&#x1f367;', '&#x1f368;', '&#x1f369;', '&#x1f36a;', '&#x1f36b;', '&#x1f36c;', '&#x1f36d;', '&#x1f36e;', '&#x1f36f;', '&#x1f370;', '&#x1f371;', '&#x1f372;', '&#x1f373;', '&#x1f374;', '&#x1f375;', '&#x1f376;', '&#x1f377;', '&#x1f378;', '&#x1f379;', '&#x1f37a;', '&#x1f37b;', '&#x1f37c;', '&#x1f37d;', '&#x1f37e;', '&#x1f37f;', '&#x1f380;', '&#x1f381;', '&#x1f382;', '&#x1f383;', '&#x1f384;', '&#x1f385;', '&#x1f386;', '&#x1f387;', '&#x1f388;', '&#x1f389;', '&#x1f38a;', '&#x1f38b;', '&#x1f38c;', '&#x1f38d;', '&#x1f38e;', '&#x1f38f;', '&#x1f390;', '&#x1f391;', '&#x1f392;', '&#x1f393;', '&#x1f396;', '&#x1f397;', '&#x1f399;', '&#x1f39a;', '&#x1f39b;', '&#x1f39e;', '&#x1f39f;', '&#x1f3a0;', '&#x1f3a1;', '&#x1f3a2;', '&#x1f3a3;', '&#x1f3a4;', '&#x1f3a5;', '&#x1f3a6;', '&#x1f3a7;', '&#x1f3a8;', '&#x1f3a9;', '&#x1f3aa;', '&#x1f3ab;', '&#x1f3ac;', '&#x1f3ad;', '&#x1f3ae;', '&#x1f3af;', '&#x1f3b0;', '&#x1f3b1;', '&#x1f3b2;', '&#x1f3b3;', '&#x1f3b4;', '&#x1f3b5;', '&#x1f3b6;', '&#x1f3b7;', '&#x1f3b8;', '&#x1f3b9;', '&#x1f3ba;', '&#x1f3bb;', '&#x1f3bc;', '&#x1f3bd;', '&#x1f3be;', '&#x1f3bf;', '&#x1f3c0;', '&#x1f3c1;', '&#x1f3c2;', '&#x1f3c3;', '&#x1f3c4;', '&#x1f3c5;', '&#x1f3c6;', '&#x1f3c7;', '&#x1f3c8;', '&#x1f3c9;', '&#x1f3ca;', '&#x1f3cb;', '&#x1f3cc;', '&#x1f3cd;', '&#x1f3ce;', '&#x1f3cf;', '&#x1f3d0;', '&#x1f3d1;', '&#x1f3d2;', '&#x1f3d3;', '&#x1f3d4;', '&#x1f3d5;', '&#x1f3d6;', '&#x1f3d7;', '&#x1f3d8;', '&#x1f3d9;', '&#x1f3da;', '&#x1f3db;', '&#x1f3dc;', '&#x1f3dd;', '&#x1f3de;', '&#x1f3df;', '&#x1f3e0;', '&#x1f3e1;', '&#x1f3e2;', '&#x1f3e3;', '&#x1f3e4;', '&#x1f3e5;', '&#x1f3e6;', '&#x1f3e7;', '&#x1f3e8;', '&#x1f3e9;', '&#x1f3ea;', '&#x1f3eb;', '&#x1f3ec;', '&#x1f3ed;', '&#x1f3ee;', '&#x1f3ef;', '&#x1f3f0;', '&#x1f3f3;', '&#x1f3f4;', '&#x1f3f5;', '&#x1f3f7;', '&#x1f3f8;', '&#x1f3f9;', '&#x1f3fa;', '&#x1f3fb;', '&#x1f3fc;', '&#x1f3fd;', '&#x1f3fe;', '&#x1f3ff;', '&#x1f400;', '&#x1f401;', '&#x1f402;', '&#x1f403;', '&#x1f404;', '&#x1f405;', '&#x1f406;', '&#x1f407;', '&#x1f408;', '&#x1f409;', '&#x1f40a;', '&#x1f40b;', '&#x1f40c;', '&#x1f40d;', '&#x1f40e;', '&#x1f40f;', '&#x1f410;', '&#x1f411;', '&#x1f412;', '&#x1f413;', '&#x1f414;', '&#x1f415;', '&#x1f416;', '&#x1f417;', '&#x1f418;', '&#x1f419;', '&#x1f41a;', '&#x1f41b;', '&#x1f41c;', '&#x1f41d;', '&#x1f41e;', '&#x1f41f;', '&#x1f420;', '&#x1f421;', '&#x1f422;', '&#x1f423;', '&#x1f424;', '&#x1f425;', '&#x1f426;', '&#x1f427;', '&#x1f428;', '&#x1f429;', '&#x1f42a;', '&#x1f42b;', '&#x1f42c;', '&#x1f42d;', '&#x1f42e;', '&#x1f42f;', '&#x1f430;', '&#x1f431;', '&#x1f432;', '&#x1f433;', '&#x1f434;', '&#x1f435;', '&#x1f436;', '&#x1f437;', '&#x1f438;', '&#x1f439;', '&#x1f43a;', '&#x1f43b;', '&#x1f43c;', '&#x1f43d;', '&#x1f43e;', '&#x1f43f;', '&#x1f440;', '&#x1f441;', '&#x1f442;', '&#x1f443;', '&#x1f444;', '&#x1f445;', '&#x1f446;', '&#x1f447;', '&#x1f448;', '&#x1f449;', '&#x1f44a;', '&#x1f44b;', '&#x1f44c;', '&#x1f44d;', '&#x1f44e;', '&#x1f44f;', '&#x1f450;', '&#x1f451;', '&#x1f452;', '&#x1f453;', '&#x1f454;', '&#x1f455;', '&#x1f456;', '&#x1f457;', '&#x1f458;', '&#x1f459;', '&#x1f45a;', '&#x1f45b;', '&#x1f45c;', '&#x1f45d;', '&#x1f45e;', '&#x1f45f;', '&#x1f460;', '&#x1f461;', '&#x1f462;', '&#x1f463;', '&#x1f464;', '&#x1f465;', '&#x1f466;', '&#x1f467;', '&#x1f468;', '&#x1f469;', '&#x1f46a;', '&#x1f46b;', '&#x1f46c;', '&#x1f46d;', '&#x1f46e;', '&#x1f46f;', '&#x1f470;', '&#x1f471;', '&#x1f472;', '&#x1f473;', '&#x1f474;', '&#x1f475;', '&#x1f476;', '&#x1f477;', '&#x1f478;', '&#x1f479;', '&#x1f47a;', '&#x1f47b;', '&#x1f47c;', '&#x1f47d;', '&#x1f47e;', '&#x1f47f;', '&#x1f480;', '&#x1f481;', '&#x1f482;', '&#x1f483;', '&#x1f484;', '&#x1f485;', '&#x1f486;', '&#x1f487;', '&#x1f488;', '&#x1f489;', '&#x1f48a;', '&#x1f48b;', '&#x1f48c;', '&#x1f48d;', '&#x1f48e;', '&#x1f48f;', '&#x1f490;', '&#x1f491;', '&#x1f492;', '&#x1f493;', '&#x1f494;', '&#x1f495;', '&#x1f496;', '&#x1f497;', '&#x1f498;', '&#x1f499;', '&#x1f49a;', '&#x1f49b;', '&#x1f49c;', '&#x1f49d;', '&#x1f49e;', '&#x1f49f;', '&#x1f4a0;', '&#x1f4a1;', '&#x1f4a2;', '&#x1f4a3;', '&#x1f4a4;', '&#x1f4a5;', '&#x1f4a6;', '&#x1f4a7;', '&#x1f4a8;', '&#x1f4a9;', '&#x1f4aa;', '&#x1f4ab;', '&#x1f4ac;', '&#x1f4ad;', '&#x1f4ae;', '&#x1f4af;', '&#x1f4b0;', '&#x1f4b1;', '&#x1f4b2;', '&#x1f4b3;', '&#x1f4b4;', '&#x1f4b5;', '&#x1f4b6;', '&#x1f4b7;', '&#x1f4b8;', '&#x1f4b9;', '&#x1f4ba;', '&#x1f4bb;', '&#x1f4bc;', '&#x1f4bd;', '&#x1f4be;', '&#x1f4bf;', '&#x1f4c0;', '&#x1f4c1;', '&#x1f4c2;', '&#x1f4c3;', '&#x1f4c4;', '&#x1f4c5;', '&#x1f4c6;', '&#x1f4c7;', '&#x1f4c8;', '&#x1f4c9;', '&#x1f4ca;', '&#x1f4cb;', '&#x1f4cc;', '&#x1f4cd;', '&#x1f4ce;', '&#x1f4cf;', '&#x1f4d0;', '&#x1f4d1;', '&#x1f4d2;', '&#x1f4d3;', '&#x1f4d4;', '&#x1f4d5;', '&#x1f4d6;', '&#x1f4d7;', '&#x1f4d8;', '&#x1f4d9;', '&#x1f4da;', '&#x1f4db;', '&#x1f4dc;', '&#x1f4dd;', '&#x1f4de;', '&#x1f4df;', '&#x1f4e0;', '&#x1f4e1;', '&#x1f4e2;', '&#x1f4e3;', '&#x1f4e4;', '&#x1f4e5;', '&#x1f4e6;', '&#x1f4e7;', '&#x1f4e8;', '&#x1f4e9;', '&#x1f4ea;', '&#x1f4eb;', '&#x1f4ec;', '&#x1f4ed;', '&#x1f4ee;', '&#x1f4ef;', '&#x1f4f0;', '&#x1f4f1;', '&#x1f4f2;', '&#x1f4f3;', '&#x1f4f4;', '&#x1f4f5;', '&#x1f4f6;', '&#x1f4f7;', '&#x1f4f8;', '&#x1f4f9;', '&#x1f4fa;', '&#x1f4fb;', '&#x1f4fc;', '&#x1f4fd;', '&#x1f4ff;', '&#x1f500;', '&#x1f501;', '&#x1f502;', '&#x1f503;', '&#x1f504;', '&#x1f505;', '&#x1f506;', '&#x1f507;', '&#x1f508;', '&#x1f509;', '&#x1f50a;', '&#x1f50b;', '&#x1f50c;', '&#x1f50d;', '&#x1f50e;', '&#x1f50f;', '&#x1f510;', '&#x1f511;', '&#x1f512;', '&#x1f513;', '&#x1f514;', '&#x1f515;', '&#x1f516;', '&#x1f517;', '&#x1f518;', '&#x1f519;', '&#x1f51a;', '&#x1f51b;', '&#x1f51c;', '&#x1f51d;', '&#x1f51e;', '&#x1f51f;', '&#x1f520;', '&#x1f521;', '&#x1f522;', '&#x1f523;', '&#x1f524;', '&#x1f525;', '&#x1f526;', '&#x1f527;', '&#x1f528;', '&#x1f529;', '&#x1f52a;', '&#x1f52b;', '&#x1f52c;', '&#x1f52d;', '&#x1f52e;', '&#x1f52f;', '&#x1f530;', '&#x1f531;', '&#x1f532;', '&#x1f533;', '&#x1f534;', '&#x1f535;', '&#x1f536;', '&#x1f537;', '&#x1f538;', '&#x1f539;', '&#x1f53a;', '&#x1f53b;', '&#x1f53c;', '&#x1f53d;', '&#x1f549;', '&#x1f54a;', '&#x1f54b;', '&#x1f54c;', '&#x1f54d;', '&#x1f54e;', '&#x1f550;', '&#x1f551;', '&#x1f552;', '&#x1f553;', '&#x1f554;', '&#x1f555;', '&#x1f556;', '&#x1f557;', '&#x1f558;', '&#x1f559;', '&#x1f55a;', '&#x1f55b;', '&#x1f55c;', '&#x1f55d;', '&#x1f55e;', '&#x1f55f;', '&#x1f560;', '&#x1f561;', '&#x1f562;', '&#x1f563;', '&#x1f564;', '&#x1f565;', '&#x1f566;', '&#x1f567;', '&#x1f56f;', '&#x1f570;', '&#x1f573;', '&#x1f574;', '&#x1f575;', '&#x1f576;', '&#x1f577;', '&#x1f578;', '&#x1f579;', '&#x1f57a;', '&#x1f587;', '&#x1f58a;', '&#x1f58b;', '&#x1f58c;', '&#x1f58d;', '&#x1f590;', '&#x1f595;', '&#x1f596;', '&#x1f5a4;', '&#x1f5a5;', '&#x1f5a8;', '&#x1f5b1;', '&#x1f5b2;', '&#x1f5bc;', '&#x1f5c2;', '&#x1f5c3;', '&#x1f5c4;', '&#x1f5d1;', '&#x1f5d2;', '&#x1f5d3;', '&#x1f5dc;', '&#x1f5dd;', '&#x1f5de;', '&#x1f5e1;', '&#x1f5e3;', '&#x1f5e8;', '&#x1f5ef;', '&#x1f5f3;', '&#x1f5fa;', '&#x1f5fb;', '&#x1f5fc;', '&#x1f5fd;', '&#x1f5fe;', '&#x1f5ff;', '&#x1f600;', '&#x1f601;', '&#x1f602;', '&#x1f603;', '&#x1f604;', '&#x1f605;', '&#x1f606;', '&#x1f607;', '&#x1f608;', '&#x1f609;', '&#x1f60a;', '&#x1f60b;', '&#x1f60c;', '&#x1f60d;', '&#x1f60e;', '&#x1f60f;', '&#x1f610;', '&#x1f611;', '&#x1f612;', '&#x1f613;', '&#x1f614;', '&#x1f615;', '&#x1f616;', '&#x1f617;', '&#x1f618;', '&#x1f619;', '&#x1f61a;', '&#x1f61b;', '&#x1f61c;', '&#x1f61d;', '&#x1f61e;', '&#x1f61f;', '&#x1f620;', '&#x1f621;', '&#x1f622;', '&#x1f623;', '&#x1f624;', '&#x1f625;', '&#x1f626;', '&#x1f627;', '&#x1f628;', '&#x1f629;', '&#x1f62a;', '&#x1f62b;', '&#x1f62c;', '&#x1f62d;', '&#x1f62e;', '&#x1f62f;', '&#x1f630;', '&#x1f631;', '&#x1f632;', '&#x1f633;', '&#x1f634;', '&#x1f635;', '&#x1f636;', '&#x1f637;', '&#x1f638;', '&#x1f639;', '&#x1f63a;', '&#x1f63b;', '&#x1f63c;', '&#x1f63d;', '&#x1f63e;', '&#x1f63f;', '&#x1f640;', '&#x1f641;', '&#x1f642;', '&#x1f643;', '&#x1f644;', '&#x1f645;', '&#x1f646;', '&#x1f647;', '&#x1f648;', '&#x1f649;', '&#x1f64a;', '&#x1f64b;', '&#x1f64c;', '&#x1f64d;', '&#x1f64e;', '&#x1f64f;', '&#x1f680;', '&#x1f681;', '&#x1f682;', '&#x1f683;', '&#x1f684;', '&#x1f685;', '&#x1f686;', '&#x1f687;', '&#x1f688;', '&#x1f689;', '&#x1f68a;', '&#x1f68b;', '&#x1f68c;', '&#x1f68d;', '&#x1f68e;', '&#x1f68f;', '&#x1f690;', '&#x1f691;', '&#x1f692;', '&#x1f693;', '&#x1f694;', '&#x1f695;', '&#x1f696;', '&#x1f697;', '&#x1f698;', '&#x1f699;', '&#x1f69a;', '&#x1f69b;', '&#x1f69c;', '&#x1f69d;', '&#x1f69e;', '&#x1f69f;', '&#x1f6a0;', '&#x1f6a1;', '&#x1f6a2;', '&#x1f6a3;', '&#x1f6a4;', '&#x1f6a5;', '&#x1f6a6;', '&#x1f6a7;', '&#x1f6a8;', '&#x1f6a9;', '&#x1f6aa;', '&#x1f6ab;', '&#x1f6ac;', '&#x1f6ad;', '&#x1f6ae;', '&#x1f6af;', '&#x1f6b0;', '&#x1f6b1;', '&#x1f6b2;', '&#x1f6b3;', '&#x1f6b4;', '&#x1f6b5;', '&#x1f6b6;', '&#x1f6b7;', '&#x1f6b8;', '&#x1f6b9;', '&#x1f6ba;', '&#x1f6bb;', '&#x1f6bc;', '&#x1f6bd;', '&#x1f6be;', '&#x1f6bf;', '&#x1f6c0;', '&#x1f6c1;', '&#x1f6c2;', '&#x1f6c3;', '&#x1f6c4;', '&#x1f6c5;', '&#x1f6cb;', '&#x1f6cc;', '&#x1f6cd;', '&#x1f6ce;', '&#x1f6cf;', '&#x1f6d0;', '&#x1f6d1;', '&#x1f6d2;', '&#x1f6d5;', '&#x1f6d6;', '&#x1f6d7;', '&#x1f6dc;', '&#x1f6dd;', '&#x1f6de;', '&#x1f6df;', '&#x1f6e0;', '&#x1f6e1;', '&#x1f6e2;', '&#x1f6e3;', '&#x1f6e4;', '&#x1f6e5;', '&#x1f6e9;', '&#x1f6eb;', '&#x1f6ec;', '&#x1f6f0;', '&#x1f6f3;', '&#x1f6f4;', '&#x1f6f5;', '&#x1f6f6;', '&#x1f6f7;', '&#x1f6f8;', '&#x1f6f9;', '&#x1f6fa;', '&#x1f6fb;', '&#x1f6fc;', '&#x1f7e0;', '&#x1f7e1;', '&#x1f7e2;', '&#x1f7e3;', '&#x1f7e4;', '&#x1f7e5;', '&#x1f7e6;', '&#x1f7e7;', '&#x1f7e8;', '&#x1f7e9;', '&#x1f7ea;', '&#x1f7eb;', '&#x1f7f0;', '&#x1f90c;', '&#x1f90d;', '&#x1f90e;', '&#x1f90f;', '&#x1f910;', '&#x1f911;', '&#x1f912;', '&#x1f913;', '&#x1f914;', '&#x1f915;', '&#x1f916;', '&#x1f917;', '&#x1f918;', '&#x1f919;', '&#x1f91a;', '&#x1f91b;', '&#x1f91c;', '&#x1f91d;', '&#x1f91e;', '&#x1f91f;', '&#x1f920;', '&#x1f921;', '&#x1f922;', '&#x1f923;', '&#x1f924;', '&#x1f925;', '&#x1f926;', '&#x1f927;', '&#x1f928;', '&#x1f929;', '&#x1f92a;', '&#x1f92b;', '&#x1f92c;', '&#x1f92d;', '&#x1f92e;', '&#x1f92f;', '&#x1f930;', '&#x1f931;', '&#x1f932;', '&#x1f933;', '&#x1f934;', '&#x1f935;', '&#x1f936;', '&#x1f937;', '&#x1f938;', '&#x1f939;', '&#x1f93a;', '&#x1f93c;', '&#x1f93d;', '&#x1f93e;', '&#x1f93f;', '&#x1f940;', '&#x1f941;', '&#x1f942;', '&#x1f943;', '&#x1f944;', '&#x1f945;', '&#x1f947;', '&#x1f948;', '&#x1f949;', '&#x1f94a;', '&#x1f94b;', '&#x1f94c;', '&#x1f94d;', '&#x1f94e;', '&#x1f94f;', '&#x1f950;', '&#x1f951;', '&#x1f952;', '&#x1f953;', '&#x1f954;', '&#x1f955;', '&#x1f956;', '&#x1f957;', '&#x1f958;', '&#x1f959;', '&#x1f95a;', '&#x1f95b;', '&#x1f95c;', '&#x1f95d;', '&#x1f95e;', '&#x1f95f;', '&#x1f960;', '&#x1f961;', '&#x1f962;', '&#x1f963;', '&#x1f964;', '&#x1f965;', '&#x1f966;', '&#x1f967;', '&#x1f968;', '&#x1f969;', '&#x1f96a;', '&#x1f96b;', '&#x1f96c;', '&#x1f96d;', '&#x1f96e;', '&#x1f96f;', '&#x1f970;', '&#x1f971;', '&#x1f972;', '&#x1f973;', '&#x1f974;', '&#x1f975;', '&#x1f976;', '&#x1f977;', '&#x1f978;', '&#x1f979;', '&#x1f97a;', '&#x1f97b;', '&#x1f97c;', '&#x1f97d;', '&#x1f97e;', '&#x1f97f;', '&#x1f980;', '&#x1f981;', '&#x1f982;', '&#x1f983;', '&#x1f984;', '&#x1f985;', '&#x1f986;', '&#x1f987;', '&#x1f988;', '&#x1f989;', '&#x1f98a;', '&#x1f98b;', '&#x1f98c;', '&#x1f98d;', '&#x1f98e;', '&#x1f98f;', '&#x1f990;', '&#x1f991;', '&#x1f992;', '&#x1f993;', '&#x1f994;', '&#x1f995;', '&#x1f996;', '&#x1f997;', '&#x1f998;', '&#x1f999;', '&#x1f99a;', '&#x1f99b;', '&#x1f99c;', '&#x1f99d;', '&#x1f99e;', '&#x1f99f;', '&#x1f9a0;', '&#x1f9a1;', '&#x1f9a2;', '&#x1f9a3;', '&#x1f9a4;', '&#x1f9a5;', '&#x1f9a6;', '&#x1f9a7;', '&#x1f9a8;', '&#x1f9a9;', '&#x1f9aa;', '&#x1f9ab;', '&#x1f9ac;', '&#x1f9ad;', '&#x1f9ae;', '&#x1f9af;', '&#x1f9b0;', '&#x1f9b1;', '&#x1f9b2;', '&#x1f9b3;', '&#x1f9b4;', '&#x1f9b5;', '&#x1f9b6;', '&#x1f9b7;', '&#x1f9b8;', '&#x1f9b9;', '&#x1f9ba;', '&#x1f9bb;', '&#x1f9bc;', '&#x1f9bd;', '&#x1f9be;', '&#x1f9bf;', '&#x1f9c0;', '&#x1f9c1;', '&#x1f9c2;', '&#x1f9c3;', '&#x1f9c4;', '&#x1f9c5;', '&#x1f9c6;', '&#x1f9c7;', '&#x1f9c8;', '&#x1f9c9;', '&#x1f9ca;', '&#x1f9cb;', '&#x1f9cc;', '&#x1f9cd;', '&#x1f9ce;', '&#x1f9cf;', '&#x1f9d0;', '&#x1f9d1;', '&#x1f9d2;', '&#x1f9d3;', '&#x1f9d4;', '&#x1f9d5;', '&#x1f9d6;', '&#x1f9d7;', '&#x1f9d8;', '&#x1f9d9;', '&#x1f9da;', '&#x1f9db;', '&#x1f9dc;', '&#x1f9dd;', '&#x1f9de;', '&#x1f9df;', '&#x1f9e0;', '&#x1f9e1;', '&#x1f9e2;', '&#x1f9e3;', '&#x1f9e4;', '&#x1f9e5;', '&#x1f9e6;', '&#x1f9e7;', '&#x1f9e8;', '&#x1f9e9;', '&#x1f9ea;', '&#x1f9eb;', '&#x1f9ec;', '&#x1f9ed;', '&#x1f9ee;', '&#x1f9ef;', '&#x1f9f0;', '&#x1f9f1;', '&#x1f9f2;', '&#x1f9f3;', '&#x1f9f4;', '&#x1f9f5;', '&#x1f9f6;', '&#x1f9f7;', '&#x1f9f8;', '&#x1f9f9;', '&#x1f9fa;', '&#x1f9fb;', '&#x1f9fc;', '&#x1f9fd;', '&#x1f9fe;', '&#x1f9ff;', '&#x1fa70;', '&#x1fa71;', '&#x1fa72;', '&#x1fa73;', '&#x1fa74;', '&#x1fa75;', '&#x1fa76;', '&#x1fa77;', '&#x1fa78;', '&#x1fa79;', '&#x1fa7a;', '&#x1fa7b;', '&#x1fa7c;', '&#x1fa80;', '&#x1fa81;', '&#x1fa82;', '&#x1fa83;', '&#x1fa84;', '&#x1fa85;', '&#x1fa86;', '&#x1fa87;', '&#x1fa88;', '&#x1fa90;', '&#x1fa91;', '&#x1fa92;', '&#x1fa93;', '&#x1fa94;', '&#x1fa95;', '&#x1fa96;', '&#x1fa97;', '&#x1fa98;', '&#x1fa99;', '&#x1fa9a;', '&#x1fa9b;', '&#x1fa9c;', '&#x1fa9d;', '&#x1fa9e;', '&#x1fa9f;', '&#x1faa0;', '&#x1faa1;', '&#x1faa2;', '&#x1faa3;', '&#x1faa4;', '&#x1faa5;', '&#x1faa6;', '&#x1faa7;', '&#x1faa8;', '&#x1faa9;', '&#x1faaa;', '&#x1faab;', '&#x1faac;', '&#x1faad;', '&#x1faae;', '&#x1faaf;', '&#x1fab0;', '&#x1fab1;', '&#x1fab2;', '&#x1fab3;', '&#x1fab4;', '&#x1fab5;', '&#x1fab6;', '&#x1fab7;', '&#x1fab8;', '&#x1fab9;', '&#x1faba;', '&#x1fabb;', '&#x1fabc;', '&#x1fabd;', '&#x1fabf;', '&#x1fac0;', '&#x1fac1;', '&#x1fac2;', '&#x1fac3;', '&#x1fac4;', '&#x1fac5;', '&#x1face;', '&#x1facf;', '&#x1fad0;', '&#x1fad1;', '&#x1fad2;', '&#x1fad3;', '&#x1fad4;', '&#x1fad5;', '&#x1fad6;', '&#x1fad7;', '&#x1fad8;', '&#x1fad9;', '&#x1fada;', '&#x1fadb;', '&#x1fae0;', '&#x1fae1;', '&#x1fae2;', '&#x1fae3;', '&#x1fae4;', '&#x1fae5;', '&#x1fae6;', '&#x1fae7;', '&#x1fae8;', '&#x1faf0;', '&#x1faf1;', '&#x1faf2;', '&#x1faf3;', '&#x1faf4;', '&#x1faf5;', '&#x1faf6;', '&#x1faf7;', '&#x1faf8;', '&#x203c;', '&#x2049;', '&#x2122;', '&#x2139;', '&#x2194;', '&#x2195;', '&#x2196;', '&#x2197;', '&#x2198;', '&#x2199;', '&#x21a9;', '&#x21aa;', '&#x231a;', '&#x231b;', '&#x2328;', '&#x23cf;', '&#x23e9;', '&#x23ea;', '&#x23eb;', '&#x23ec;', '&#x23ed;', '&#x23ee;', '&#x23ef;', '&#x23f0;', '&#x23f1;', '&#x23f2;', '&#x23f3;', '&#x23f8;', '&#x23f9;', '&#x23fa;', '&#x24c2;', '&#x25aa;', '&#x25ab;', '&#x25b6;', '&#x25c0;', '&#x25fb;', '&#x25fc;', '&#x25fd;', '&#x25fe;', '&#x2600;', '&#x2601;', '&#x2602;', '&#x2603;', '&#x2604;', '&#x260e;', '&#x2611;', '&#x2614;', '&#x2615;', '&#x2618;', '&#x261d;', '&#x2620;', '&#x2622;', '&#x2623;', '&#x2626;', '&#x262a;', '&#x262e;', '&#x262f;', '&#x2638;', '&#x2639;', '&#x263a;', '&#x2640;', '&#x2642;', '&#x2648;', '&#x2649;', '&#x264a;', '&#x264b;', '&#x264c;', '&#x264d;', '&#x264e;', '&#x264f;', '&#x2650;', '&#x2651;', '&#x2652;', '&#x2653;', '&#x265f;', '&#x2660;', '&#x2663;', '&#x2665;', '&#x2666;', '&#x2668;', '&#x267b;', '&#x267e;', '&#x267f;', '&#x2692;', '&#x2693;', '&#x2694;', '&#x2695;', '&#x2696;', '&#x2697;', '&#x2699;', '&#x269b;', '&#x269c;', '&#x26a0;', '&#x26a1;', '&#x26a7;', '&#x26aa;', '&#x26ab;', '&#x26b0;', '&#x26b1;', '&#x26bd;', '&#x26be;', '&#x26c4;', '&#x26c5;', '&#x26c8;', '&#x26ce;', '&#x26cf;', '&#x26d1;', '&#x26d3;', '&#x26d4;', '&#x26e9;', '&#x26ea;', '&#x26f0;', '&#x26f1;', '&#x26f2;', '&#x26f3;', '&#x26f4;', '&#x26f5;', '&#x26f7;', '&#x26f8;', '&#x26f9;', '&#x26fa;', '&#x26fd;', '&#x2702;', '&#x2705;', '&#x2708;', '&#x2709;', '&#x270a;', '&#x270b;', '&#x270c;', '&#x270d;', '&#x270f;', '&#x2712;', '&#x2714;', '&#x2716;', '&#x271d;', '&#x2721;', '&#x2728;', '&#x2733;', '&#x2734;', '&#x2744;', '&#x2747;', '&#x274c;', '&#x274e;', '&#x2753;', '&#x2754;', '&#x2755;', '&#x2757;', '&#x2763;', '&#x2764;', '&#x2795;', '&#x2796;', '&#x2797;', '&#x27a1;', '&#x27b0;', '&#x27bf;', '&#x2934;', '&#x2935;', '&#x2b05;', '&#x2b06;', '&#x2b07;', '&#x2b1b;', '&#x2b1c;', '&#x2b50;', '&#x2b55;', '&#x3030;', '&#x303d;', '&#x3297;', '&#x3299;', '&#xe50a;' );
	$partials = array( '&#x1f004;', '&#x1f0cf;', '&#x1f170;', '&#x1f171;', '&#x1f17e;', '&#x1f17f;', '&#x1f18e;', '&#x1f191;', '&#x1f192;', '&#x1f193;', '&#x1f194;', '&#x1f195;', '&#x1f196;', '&#x1f197;', '&#x1f198;', '&#x1f199;', '&#x1f19a;', '&#x1f1e6;', '&#x1f1e8;', '&#x1f1e9;', '&#x1f1ea;', '&#x1f1eb;', '&#x1f1ec;', '&#x1f1ee;', '&#x1f1f1;', '&#x1f1f2;', '&#x1f1f4;', '&#x1f1f6;', '&#x1f1f7;', '&#x1f1f8;', '&#x1f1f9;', '&#x1f1fa;', '&#x1f1fc;', '&#x1f1fd;', '&#x1f1ff;', '&#x1f1e7;', '&#x1f1ed;', '&#x1f1ef;', '&#x1f1f3;', '&#x1f1fb;', '&#x1f1fe;', '&#x1f1f0;', '&#x1f1f5;', '&#x1f201;', '&#x1f202;', '&#x1f21a;', '&#x1f22f;', '&#x1f232;', '&#x1f233;', '&#x1f234;', '&#x1f235;', '&#x1f236;', '&#x1f237;', '&#x1f238;', '&#x1f239;', '&#x1f23a;', '&#x1f250;', '&#x1f251;', '&#x1f300;', '&#x1f301;', '&#x1f302;', '&#x1f303;', '&#x1f304;', '&#x1f305;', '&#x1f306;', '&#x1f307;', '&#x1f308;', '&#x1f309;', '&#x1f30a;', '&#x1f30b;', '&#x1f30c;', '&#x1f30d;', '&#x1f30e;', '&#x1f30f;', '&#x1f310;', '&#x1f311;', '&#x1f312;', '&#x1f313;', '&#x1f314;', '&#x1f315;', '&#x1f316;', '&#x1f317;', '&#x1f318;', '&#x1f319;', '&#x1f31a;', '&#x1f31b;', '&#x1f31c;', '&#x1f31d;', '&#x1f31e;', '&#x1f31f;', '&#x1f320;', '&#x1f321;', '&#x1f324;', '&#x1f325;', '&#x1f326;', '&#x1f327;', '&#x1f328;', '&#x1f329;', '&#x1f32a;', '&#x1f32b;', '&#x1f32c;', '&#x1f32d;', '&#x1f32e;', '&#x1f32f;', '&#x1f330;', '&#x1f331;', '&#x1f332;', '&#x1f333;', '&#x1f334;', '&#x1f335;', '&#x1f336;', '&#x1f337;', '&#x1f338;', '&#x1f339;', '&#x1f33a;', '&#x1f33b;', '&#x1f33c;', '&#x1f33d;', '&#x1f33e;', '&#x1f33f;', '&#x1f340;', '&#x1f341;', '&#x1f342;', '&#x1f343;', '&#x1f344;', '&#x200d;', '&#x1f7eb;', '&#x1f345;', '&#x1f346;', '&#x1f347;', '&#x1f348;', '&#x1f349;', '&#x1f34a;', '&#x1f34b;', '&#x1f7e9;', '&#x1f34c;', '&#x1f34d;', '&#x1f34e;', '&#x1f34f;', '&#x1f350;', '&#x1f351;', '&#x1f352;', '&#x1f353;', '&#x1f354;', '&#x1f355;', '&#x1f356;', '&#x1f357;', '&#x1f358;', '&#x1f359;', '&#x1f35a;', '&#x1f35b;', '&#x1f35c;', '&#x1f35d;', '&#x1f35e;', '&#x1f35f;', '&#x1f360;', '&#x1f361;', '&#x1f362;', '&#x1f363;', '&#x1f364;', '&#x1f365;', '&#x1f366;', '&#x1f367;', '&#x1f368;', '&#x1f369;', '&#x1f36a;', '&#x1f36b;', '&#x1f36c;', '&#x1f36d;', '&#x1f36e;', '&#x1f36f;', '&#x1f370;', '&#x1f371;', '&#x1f372;', '&#x1f373;', '&#x1f374;', '&#x1f375;', '&#x1f376;', '&#x1f377;', '&#x1f378;', '&#x1f379;', '&#x1f37a;', '&#x1f37b;', '&#x1f37c;', '&#x1f37d;', '&#x1f37e;', '&#x1f37f;', '&#x1f380;', '&#x1f381;', '&#x1f382;', '&#x1f383;', '&#x1f384;', '&#x1f385;', '&#x1f3fb;', '&#x1f3fc;', '&#x1f3fd;', '&#x1f3fe;', '&#x1f3ff;', '&#x1f386;', '&#x1f387;', '&#x1f388;', '&#x1f389;', '&#x1f38a;', '&#x1f38b;', '&#x1f38c;', '&#x1f38d;', '&#x1f38e;', '&#x1f38f;', '&#x1f390;', '&#x1f391;', '&#x1f392;', '&#x1f393;', '&#x1f396;', '&#x1f397;', '&#x1f399;', '&#x1f39a;', '&#x1f39b;', '&#x1f39e;', '&#x1f39f;', '&#x1f3a0;', '&#x1f3a1;', '&#x1f3a2;', '&#x1f3a3;', '&#x1f3a4;', '&#x1f3a5;', '&#x1f3a6;', '&#x1f3a7;', '&#x1f3a8;', '&#x1f3a9;', '&#x1f3aa;', '&#x1f3ab;', '&#x1f3ac;', '&#x1f3ad;', '&#x1f3ae;', '&#x1f3af;', '&#x1f3b0;', '&#x1f3b1;', '&#x1f3b2;', '&#x1f3b3;', '&#x1f3b4;', '&#x1f3b5;', '&#x1f3b6;', '&#x1f3b7;', '&#x1f3b8;', '&#x1f3b9;', '&#x1f3ba;', '&#x1f3bb;', '&#x1f3bc;', '&#x1f3bd;', '&#x1f3be;', '&#x1f3bf;', '&#x1f3c0;', '&#x1f3c1;', '&#x1f3c2;', '&#x1f3c3;', '&#x2640;', '&#xfe0f;', '&#x27a1;', '&#x2642;', '&#x1f3c4;', '&#x1f3c5;', '&#x1f3c6;', '&#x1f3c7;', '&#x1f3c8;', '&#x1f3c9;', '&#x1f3ca;', '&#x1f3cb;', '&#x1f3cc;', '&#x1f3cd;', '&#x1f3ce;', '&#x1f3cf;', '&#x1f3d0;', '&#x1f3d1;', '&#x1f3d2;', '&#x1f3d3;', '&#x1f3d4;', '&#x1f3d5;', '&#x1f3d6;', '&#x1f3d7;', '&#x1f3d8;', '&#x1f3d9;', '&#x1f3da;', '&#x1f3db;', '&#x1f3dc;', '&#x1f3dd;', '&#x1f3de;', '&#x1f3df;', '&#x1f3e0;', '&#x1f3e1;', '&#x1f3e2;', '&#x1f3e3;', '&#x1f3e4;', '&#x1f3e5;', '&#x1f3e6;', '&#x1f3e7;', '&#x1f3e8;', '&#x1f3e9;', '&#x1f3ea;', '&#x1f3eb;', '&#x1f3ec;', '&#x1f3ed;', '&#x1f3ee;', '&#x1f3ef;', '&#x1f3f0;', '&#x1f3f3;', '&#x26a7;', '&#x1f3f4;', '&#x2620;', '&#xe0067;', '&#xe0062;', '&#xe0065;', '&#xe006e;', '&#xe007f;', '&#xe0073;', '&#xe0063;', '&#xe0074;', '&#xe0077;', '&#xe006c;', '&#x1f3f5;', '&#x1f3f7;', '&#x1f3f8;', '&#x1f3f9;', '&#x1f3fa;', '&#x1f400;', '&#x1f401;', '&#x1f402;', '&#x1f403;', '&#x1f404;', '&#x1f405;', '&#x1f406;', '&#x1f407;', '&#x1f408;', '&#x2b1b;', '&#x1f409;', '&#x1f40a;', '&#x1f40b;', '&#x1f40c;', '&#x1f40d;', '&#x1f40e;', '&#x1f40f;', '&#x1f410;', '&#x1f411;', '&#x1f412;', '&#x1f413;', '&#x1f414;', '&#x1f415;', '&#x1f9ba;', '&#x1f416;', '&#x1f417;', '&#x1f418;', '&#x1f419;', '&#x1f41a;', '&#x1f41b;', '&#x1f41c;', '&#x1f41d;', '&#x1f41e;', '&#x1f41f;', '&#x1f420;', '&#x1f421;', '&#x1f422;', '&#x1f423;', '&#x1f424;', '&#x1f425;', '&#x1f426;', '&#x1f525;', '&#x1f427;', '&#x1f428;', '&#x1f429;', '&#x1f42a;', '&#x1f42b;', '&#x1f42c;', '&#x1f42d;', '&#x1f42e;', '&#x1f42f;', '&#x1f430;', '&#x1f431;', '&#x1f432;', '&#x1f433;', '&#x1f434;', '&#x1f435;', '&#x1f436;', '&#x1f437;', '&#x1f438;', '&#x1f439;', '&#x1f43a;', '&#x1f43b;', '&#x2744;', '&#x1f43c;', '&#x1f43d;', '&#x1f43e;', '&#x1f43f;', '&#x1f440;', '&#x1f441;', '&#x1f5e8;', '&#x1f442;', '&#x1f443;', '&#x1f444;', '&#x1f445;', '&#x1f446;', '&#x1f447;', '&#x1f448;', '&#x1f449;', '&#x1f44a;', '&#x1f44b;', '&#x1f44c;', '&#x1f44d;', '&#x1f44e;', '&#x1f44f;', '&#x1f450;', '&#x1f451;', '&#x1f452;', '&#x1f453;', '&#x1f454;', '&#x1f455;', '&#x1f456;', '&#x1f457;', '&#x1f458;', '&#x1f459;', '&#x1f45a;', '&#x1f45b;', '&#x1f45c;', '&#x1f45d;', '&#x1f45e;', '&#x1f45f;', '&#x1f460;', '&#x1f461;', '&#x1f462;', '&#x1f463;', '&#x1f464;', '&#x1f465;', '&#x1f466;', '&#x1f467;', '&#x1f468;', '&#x1f4bb;', '&#x1f4bc;', '&#x1f527;', '&#x1f52c;', '&#x1f680;', '&#x1f692;', '&#x1f91d;', '&#x1f9af;', '&#x1f9b0;', '&#x1f9b1;', '&#x1f9b2;', '&#x1f9b3;', '&#x1f9bc;', '&#x1f9bd;', '&#x2695;', '&#x2696;', '&#x2708;', '&#x2764;', '&#x1f48b;', '&#x1f469;', '&#x1f46a;', '&#x1f46b;', '&#x1f46c;', '&#x1f46d;', '&#x1f46e;', '&#x1f46f;', '&#x1f470;', '&#x1f471;', '&#x1f472;', '&#x1f473;', '&#x1f474;', '&#x1f475;', '&#x1f476;', '&#x1f477;', '&#x1f478;', '&#x1f479;', '&#x1f47a;', '&#x1f47b;', '&#x1f47c;', '&#x1f47d;', '&#x1f47e;', '&#x1f47f;', '&#x1f480;', '&#x1f481;', '&#x1f482;', '&#x1f483;', '&#x1f484;', '&#x1f485;', '&#x1f486;', '&#x1f487;', '&#x1f488;', '&#x1f489;', '&#x1f48a;', '&#x1f48c;', '&#x1f48d;', '&#x1f48e;', '&#x1f48f;', '&#x1f490;', '&#x1f491;', '&#x1f492;', '&#x1f493;', '&#x1f494;', '&#x1f495;', '&#x1f496;', '&#x1f497;', '&#x1f498;', '&#x1f499;', '&#x1f49a;', '&#x1f49b;', '&#x1f49c;', '&#x1f49d;', '&#x1f49e;', '&#x1f49f;', '&#x1f4a0;', '&#x1f4a1;', '&#x1f4a2;', '&#x1f4a3;', '&#x1f4a4;', '&#x1f4a5;', '&#x1f4a6;', '&#x1f4a7;', '&#x1f4a8;', '&#x1f4a9;', '&#x1f4aa;', '&#x1f4ab;', '&#x1f4ac;', '&#x1f4ad;', '&#x1f4ae;', '&#x1f4af;', '&#x1f4b0;', '&#x1f4b1;', '&#x1f4b2;', '&#x1f4b3;', '&#x1f4b4;', '&#x1f4b5;', '&#x1f4b6;', '&#x1f4b7;', '&#x1f4b8;', '&#x1f4b9;', '&#x1f4ba;', '&#x1f4bd;', '&#x1f4be;', '&#x1f4bf;', '&#x1f4c0;', '&#x1f4c1;', '&#x1f4c2;', '&#x1f4c3;', '&#x1f4c4;', '&#x1f4c5;', '&#x1f4c6;', '&#x1f4c7;', '&#x1f4c8;', '&#x1f4c9;', '&#x1f4ca;', '&#x1f4cb;', '&#x1f4cc;', '&#x1f4cd;', '&#x1f4ce;', '&#x1f4cf;', '&#x1f4d0;', '&#x1f4d1;', '&#x1f4d2;', '&#x1f4d3;', '&#x1f4d4;', '&#x1f4d5;', '&#x1f4d6;', '&#x1f4d7;', '&#x1f4d8;', '&#x1f4d9;', '&#x1f4da;', '&#x1f4db;', '&#x1f4dc;', '&#x1f4dd;', '&#x1f4de;', '&#x1f4df;', '&#x1f4e0;', '&#x1f4e1;', '&#x1f4e2;', '&#x1f4e3;', '&#x1f4e4;', '&#x1f4e5;', '&#x1f4e6;', '&#x1f4e7;', '&#x1f4e8;', '&#x1f4e9;', '&#x1f4ea;', '&#x1f4eb;', '&#x1f4ec;', '&#x1f4ed;', '&#x1f4ee;', '&#x1f4ef;', '&#x1f4f0;', '&#x1f4f1;', '&#x1f4f2;', '&#x1f4f3;', '&#x1f4f4;', '&#x1f4f5;', '&#x1f4f6;', '&#x1f4f7;', '&#x1f4f8;', '&#x1f4f9;', '&#x1f4fa;', '&#x1f4fb;', '&#x1f4fc;', '&#x1f4fd;', '&#x1f4ff;', '&#x1f500;', '&#x1f501;', '&#x1f502;', '&#x1f503;', '&#x1f504;', '&#x1f505;', '&#x1f506;', '&#x1f507;', '&#x1f508;', '&#x1f509;', '&#x1f50a;', '&#x1f50b;', '&#x1f50c;', '&#x1f50d;', '&#x1f50e;', '&#x1f50f;', '&#x1f510;', '&#x1f511;', '&#x1f512;', '&#x1f513;', '&#x1f514;', '&#x1f515;', '&#x1f516;', '&#x1f517;', '&#x1f518;', '&#x1f519;', '&#x1f51a;', '&#x1f51b;', '&#x1f51c;', '&#x1f51d;', '&#x1f51e;', '&#x1f51f;', '&#x1f520;', '&#x1f521;', '&#x1f522;', '&#x1f523;', '&#x1f524;', '&#x1f526;', '&#x1f528;', '&#x1f529;', '&#x1f52a;', '&#x1f52b;', '&#x1f52d;', '&#x1f52e;', '&#x1f52f;', '&#x1f530;', '&#x1f531;', '&#x1f532;', '&#x1f533;', '&#x1f534;', '&#x1f535;', '&#x1f536;', '&#x1f537;', '&#x1f538;', '&#x1f539;', '&#x1f53a;', '&#x1f53b;', '&#x1f53c;', '&#x1f53d;', '&#x1f549;', '&#x1f54a;', '&#x1f54b;', '&#x1f54c;', '&#x1f54d;', '&#x1f54e;', '&#x1f550;', '&#x1f551;', '&#x1f552;', '&#x1f553;', '&#x1f554;', '&#x1f555;', '&#x1f556;', '&#x1f557;', '&#x1f558;', '&#x1f559;', '&#x1f55a;', '&#x1f55b;', '&#x1f55c;', '&#x1f55d;', '&#x1f55e;', '&#x1f55f;', '&#x1f560;', '&#x1f561;', '&#x1f562;', '&#x1f563;', '&#x1f564;', '&#x1f565;', '&#x1f566;', '&#x1f567;', '&#x1f56f;', '&#x1f570;', '&#x1f573;', '&#x1f574;', '&#x1f575;', '&#x1f576;', '&#x1f577;', '&#x1f578;', '&#x1f579;', '&#x1f57a;', '&#x1f587;', '&#x1f58a;', '&#x1f58b;', '&#x1f58c;', '&#x1f58d;', '&#x1f590;', '&#x1f595;', '&#x1f596;', '&#x1f5a4;', '&#x1f5a5;', '&#x1f5a8;', '&#x1f5b1;', '&#x1f5b2;', '&#x1f5bc;', '&#x1f5c2;', '&#x1f5c3;', '&#x1f5c4;', '&#x1f5d1;', '&#x1f5d2;', '&#x1f5d3;', '&#x1f5dc;', '&#x1f5dd;', '&#x1f5de;', '&#x1f5e1;', '&#x1f5e3;', '&#x1f5ef;', '&#x1f5f3;', '&#x1f5fa;', '&#x1f5fb;', '&#x1f5fc;', '&#x1f5fd;', '&#x1f5fe;', '&#x1f5ff;', '&#x1f600;', '&#x1f601;', '&#x1f602;', '&#x1f603;', '&#x1f604;', '&#x1f605;', '&#x1f606;', '&#x1f607;', '&#x1f608;', '&#x1f609;', '&#x1f60a;', '&#x1f60b;', '&#x1f60c;', '&#x1f60d;', '&#x1f60e;', '&#x1f60f;', '&#x1f610;', '&#x1f611;', '&#x1f612;', '&#x1f613;', '&#x1f614;', '&#x1f615;', '&#x1f616;', '&#x1f617;', '&#x1f618;', '&#x1f619;', '&#x1f61a;', '&#x1f61b;', '&#x1f61c;', '&#x1f61d;', '&#x1f61e;', '&#x1f61f;', '&#x1f620;', '&#x1f621;', '&#x1f622;', '&#x1f623;', '&#x1f624;', '&#x1f625;', '&#x1f626;', '&#x1f627;', '&#x1f628;', '&#x1f629;', '&#x1f62a;', '&#x1f62b;', '&#x1f62c;', '&#x1f62d;', '&#x1f62e;', '&#x1f62f;', '&#x1f630;', '&#x1f631;', '&#x1f632;', '&#x1f633;', '&#x1f634;', '&#x1f635;', '&#x1f636;', '&#x1f637;', '&#x1f638;', '&#x1f639;', '&#x1f63a;', '&#x1f63b;', '&#x1f63c;', '&#x1f63d;', '&#x1f63e;', '&#x1f63f;', '&#x1f640;', '&#x1f641;', '&#x1f642;', '&#x2194;', '&#x2195;', '&#x1f643;', '&#x1f644;', '&#x1f645;', '&#x1f646;', '&#x1f647;', '&#x1f648;', '&#x1f649;', '&#x1f64a;', '&#x1f64b;', '&#x1f64c;', '&#x1f64d;', '&#x1f64e;', '&#x1f64f;', '&#x1f681;', '&#x1f682;', '&#x1f683;', '&#x1f684;', '&#x1f685;', '&#x1f686;', '&#x1f687;', '&#x1f688;', '&#x1f689;', '&#x1f68a;', '&#x1f68b;', '&#x1f68c;', '&#x1f68d;', '&#x1f68e;', '&#x1f68f;', '&#x1f690;', '&#x1f691;', '&#x1f693;', '&#x1f694;', '&#x1f695;', '&#x1f696;', '&#x1f697;', '&#x1f698;', '&#x1f699;', '&#x1f69a;', '&#x1f69b;', '&#x1f69c;', '&#x1f69d;', '&#x1f69e;', '&#x1f69f;', '&#x1f6a0;', '&#x1f6a1;', '&#x1f6a2;', '&#x1f6a3;', '&#x1f6a4;', '&#x1f6a5;', '&#x1f6a6;', '&#x1f6a7;', '&#x1f6a8;', '&#x1f6a9;', '&#x1f6aa;', '&#x1f6ab;', '&#x1f6ac;', '&#x1f6ad;', '&#x1f6ae;', '&#x1f6af;', '&#x1f6b0;', '&#x1f6b1;', '&#x1f6b2;', '&#x1f6b3;', '&#x1f6b4;', '&#x1f6b5;', '&#x1f6b6;', '&#x1f6b7;', '&#x1f6b8;', '&#x1f6b9;', '&#x1f6ba;', '&#x1f6bb;', '&#x1f6bc;', '&#x1f6bd;', '&#x1f6be;', '&#x1f6bf;', '&#x1f6c0;', '&#x1f6c1;', '&#x1f6c2;', '&#x1f6c3;', '&#x1f6c4;', '&#x1f6c5;', '&#x1f6cb;', '&#x1f6cc;', '&#x1f6cd;', '&#x1f6ce;', '&#x1f6cf;', '&#x1f6d0;', '&#x1f6d1;', '&#x1f6d2;', '&#x1f6d5;', '&#x1f6d6;', '&#x1f6d7;', '&#x1f6dc;', '&#x1f6dd;', '&#x1f6de;', '&#x1f6df;', '&#x1f6e0;', '&#x1f6e1;', '&#x1f6e2;', '&#x1f6e3;', '&#x1f6e4;', '&#x1f6e5;', '&#x1f6e9;', '&#x1f6eb;', '&#x1f6ec;', '&#x1f6f0;', '&#x1f6f3;', '&#x1f6f4;', '&#x1f6f5;', '&#x1f6f6;', '&#x1f6f7;', '&#x1f6f8;', '&#x1f6f9;', '&#x1f6fa;', '&#x1f6fb;', '&#x1f6fc;', '&#x1f7e0;', '&#x1f7e1;', '&#x1f7e2;', '&#x1f7e3;', '&#x1f7e4;', '&#x1f7e5;', '&#x1f7e6;', '&#x1f7e7;', '&#x1f7e8;', '&#x1f7ea;', '&#x1f7f0;', '&#x1f90c;', '&#x1f90d;', '&#x1f90e;', '&#x1f90f;', '&#x1f910;', '&#x1f911;', '&#x1f912;', '&#x1f913;', '&#x1f914;', '&#x1f915;', '&#x1f916;', '&#x1f917;', '&#x1f918;', '&#x1f919;', '&#x1f91a;', '&#x1f91b;', '&#x1f91c;', '&#x1f91e;', '&#x1f91f;', '&#x1f920;', '&#x1f921;', '&#x1f922;', '&#x1f923;', '&#x1f924;', '&#x1f925;', '&#x1f926;', '&#x1f927;', '&#x1f928;', '&#x1f929;', '&#x1f92a;', '&#x1f92b;', '&#x1f92c;', '&#x1f92d;', '&#x1f92e;', '&#x1f92f;', '&#x1f930;', '&#x1f931;', '&#x1f932;', '&#x1f933;', '&#x1f934;', '&#x1f935;', '&#x1f936;', '&#x1f937;', '&#x1f938;', '&#x1f939;', '&#x1f93a;', '&#x1f93c;', '&#x1f93d;', '&#x1f93e;', '&#x1f93f;', '&#x1f940;', '&#x1f941;', '&#x1f942;', '&#x1f943;', '&#x1f944;', '&#x1f945;', '&#x1f947;', '&#x1f948;', '&#x1f949;', '&#x1f94a;', '&#x1f94b;', '&#x1f94c;', '&#x1f94d;', '&#x1f94e;', '&#x1f94f;', '&#x1f950;', '&#x1f951;', '&#x1f952;', '&#x1f953;', '&#x1f954;', '&#x1f955;', '&#x1f956;', '&#x1f957;', '&#x1f958;', '&#x1f959;', '&#x1f95a;', '&#x1f95b;', '&#x1f95c;', '&#x1f95d;', '&#x1f95e;', '&#x1f95f;', '&#x1f960;', '&#x1f961;', '&#x1f962;', '&#x1f963;', '&#x1f964;', '&#x1f965;', '&#x1f966;', '&#x1f967;', '&#x1f968;', '&#x1f969;', '&#x1f96a;', '&#x1f96b;', '&#x1f96c;', '&#x1f96d;', '&#x1f96e;', '&#x1f96f;', '&#x1f970;', '&#x1f971;', '&#x1f972;', '&#x1f973;', '&#x1f974;', '&#x1f975;', '&#x1f976;', '&#x1f977;', '&#x1f978;', '&#x1f979;', '&#x1f97a;', '&#x1f97b;', '&#x1f97c;', '&#x1f97d;', '&#x1f97e;', '&#x1f97f;', '&#x1f980;', '&#x1f981;', '&#x1f982;', '&#x1f983;', '&#x1f984;', '&#x1f985;', '&#x1f986;', '&#x1f987;', '&#x1f988;', '&#x1f989;', '&#x1f98a;', '&#x1f98b;', '&#x1f98c;', '&#x1f98d;', '&#x1f98e;', '&#x1f98f;', '&#x1f990;', '&#x1f991;', '&#x1f992;', '&#x1f993;', '&#x1f994;', '&#x1f995;', '&#x1f996;', '&#x1f997;', '&#x1f998;', '&#x1f999;', '&#x1f99a;', '&#x1f99b;', '&#x1f99c;', '&#x1f99d;', '&#x1f99e;', '&#x1f99f;', '&#x1f9a0;', '&#x1f9a1;', '&#x1f9a2;', '&#x1f9a3;', '&#x1f9a4;', '&#x1f9a5;', '&#x1f9a6;', '&#x1f9a7;', '&#x1f9a8;', '&#x1f9a9;', '&#x1f9aa;', '&#x1f9ab;', '&#x1f9ac;', '&#x1f9ad;', '&#x1f9ae;', '&#x1f9b4;', '&#x1f9b5;', '&#x1f9b6;', '&#x1f9b7;', '&#x1f9b8;', '&#x1f9b9;', '&#x1f9bb;', '&#x1f9be;', '&#x1f9bf;', '&#x1f9c0;', '&#x1f9c1;', '&#x1f9c2;', '&#x1f9c3;', '&#x1f9c4;', '&#x1f9c5;', '&#x1f9c6;', '&#x1f9c7;', '&#x1f9c8;', '&#x1f9c9;', '&#x1f9ca;', '&#x1f9cb;', '&#x1f9cc;', '&#x1f9cd;', '&#x1f9ce;', '&#x1f9cf;', '&#x1f9d0;', '&#x1f9d1;', '&#x1f9d2;', '&#x1f9d3;', '&#x1f9d4;', '&#x1f9d5;', '&#x1f9d6;', '&#x1f9d7;', '&#x1f9d8;', '&#x1f9d9;', '&#x1f9da;', '&#x1f9db;', '&#x1f9dc;', '&#x1f9dd;', '&#x1f9de;', '&#x1f9df;', '&#x1f9e0;', '&#x1f9e1;', '&#x1f9e2;', '&#x1f9e3;', '&#x1f9e4;', '&#x1f9e5;', '&#x1f9e6;', '&#x1f9e7;', '&#x1f9e8;', '&#x1f9e9;', '&#x1f9ea;', '&#x1f9eb;', '&#x1f9ec;', '&#x1f9ed;', '&#x1f9ee;', '&#x1f9ef;', '&#x1f9f0;', '&#x1f9f1;', '&#x1f9f2;', '&#x1f9f3;', '&#x1f9f4;', '&#x1f9f5;', '&#x1f9f6;', '&#x1f9f7;', '&#x1f9f8;', '&#x1f9f9;', '&#x1f9fa;', '&#x1f9fb;', '&#x1f9fc;', '&#x1f9fd;', '&#x1f9fe;', '&#x1f9ff;', '&#x1fa70;', '&#x1fa71;', '&#x1fa72;', '&#x1fa73;', '&#x1fa74;', '&#x1fa75;', '&#x1fa76;', '&#x1fa77;', '&#x1fa78;', '&#x1fa79;', '&#x1fa7a;', '&#x1fa7b;', '&#x1fa7c;', '&#x1fa80;', '&#x1fa81;', '&#x1fa82;', '&#x1fa83;', '&#x1fa84;', '&#x1fa85;', '&#x1fa86;', '&#x1fa87;', '&#x1fa88;', '&#x1fa90;', '&#x1fa91;', '&#x1fa92;', '&#x1fa93;', '&#x1fa94;', '&#x1fa95;', '&#x1fa96;', '&#x1fa97;', '&#x1fa98;', '&#x1fa99;', '&#x1fa9a;', '&#x1fa9b;', '&#x1fa9c;', '&#x1fa9d;', '&#x1fa9e;', '&#x1fa9f;', '&#x1faa0;', '&#x1faa1;', '&#x1faa2;', '&#x1faa3;', '&#x1faa4;', '&#x1faa5;', '&#x1faa6;', '&#x1faa7;', '&#x1faa8;', '&#x1faa9;', '&#x1faaa;', '&#x1faab;', '&#x1faac;', '&#x1faad;', '&#x1faae;', '&#x1faaf;', '&#x1fab0;', '&#x1fab1;', '&#x1fab2;', '&#x1fab3;', '&#x1fab4;', '&#x1fab5;', '&#x1fab6;', '&#x1fab7;', '&#x1fab8;', '&#x1fab9;', '&#x1faba;', '&#x1fabb;', '&#x1fabc;', '&#x1fabd;', '&#x1fabf;', '&#x1fac0;', '&#x1fac1;', '&#x1fac2;', '&#x1fac3;', '&#x1fac4;', '&#x1fac5;', '&#x1face;', '&#x1facf;', '&#x1fad0;', '&#x1fad1;', '&#x1fad2;', '&#x1fad3;', '&#x1fad4;', '&#x1fad5;', '&#x1fad6;', '&#x1fad7;', '&#x1fad8;', '&#x1fad9;', '&#x1fada;', '&#x1fadb;', '&#x1fae0;', '&#x1fae1;', '&#x1fae2;', '&#x1fae3;', '&#x1fae4;', '&#x1fae5;', '&#x1fae6;', '&#x1fae7;', '&#x1fae8;', '&#x1faf0;', '&#x1faf1;', '&#x1faf2;', '&#x1faf3;', '&#x1faf4;', '&#x1faf5;', '&#x1faf6;', '&#x1faf7;', '&#x1faf8;', '&#x203c;', '&#x2049;', '&#x2122;', '&#x2139;', '&#x2196;', '&#x2197;', '&#x2198;', '&#x2199;', '&#x21a9;', '&#x21aa;', '&#x20e3;', '&#x231a;', '&#x231b;', '&#x2328;', '&#x23cf;', '&#x23e9;', '&#x23ea;', '&#x23eb;', '&#x23ec;', '&#x23ed;', '&#x23ee;', '&#x23ef;', '&#x23f0;', '&#x23f1;', '&#x23f2;', '&#x23f3;', '&#x23f8;', '&#x23f9;', '&#x23fa;', '&#x24c2;', '&#x25aa;', '&#x25ab;', '&#x25b6;', '&#x25c0;', '&#x25fb;', '&#x25fc;', '&#x25fd;', '&#x25fe;', '&#x2600;', '&#x2601;', '&#x2602;', '&#x2603;', '&#x2604;', '&#x260e;', '&#x2611;', '&#x2614;', '&#x2615;', '&#x2618;', '&#x261d;', '&#x2622;', '&#x2623;', '&#x2626;', '&#x262a;', '&#x262e;', '&#x262f;', '&#x2638;', '&#x2639;', '&#x263a;', '&#x2648;', '&#x2649;', '&#x264a;', '&#x264b;', '&#x264c;', '&#x264d;', '&#x264e;', '&#x264f;', '&#x2650;', '&#x2651;', '&#x2652;', '&#x2653;', '&#x265f;', '&#x2660;', '&#x2663;', '&#x2665;', '&#x2666;', '&#x2668;', '&#x267b;', '&#x267e;', '&#x267f;', '&#x2692;', '&#x2693;', '&#x2694;', '&#x2697;', '&#x2699;', '&#x269b;', '&#x269c;', '&#x26a0;', '&#x26a1;', '&#x26aa;', '&#x26ab;', '&#x26b0;', '&#x26b1;', '&#x26bd;', '&#x26be;', '&#x26c4;', '&#x26c5;', '&#x26c8;', '&#x26ce;', '&#x26cf;', '&#x26d1;', '&#x26d3;', '&#x26d4;', '&#x26e9;', '&#x26ea;', '&#x26f0;', '&#x26f1;', '&#x26f2;', '&#x26f3;', '&#x26f4;', '&#x26f5;', '&#x26f7;', '&#x26f8;', '&#x26f9;', '&#x26fa;', '&#x26fd;', '&#x2702;', '&#x2705;', '&#x2709;', '&#x270a;', '&#x270b;', '&#x270c;', '&#x270d;', '&#x270f;', '&#x2712;', '&#x2714;', '&#x2716;', '&#x271d;', '&#x2721;', '&#x2728;', '&#x2733;', '&#x2734;', '&#x2747;', '&#x274c;', '&#x274e;', '&#x2753;', '&#x2754;', '&#x2755;', '&#x2757;', '&#x2763;', '&#x2795;', '&#x2796;', '&#x2797;', '&#x27b0;', '&#x27bf;', '&#x2934;', '&#x2935;', '&#x2b05;', '&#x2b06;', '&#x2b07;', '&#x2b1c;', '&#x2b50;', '&#x2b55;', '&#x3030;', '&#x303d;', '&#x3297;', '&#x3299;', '&#xe50a;' );
	// END: emoji arrays

	if ( 'entities' === $type ) {
		return $entities;
	}

	return $partials;
}

// wp-includes/formatting.php (WP 6.8.1)
function url_shorten( $url, $length = 35 ) {
	$stripped  = str_replace( array( 'https://', 'http://', 'www.' ), '', $url );
	$short_url = untrailingslashit( $stripped );

	if ( strlen( $short_url ) > $length ) {
		$short_url = substr( $short_url, 0, $length - 3 ) . '&hellip;';
	}
	return $short_url;
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_hex_color( $color ) {
	if ( '' === $color ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}
}

// wp-includes/formatting.php (WP 6.8.1)
function sanitize_hex_color_no_hash( $color ) {
	$color = ltrim( $color, '#' );

	if ( '' === $color ) {
		return '';
	}

	return sanitize_hex_color( '#' . $color ) ? $color : null;
}

// wp-includes/formatting.php (WP 6.8.1)
function maybe_hash_hex_color( $color ) {
	$unhashed = sanitize_hex_color_no_hash( $color );
	if ( $unhashed ) {
		return '#' . $unhashed;
	}

	return $color;
}

