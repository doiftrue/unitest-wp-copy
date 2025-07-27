<?php

class Parser_Helpers {

	/**
	 * Cuts classes, functions and stores all their data in a variable: names, code and line numbers.
	 *
	 * Performs double operation. Changes passed parameter $lines if parameter $cut is specified.
	 * And returns data of found classes.
	 *
	 * Files for testing:
	 *    $file = ABSPATH . 'wp-admin/includes/class-pclzip.php';
	 *    $file = ABSPATH . 'wp-includes/class.wp-dependencies.php';
	 *    $file = ABSPATH . 'wp-includes/query.php';
	 *    $file = ABSPATH . 'wp-admin/includes/media.php';
	 *    $file = ABSPATH . 'wp-includes/customize/class-wp-customize-media-control.php';
	 *    $file = ABSPATH . 'wp-includes/functions.wp-styles.php';
	 *
	 * Code for testing:
	 *     $file         = ABSPATH . 'wp-admin/includes/media.php';
	 *     $file_content = file_get_contents( $filepath );
	 *     $functions    = get_class_func_code_from_php_code( $file_content, [ 'type' => 'func' ] );
	 *     echo htmlspecialchars( print_r( $functions, true ) );
	 *
	 * @param array|string $lines A line-separated content or a string of content (file code).
	 * @param array $args         Data retrieval parameters. Array keys:
	 *                            - 'cut'  - Cut class/function code from the data passed in $lines or not.
	 *                            - 'type' - The type of data received: 'class', 'method' (class methods), 'func' (function).
	 *                            - 'name' - Get a specified class or function (by name).
	 *
	 * @return array The data of the found classes|functions. Will return such an array:
	 *
	 *     Array(
	 *         [WP_Dependencies] => Array(
	 *               [18] => class WP_Dependencies {
	 *               [19] =>    public $registered = array();
	 *               ...
	 *         ),
	 *         [_WP_Dependency] => Array( ... )
	 *     )
	 *
	 * @version 1.0
	 */
	public static function get_class_func_code_from_php_code( & $lines, $args = [] ): array {

		$rg = (object) array_merge( [
			'cut'  => 0,       // Cut or not the found code from the lines...
			'type' => 'class', // func, method, class.
			'name' => '',      // Class or function name. When you want to get a specific class or function.
		], $args );

		if( ! is_array( $lines ) ){
			$was_string = true;
			$lines = str_replace( "\r", '', $lines );
			$lines = explode( "\n", $lines );
		}

		// correct the line array indexes - it must start with 1 (not 0).
		reset( $lines );
		if( 0 === key( $lines ) ){
			$_lines = [];
			foreach( $lines as $k => $val ){
				$_lines[ $k + 1 ] = $val;
			}
			$lines = $_lines;
		}

		// Look for a specific name or any
		$patt_name = $rg->name ? '(' . preg_quote( $rg->name, '~' ) . ')' : '([a-zA-Z0-9_]+)';

		$match_pattern = match ( $rg->type ) {
			'func' => "~^[\t ]*function[ ]+{$patt_name}[ ]*\(~",
			'class' => "~^[\t ]*(?:final[ ]+|abstract[ ]+|enum[ ]+)?(?:class[ ]+|interface[ ]+){$patt_name}~",
			'method' => "~^[\t ]*(?:final[ ]+|abstract[ ]+|static[ ]+|public[ ]+|private[ ]+|protected[ ]+)*function[ &]+{$patt_name}[ ]*\(~"
		};

		$code_lines = []; // The array index contains the name. The value is `line number => function code
		$_open_braket_level = 0;  // open brackets counter { after
		$_in_process = 0;  // 1 when inside the class
		$_elem_name = ''; // name of function, class


		$inside_script = $inside_style = $inside_comments = false;

		// look for the line containing the function (the first line) and collect the code inside the { ... }
		foreach( $lines as $line_num => $line ){
			// Clear the line, so that extra { and } inside the lines do not interfere with the counting
			$clear_line = trim( $line );

			// Delete the lines 'foo' or "foo". Example: https://wp-kama.ru/function/wptexturize
			// The string can be empty `''`. Example: https://wp-kama.ru/function/media_upload_type_form
			// Single quote at start, or preceded by (, {, <, [, ", -, spaces.
			// $dynamic[ '/\'(\d\d)\'(?=\Z|[.,:;!?)}\-\]]|&gt;|' . $spaces . ')/' ] = $apos_flag . '$1' . $closing_single_quote;
			$clear_line = preg_replace( '~([\'"]).*?(?<!\\\\)\1~', '', $clear_line );

			// inside block comments /* code with new line breaks */
			// opens /* one on line
			if( ! $inside_comments && preg_match( '~^[ \t]*/\*+[ \t]*$~', $clear_line ) ){
				$inside_comments = 1;
			}
			// closes */ one on line
			elseif( $inside_comments && preg_match( '~^[ \t]*\*+/[ \t]*$~', $clear_line ) ){
				$inside_comments = 0;
			}

			// If not inside comments.
			// Inside comments can be substrings similar to string comments, example: wp_initial_constants()
			if( ! $inside_comments ){

				// Remove comments like // and #.
				// Note that the # character can start the css-selector id in, eg: `#{$selector} {`.
				// So let's assume that the comment starts with `# ` or `# `.
				// Example: //wp-kama.ru/function/gallery_shortcode
				$clear_line = preg_replace( '~(?://|#[ \t]).+$~', '', $clear_line );

				// Delete comments like /* ... */
				// Example: https://wp-kama.ru/function/wp_add_inline_style
				$clear_line = preg_replace( '~/[*].+?[*]/~', '', $clear_line );
			}

			// Skip if we are not in the code collection process and we are inside the '<script>.*?</script>' tag
			// There can also be functions there that look like PHP functions...
			// The JS function for checking `wp_attempt_focus` - it shouldn't be in the list!
			// Also <script> can be inside the comments, example: wp_strip_all_tags()
			if( ! $_in_process && ! $inside_comments ){

				if( ! $inside_script && preg_match( '/<script[ \t>]/', $clear_line ) ){
					$inside_script = 1;
				}

				if( $inside_script && str_contains( $clear_line, '</script>' ) ){
					$inside_script = 0;
				}
			}

			// inside styles (already inside the function code)
			if( $_in_process && ! $inside_comments ){

				if( ! $inside_style && preg_match( '/<style[ \t>]/', $clear_line ) ){
					$inside_style = 1;
				}

				if( $inside_style && str_contains( $clear_line, '</style>' ) ){
					$inside_style = 0;
				}
			}

			// not inside (i.e., not process).
			// Here we go. Check the string for membership in a class, function, or method.
			if(
				! $_in_process
				&& ! $inside_comments
				&& ! $inside_script
				&& preg_match( $match_pattern, $clear_line, $mm )
			){
				$_in_process = 1;
				$_elem_name = $mm[1];

				// add the string to the return
				$code_lines[ $_elem_name ][ $line_num ] = $line;
				// delete the string that belongs to the class
				if( $rg->cut ){
					unset( $lines[ $line_num ] );
				}

				// a method can be without brackets at all. Example: 'abstract public function load();'
				if( preg_match( '/; *$/', $clear_line ) ){
					$_in_process = 0;

					// if we were looking for a specific name, the loop can be terminated
					if( $rg->name ){
						break;
					}
				}
				else {
					// count the opening bracket
					if( $count = preg_match_all( '/\{/', $clear_line ) ){
						$_open_braket_level += $count;
					}
					else {
						/* To write the function in this way
						function foo(
							$foo,
							$bar
						) {
							// function
						}
						*/
						$_open_braket_level += 1; // Open manually, then skip it at the first encounter...
						$_maually_opened = true;
					}

					// can close on the same line in methods. Example: 'function offsetUnset( $offset ) {}'
					// So let's check the closing
					if( $count = preg_match_all( '/\}/', $clear_line ) ){
						$_open_braket_level -= $count;

						if( $_open_braket_level === 0 ){
							$_in_process = 0;

							// if we were looking for a specific name, the loop can be terminated
							if( $rg->name ){
								break;
							}
						}
					}
				}
			}
			// inside - it means we process it - we look for the closure
			elseif( $_in_process ){
				// don't process { and } inside block comments /*code with line breaks*/.
				// don't process { and } inside <style> blocks (they might have # similar to PHP comments)
				if( ! $inside_comments && ! $inside_style ){
					// The '{' sign before which there is no '\' or '['. It may be in the regular expression
					if( $count = preg_match_all( '~(?<!\\|\[)\{~', $clear_line ) ){
						$_open_braket_level += $count;
					}

					// A '}' sign before which there is no '\' or '['. It may be in the regular expression
					if( $count = preg_match_all( '~(?<!\\|\[)\}~', $clear_line ) ){
						$_open_braket_level -= $count;
					}
				}

				$code_lines[ $_elem_name ][ $line_num ] = $line;
				// delete the line belonging to the class
				if( $rg->cut ){
					unset( $lines[ $line_num ] );
				}

				if( $_open_braket_level === 0 ){
					// function or class was closed.
					$_in_process = 0;

					// if we were looking for a specific name, the loop can be interrupted
					if( $rg->name ){
						break;
					}
				}

				if( ! empty( $_maually_opened ) && $_open_braket_level === 2 ){
					$_maually_opened = false;
					// Remove one count forcibly opened in the first line,
					// when there is no opening bracket '{' in the first line...
					$_open_braket_level -= 1;
				}
			}
		}

		// put the string back together as it was
		if( ! empty( $was_string ) ){
			$lines = implode( "\n", $lines );
		}

		// In this case the result will contain only one element of the array...
		if( $rg->name ){
			$code_lines = reset( $code_lines );
		}

		return $code_lines;
	}

}
