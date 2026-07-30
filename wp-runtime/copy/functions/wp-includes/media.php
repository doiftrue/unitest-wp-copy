<?php

// ------------------auto-generated---------------------

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_img_tag_add_auto_sizes' ) ) :
	function wp_img_tag_add_auto_sizes( string $image ): string {
		/**
		 * Filters whether auto-sizes for lazy loaded images is enabled.
		 *
		 * @since 6.7.1
		 *
		 * @param boolean $enabled Whether auto-sizes for lazy loaded images is enabled.
		 */
		if ( ! apply_filters( 'wp_img_tag_add_auto_sizes', true ) ) {
			return $image;
		}
	
		$processor = new WP_HTML_Tag_Processor( $image );
	
		// Bail if there is no IMG tag.
		if ( ! $processor->next_tag( array( 'tag_name' => 'IMG' ) ) ) {
			return $image;
		}
	
		// Bail early if the image is not lazy-loaded.
		$loading = $processor->get_attribute( 'loading' );
		if ( ! is_string( $loading ) || 'lazy' !== strtolower( trim( $loading, " \t\f\r\n" ) ) ) {
			return $image;
		}
	
		/*
		 * Bail early if the image doesn't have a width attribute.
		 * Per WordPress Core itself, lazy-loaded images should always have a width attribute.
		 * However, it is possible that lazy-loading could be added by a plugin, where we don't have that guarantee.
		 * As such, it still makes sense to ensure presence of a width attribute here in order to use `sizes=auto`.
		 */
		$width = $processor->get_attribute( 'width' );
		if ( ! is_string( $width ) || '' === $width ) {
			return $image;
		}
	
		$sizes = $processor->get_attribute( 'sizes' );
	
		// Bail early if the image is not responsive.
		if ( ! is_string( $sizes ) ) {
			return $image;
		}
	
		// Don't add 'auto' to the sizes attribute if it already exists.
		if ( wp_sizes_attribute_includes_valid_auto( $sizes ) ) {
			return $image;
		}
	
		$processor->set_attribute( 'sizes', "auto, $sizes" );
		return $processor->get_updated_html();
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_sizes_attribute_includes_valid_auto' ) ) :
	function wp_sizes_attribute_includes_valid_auto( string $sizes_attr ): bool {
		list( $first_size ) = explode( ',', $sizes_attr, 2 );
		return 'auto' === strtolower( trim( $first_size, " \t\f\r\n" ) );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_get_image_editor_output_format' ) ) :
	function wp_get_image_editor_output_format( $filename, $mime_type ) {
		$output_format = array(
			'image/heic'          => 'image/jpeg',
			'image/heif'          => 'image/jpeg',
			'image/heic-sequence' => 'image/jpeg',
			'image/heif-sequence' => 'image/jpeg',
		);
	
		/**
		 * Filters the image editor output format mapping.
		 *
		 * Enables filtering the mime type used to save images. By default HEIC/HEIF images
		 * are converted to JPEGs.
		 *
		 * @see WP_Image_Editor::get_output_format()
		 *
		 * @since 5.8.0
		 * @since 6.7.0 The default was changed from an empty array to an array
		 *              containing the HEIC/HEIF images mime types.
		 *
		 * @param string[] $output_format {
		 *     An array of mime type mappings. Maps a source mime type to a new
		 *     destination mime type. By default maps HEIC/HEIF input to JPEG output.
		 *
		 *     @type string ...$0 The new mime type.
		 * }
		 * @param string $filename  Path to the image.
		 * @param string $mime_type The source image mime type.
		 */
		return apply_filters( 'image_editor_output_format', $output_format, $filename, $mime_type );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( '_wp_post_thumbnail_context_filter' ) ) :
	function _wp_post_thumbnail_context_filter( $context ) {
		return 'the_post_thumbnail';
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( '_wp_post_thumbnail_context_filter_add' ) ) :
	function _wp_post_thumbnail_context_filter_add() {
		add_filter( 'wp_get_attachment_image_context', '_wp_post_thumbnail_context_filter' );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( '_wp_post_thumbnail_context_filter_remove' ) ) :
	function _wp_post_thumbnail_context_filter_remove() {
		remove_filter( 'wp_get_attachment_image_context', '_wp_post_thumbnail_context_filter' );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_maybe_add_fetchpriority_high_attr' ) ) :
	function wp_maybe_add_fetchpriority_high_attr( $loading_attrs, $tag_name, $attr ) {
		// For now, adding `fetchpriority="high"` is only supported for images.
		if ( 'img' !== $tag_name ) {
			return $loading_attrs;
		}
	
		$existing_fetchpriority = $attr['fetchpriority'] ?? null;
		if ( null !== $existing_fetchpriority && 'auto' !== $existing_fetchpriority ) {
			/*
			 * When an IMG has been explicitly marked with `fetchpriority=high`, then honor that this is the element that
			 * should have the priority. In contrast, the Navigation block may add `fetchpriority=low` to an IMG which
			 * appears in the Navigation Overlay; such images should never be considered candidates for
			 * `fetchpriority=high`. Lastly, block visibility may add `fetchpriority=auto` to an IMG when the block is
			 * conditionally displayed based on viewport size. Such an image is considered an LCP element candidate if it
			 * exceeds the threshold for the minimum number of square pixels.
			 */
			if ( 'high' === $existing_fetchpriority ) {
				$loading_attrs['fetchpriority'] = 'high';
				wp_high_priority_element_flag( false );
			}
	
			return $loading_attrs;
		}
	
		// Lazy-loading and `fetchpriority="high"` are mutually exclusive.
		if ( isset( $loading_attrs['loading'] ) && 'lazy' === $loading_attrs['loading'] ) {
			return $loading_attrs;
		}
	
		if ( ! wp_high_priority_element_flag() ) {
			return $loading_attrs;
		}
	
		/**
		 * Filters the minimum square-pixels threshold for an image to be eligible as the high-priority image.
		 *
		 * @since 6.3.0
		 *
		 * @param int $threshold Minimum square-pixels threshold. Default 50000.
		 */
		$wp_min_priority_img_pixels = apply_filters( 'wp_min_priority_img_pixels', 50000 );
	
		if ( $wp_min_priority_img_pixels <= $attr['width'] * $attr['height'] ) {
			if ( 'auto' !== $existing_fetchpriority ) {
				$loading_attrs['fetchpriority'] = 'high';
			}
			wp_high_priority_element_flag( false );
		}
	
		return $loading_attrs;
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_high_priority_element_flag' ) ) :
	function wp_high_priority_element_flag( $value = null ): bool {
		static $high_priority_element = true;
	
		if ( is_bool( $value ) ) {
			$high_priority_element = $value;
		}
	
		return $high_priority_element;
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_omit_loading_attr_threshold' ) ) :
	function wp_omit_loading_attr_threshold( $force = false ) {
		static $omit_threshold;
	
		// This function may be called multiple times. Run the filter only once per page load.
		if ( ! isset( $omit_threshold ) || $force ) {
			/**
			 * Filters the threshold for how many of the first content media elements to not lazy-load.
			 *
			 * For these first content media elements, the `loading` attribute will be omitted. By default, this is the case
			 * for only the very first content media element.
			 *
			 * @since 5.9.0
			 * @since 6.3.0 The default threshold was changed from 1 to 3.
			 *
			 * @param int $omit_threshold The number of media elements where the `loading` attribute will not be added. Default 3.
			 */
			$omit_threshold = apply_filters( 'wp_omit_loading_attr_threshold', 3 );
		}
	
		return $omit_threshold;
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_increase_content_media_count' ) ) :
	function wp_increase_content_media_count( $amount = 1 ) {
		static $content_media_count = 0;
	
		$content_media_count += $amount;
	
		return $content_media_count;
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_image_file_matches_image_meta' ) ) :
	function wp_image_file_matches_image_meta( $image_location, $image_meta, $attachment_id = 0 ) {
		$match = false;
	
		// Ensure the $image_meta is valid.
		if ( isset( $image_meta['file'] ) && strlen( $image_meta['file'] ) > 4 ) {
			// Remove query args in image URI.
			list( $image_location ) = explode( '?', $image_location );
	
			// Check if the relative image path from the image meta is at the end of $image_location.
			if ( strrpos( $image_location, $image_meta['file'] ) === strlen( $image_location ) - strlen( $image_meta['file'] ) ) {
				$match = true;
			} else {
				// Retrieve the uploads sub-directory from the full size image.
				$dirname = _wp_get_attachment_relative_path( $image_meta['file'] );
	
				if ( $dirname ) {
					$dirname = trailingslashit( $dirname );
				}
	
				if ( ! empty( $image_meta['original_image'] ) ) {
					$relative_path = $dirname . $image_meta['original_image'];
	
					if ( strrpos( $image_location, $relative_path ) === strlen( $image_location ) - strlen( $relative_path ) ) {
						$match = true;
					}
				}
	
				if ( ! $match && ! empty( $image_meta['sizes'] ) ) {
					foreach ( $image_meta['sizes'] as $image_size_data ) {
						$relative_path = $dirname . $image_size_data['file'];
	
						if ( strrpos( $image_location, $relative_path ) === strlen( $image_location ) - strlen( $relative_path ) ) {
							$match = true;
							break;
						}
					}
				}
			}
		}
	
		/**
		 * Filters whether an image path or URI matches image meta.
		 *
		 * @since 5.5.0
		 *
		 * @param bool   $match          Whether the image relative path from the image meta
		 *                               matches the end of the URI or path to the image file.
		 * @param string $image_location Full path or URI to the tested image file.
		 * @param array  $image_meta     The image meta data as returned by 'wp_get_attachment_metadata()'.
		 * @param int    $attachment_id  The image attachment ID or 0 if not supplied.
		 */
		return apply_filters( 'wp_image_file_matches_image_meta', $match, $image_location, $image_meta, $attachment_id );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_image_src_get_dimensions' ) ) :
	function wp_image_src_get_dimensions( $image_src, $image_meta, $attachment_id = 0 ) {
		$dimensions = false;
	
		// Is it a full size image?
		if (
			isset( $image_meta['file'] ) &&
			str_contains( $image_src, wp_basename( $image_meta['file'] ) )
		) {
			$dimensions = array(
				(int) $image_meta['width'],
				(int) $image_meta['height'],
			);
		}
	
		if ( ! $dimensions && ! empty( $image_meta['sizes'] ) ) {
			$src_filename = wp_basename( $image_src );
	
			foreach ( $image_meta['sizes'] as $image_size_data ) {
				if ( $src_filename === $image_size_data['file'] ) {
					$dimensions = array(
						(int) $image_size_data['width'],
						(int) $image_size_data['height'],
					);
	
					break;
				}
			}
		}
	
		/**
		 * Filters the wp_image_src_get_dimensions() value.
		 *
		 * @since 5.7.0
		 *
		 * @param array|false $dimensions    Array with first element being the width
		 *                                   and second element being the height, or
		 *                                   false if dimensions could not be determined.
		 * @param string      $image_src     The image source file.
		 * @param array       $image_meta    The image meta data as returned by
		 *                                   'wp_get_attachment_metadata()'.
		 * @param int         $attachment_id The image attachment ID. Default 0.
		 */
		return apply_filters( 'wp_image_src_get_dimensions', $dimensions, $image_src, $image_meta, $attachment_id );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_lazy_loading_enabled' ) ) :
	function wp_lazy_loading_enabled( $tag_name, $context ) {
		/*
		 * By default add to all 'img' and 'iframe' tags.
		 * See https://html.spec.whatwg.org/multipage/embedded-content.html#attr-img-loading
		 * See https://html.spec.whatwg.org/multipage/iframe-embed-object.html#attr-iframe-loading
		 */
		$default = ( 'img' === $tag_name || 'iframe' === $tag_name );
	
		/**
		 * Filters whether to add the `loading` attribute to the specified tag in the specified context.
		 *
		 * @since 5.5.0
		 *
		 * @param bool   $default  Default value.
		 * @param string $tag_name The tag name.
		 * @param string $context  Additional context, like the current filter name
		 *                         or the function name from where this was called.
		 */
		return (bool) apply_filters( 'wp_lazy_loading_enabled', $default, $tag_name, $context );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( '_wp_add_additional_image_sizes' ) ) :
	function _wp_add_additional_image_sizes() {
		// 2x medium_large size.
		add_image_size( '1536x1536', 1536, 1536 );
		// 2x large size.
		add_image_size( '2048x2048', 2048, 2048 );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_get_registered_image_subsizes' ) ) :
	function wp_get_registered_image_subsizes() {
		$additional_sizes = wp_get_additional_image_sizes();
		$all_sizes        = array();
	
		foreach ( get_intermediate_image_sizes() as $size_name ) {
			$size_data = array(
				'width'  => 0,
				'height' => 0,
				'crop'   => false,
			);
	
			if ( isset( $additional_sizes[ $size_name ]['width'] ) ) {
				// For sizes added by plugins and themes.
				$size_data['width'] = (int) $additional_sizes[ $size_name ]['width'];
			} else {
				// For default sizes set in options.
				$size_data['width'] = (int) get_option( "{$size_name}_size_w" );
			}
	
			if ( isset( $additional_sizes[ $size_name ]['height'] ) ) {
				$size_data['height'] = (int) $additional_sizes[ $size_name ]['height'];
			} else {
				$size_data['height'] = (int) get_option( "{$size_name}_size_h" );
			}
	
			if ( empty( $size_data['width'] ) && empty( $size_data['height'] ) ) {
				// This size isn't set.
				continue;
			}
	
			if ( isset( $additional_sizes[ $size_name ]['crop'] ) ) {
				$size_data['crop'] = $additional_sizes[ $size_name ]['crop'];
			} else {
				$size_data['crop'] = get_option( "{$size_name}_crop" );
			}
	
			if ( ! is_array( $size_data['crop'] ) || empty( $size_data['crop'] ) ) {
				$size_data['crop'] = (bool) $size_data['crop'];
			}
	
			$all_sizes[ $size_name ] = $size_data;
		}
	
		return $all_sizes;
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_image_matches_ratio' ) ) :
	function wp_image_matches_ratio( $source_width, $source_height, $target_width, $target_height ) {
		/*
		 * To test for varying crops, we constrain the dimensions of the larger image
		 * to the dimensions of the smaller image and see if they match.
		 */
		if ( $source_width > $target_width ) {
			$constrained_size = wp_constrain_dimensions( $source_width, $source_height, $target_width );
			$expected_size    = array( $target_width, $target_height );
		} else {
			$constrained_size = wp_constrain_dimensions( $target_width, $target_height, $source_width );
			$expected_size    = array( $source_width, $source_height );
		}
	
		// If the image dimensions are within 1px of the expected size, we consider it a match.
		$matched = ( wp_fuzzy_number_match( $constrained_size[0], $expected_size[0] ) && wp_fuzzy_number_match( $constrained_size[1], $expected_size[1] ) );
	
		return $matched;
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( '_wp_get_attachment_relative_path' ) ) :
	function _wp_get_attachment_relative_path( $file ) {
		$dirname = dirname( $file );
	
		if ( '.' === $dirname ) {
			return '';
		}
	
		if ( str_contains( $dirname, 'wp-content/uploads' ) ) {
			// Get the directory name relative to the upload directory (back compat for pre-2.7 uploads).
			$dirname = substr( $dirname, strpos( $dirname, 'wp-content/uploads' ) + 18 );
			$dirname = ltrim( $dirname, '/' );
		}
	
		return $dirname;
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( '_wp_get_image_size_from_meta' ) ) :
	function _wp_get_image_size_from_meta( $size_name, $image_meta ) {
		if ( 'full' === $size_name ) {
			return array(
				absint( $image_meta['width'] ),
				absint( $image_meta['height'] ),
			);
		} elseif ( ! empty( $image_meta['sizes'][ $size_name ] ) ) {
			return array(
				absint( $image_meta['sizes'][ $size_name ]['width'] ),
				absint( $image_meta['sizes'][ $size_name ]['height'] ),
			);
		}
	
		return false;
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'has_image_size' ) ) :
	function has_image_size( $name ) {
		$sizes = wp_get_additional_image_sizes();
		return isset( $sizes[ $name ] );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'remove_image_size' ) ) :
	function remove_image_size( $name ) {
		global $_wp_additional_image_sizes;
	
		if ( isset( $_wp_additional_image_sizes[ $name ] ) ) {
			unset( $_wp_additional_image_sizes[ $name ] );
			return true;
		}
	
		return false;
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_get_attachment_id3_keys' ) ) :
	function wp_get_attachment_id3_keys( $attachment, $context = 'display' ) {
		$fields = array(
			'artist' => __( 'Artist' ),
			'album'  => __( 'Album' ),
		);
	
		if ( 'display' === $context ) {
			$fields['genre']            = __( 'Genre' );
			$fields['year']             = __( 'Year' );
			$fields['length_formatted'] = _x( 'Length', 'video or audio' );
		} elseif ( 'js' === $context ) {
			$fields['bitrate']      = __( 'Bitrate' );
			$fields['bitrate_mode'] = __( 'Bitrate Mode' );
		}
	
		/**
		 * Filters the editable list of keys to look up data from an attachment's metadata.
		 *
		 * @since 3.9.0
		 *
		 * @param array   $fields     Key/value pairs of field keys to labels.
		 * @param WP_Post $attachment Attachment object.
		 * @param string  $context    The context. Accepts 'edit', 'display'. Default 'display'.
		 */
		return apply_filters( 'wp_get_attachment_id3_keys', $fields, $attachment, $context );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_mediaelement_fallback' ) ) :
	function wp_mediaelement_fallback( $url ) {
		/**
		 * Filters the MediaElement fallback output for no-JS.
		 *
		 * @since 3.6.0
		 *
		 * @param string $output Fallback output for no-JS.
		 * @param string $url    Media file URL.
		 */
		return apply_filters( 'wp_mediaelement_fallback', sprintf( '<a href="%1$s">%1$s</a>', esc_url( $url ) ), $url );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_get_audio_extensions' ) ) :
	function wp_get_audio_extensions() {
		/**
		 * Filters the list of supported audio formats.
		 *
		 * @since 3.6.0
		 *
		 * @param string[] $extensions An array of supported audio formats. Defaults are
		 *                            'mp3', 'ogg', 'flac', 'm4a', 'wav'.
		 */
		return apply_filters( 'wp_audio_extensions', array( 'mp3', 'ogg', 'flac', 'm4a', 'wav' ) );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_get_video_extensions' ) ) :
	function wp_get_video_extensions() {
		/**
		 * Filters the list of supported video formats.
		 *
		 * @since 3.6.0
		 *
		 * @param string[] $extensions An array of supported video formats. Defaults are
		 *                             'mp4', 'm4v', 'webm', 'ogv', 'flv'.
		 */
		return apply_filters( 'wp_video_extensions', array( 'mp4', 'm4v', 'webm', 'ogv', 'flv' ) );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'get_intermediate_image_sizes' ) ) :
	function get_intermediate_image_sizes() {
		$default_sizes    = array( 'thumbnail', 'medium', 'medium_large', 'large' );
		$additional_sizes = wp_get_additional_image_sizes();
	
		if ( ! empty( $additional_sizes ) ) {
			$default_sizes = array_merge( $default_sizes, array_keys( $additional_sizes ) );
		}
	
		/**
		 * Filters the list of intermediate image sizes.
		 *
		 * @since 2.5.0
		 *
		 * @param string[] $default_sizes An array of intermediate image size names. Defaults
		 *                                are 'thumbnail', 'medium', 'medium_large', 'large'.
		 */
		return apply_filters( 'intermediate_image_sizes', $default_sizes );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( '_wp_post_thumbnail_class_filter' ) ) :
	function _wp_post_thumbnail_class_filter( $attr ) {
		$attr['class'] .= ' wp-post-image';
		return $attr;
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( '_wp_post_thumbnail_class_filter_add' ) ) :
	function _wp_post_thumbnail_class_filter_add( $attr ) {
		add_filter( 'wp_get_attachment_image_attributes', '_wp_post_thumbnail_class_filter' );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( '_wp_post_thumbnail_class_filter_remove' ) ) :
	function _wp_post_thumbnail_class_filter_remove( $attr ) {
		remove_filter( 'wp_get_attachment_image_attributes', '_wp_post_thumbnail_class_filter' );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_expand_dimensions' ) ) :
	function wp_expand_dimensions( $example_width, $example_height, $max_width, $max_height ) {
		$example_width  = (int) $example_width;
		$example_height = (int) $example_height;
		$max_width      = (int) $max_width;
		$max_height     = (int) $max_height;
	
		return wp_constrain_dimensions( $example_width * 1000000, $example_height * 1000000, $max_width, $max_height );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_max_upload_size' ) ) :
	function wp_max_upload_size() {
		$u_bytes = wp_convert_hr_to_bytes( ini_get( 'upload_max_filesize' ) );
		$p_bytes = wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) );
	
		/**
		 * Filters the maximum upload size allowed in php.ini.
		 *
		 * @since 2.5.0
		 *
		 * @param int $size    Max upload size limit in bytes.
		 * @param int $u_bytes Maximum upload filesize in bytes.
		 * @param int $p_bytes Maximum size of POST data in bytes.
		 */
		return apply_filters( 'upload_size_limit', min( $u_bytes, $p_bytes ), $u_bytes, $p_bytes );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'wp_constrain_dimensions' ) ) :
	function wp_constrain_dimensions( $current_width, $current_height, $max_width = 0, $max_height = 0 ) {
		if ( ! $max_width && ! $max_height ) {
			return array( $current_width, $current_height );
		}
	
		$width_ratio  = 1.0;
		$height_ratio = 1.0;
		$did_width    = false;
		$did_height   = false;
	
		if ( $max_width > 0 && $current_width > 0 && $current_width > $max_width ) {
			$width_ratio = $max_width / $current_width;
			$did_width   = true;
		}
	
		if ( $max_height > 0 && $current_height > 0 && $current_height > $max_height ) {
			$height_ratio = $max_height / $current_height;
			$did_height   = true;
		}
	
		// Calculate the larger/smaller ratios.
		$smaller_ratio = min( $width_ratio, $height_ratio );
		$larger_ratio  = max( $width_ratio, $height_ratio );
	
		if ( (int) round( $current_width * $larger_ratio ) > $max_width || (int) round( $current_height * $larger_ratio ) > $max_height ) {
			// The larger ratio is too big. It would result in an overflow.
			$ratio = $smaller_ratio;
		} else {
			// The larger ratio fits, and is likely to be a more "snug" fit.
			$ratio = $larger_ratio;
		}
	
		// Very small dimensions may result in 0, 1 should be the minimum.
		$w = max( 1, (int) round( $current_width * $ratio ) );
		$h = max( 1, (int) round( $current_height * $ratio ) );
	
		/*
		 * Sometimes, due to rounding, we'll end up with a result like this:
		 * 465x700 in a 177x177 box is 117x176... a pixel short.
		 * We also have issues with recursive calls resulting in an ever-changing result.
		 * Constraining to the result of a constraint should yield the original result.
		 * Thus we look for dimensions that are one pixel shy of the max value and bump them up.
		 */
	
		// Note: $did_width means it is possible $smaller_ratio == $width_ratio.
		if ( $did_width && $w === $max_width - 1 ) {
			$w = $max_width; // Round it up.
		}
	
		// Note: $did_height means it is possible $smaller_ratio == $height_ratio.
		if ( $did_height && $h === $max_height - 1 ) {
			$h = $max_height; // Round it up.
		}
	
		/**
		 * Filters dimensions to constrain down-sampled images to.
		 *
		 * @since 4.1.0
		 *
		 * @param int[] $dimensions     {
		 *     An array of width and height values.
		 *
		 *     @type int $0 The width in pixels.
		 *     @type int $1 The height in pixels.
		 * }
		 * @param int   $current_width  The current width of the image.
		 * @param int   $current_height The current height of the image.
		 * @param int   $max_width      The maximum width permitted.
		 * @param int   $max_height     The maximum height permitted.
		 */
		return apply_filters( 'wp_constrain_dimensions', array( $w, $h ), $current_width, $current_height, $max_width, $max_height );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'image_resize_dimensions' ) ) :
	function image_resize_dimensions( $orig_w, $orig_h, $dest_w, $dest_h, $crop = false ) {
	
		if ( $orig_w <= 0 || $orig_h <= 0 ) {
			return false;
		}
		// At least one of $dest_w or $dest_h must be specific.
		if ( $dest_w <= 0 && $dest_h <= 0 ) {
			return false;
		}
	
		/**
		 * Filters whether to preempt calculating the image resize dimensions.
		 *
		 * Returning a non-null value from the filter will effectively short-circuit
		 * image_resize_dimensions(), returning that value instead.
		 *
		 * @since 3.4.0
		 *
		 * @param null|mixed $null   Whether to preempt output of the resize dimensions.
		 * @param int        $orig_w Original width in pixels.
		 * @param int        $orig_h Original height in pixels.
		 * @param int        $dest_w New width in pixels.
		 * @param int        $dest_h New height in pixels.
		 * @param bool|array $crop   Whether to crop image to specified width and height or resize.
		 *                           An array can specify positioning of the crop area. Default false.
		 */
		$output = apply_filters( 'image_resize_dimensions', null, $orig_w, $orig_h, $dest_w, $dest_h, $crop );
	
		if ( null !== $output ) {
			return $output;
		}
	
		// Stop if the destination size is larger than the original image dimensions.
		if ( empty( $dest_h ) ) {
			if ( $orig_w < $dest_w ) {
				return false;
			}
		} elseif ( empty( $dest_w ) ) {
			if ( $orig_h < $dest_h ) {
				return false;
			}
		} else {
			if ( $orig_w < $dest_w && $orig_h < $dest_h ) {
				return false;
			}
		}
	
		if ( $crop ) {
			/*
			 * Crop the largest possible portion of the original image that we can size to $dest_w x $dest_h.
			 * Note that the requested crop dimensions are used as a maximum bounding box for the original image.
			 * If the original image's width or height is less than the requested width or height
			 * only the greater one will be cropped.
			 * For example when the original image is 600x300, and the requested crop dimensions are 400x400,
			 * the resulting image will be 400x300.
			 */
			$aspect_ratio = $orig_w / $orig_h;
			$new_w        = min( $dest_w, $orig_w );
			$new_h        = min( $dest_h, $orig_h );
	
			if ( ! $new_w ) {
				$new_w = (int) round( $new_h * $aspect_ratio );
			}
	
			if ( ! $new_h ) {
				$new_h = (int) round( $new_w / $aspect_ratio );
			}
	
			$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );
	
			$crop_w = round( $new_w / $size_ratio );
			$crop_h = round( $new_h / $size_ratio );
	
			if ( ! is_array( $crop ) || count( $crop ) !== 2 ) {
				$crop = array( 'center', 'center' );
			}
	
			list( $x, $y ) = $crop;
	
			if ( 'left' === $x ) {
				$s_x = 0;
			} elseif ( 'right' === $x ) {
				$s_x = $orig_w - $crop_w;
			} else {
				$s_x = floor( ( $orig_w - $crop_w ) / 2 );
			}
	
			if ( 'top' === $y ) {
				$s_y = 0;
			} elseif ( 'bottom' === $y ) {
				$s_y = $orig_h - $crop_h;
			} else {
				$s_y = floor( ( $orig_h - $crop_h ) / 2 );
			}
		} else {
			// Resize using $dest_w x $dest_h as a maximum bounding box.
			$crop_w = $orig_w;
			$crop_h = $orig_h;
	
			$s_x = 0;
			$s_y = 0;
	
			list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
		}
	
		if ( wp_fuzzy_number_match( $new_w, $orig_w ) && wp_fuzzy_number_match( $new_h, $orig_h ) ) {
			// The new size has virtually the same dimensions as the original image.
	
			/**
			 * Filters whether to proceed with making an image sub-size with identical dimensions
			 * with the original/source image. Differences of 1px may be due to rounding and are ignored.
			 *
			 * @since 5.3.0
			 *
			 * @param bool $proceed The filtered value.
			 * @param int  $orig_w  Original image width.
			 * @param int  $orig_h  Original image height.
			 */
			$proceed = (bool) apply_filters( 'wp_image_resize_identical_dimensions', false, $orig_w, $orig_h );
	
			if ( ! $proceed ) {
				return false;
			}
		}
	
		/*
		 * The return array matches the parameters to imagecopyresampled().
		 * int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
		 */
		return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'get_media_embedded_in_content' ) ) :
	function get_media_embedded_in_content( $content, $types = null ) {
		$html = array();
	
		/**
		 * Filters the embedded media types that are allowed to be returned from the content blob.
		 *
		 * @since 4.2.0
		 *
		 * @param string[] $allowed_media_types An array of allowed media types. Default media types are
		 *                                      'audio', 'video', 'object', 'embed', and 'iframe'.
		 */
		$allowed_media_types = apply_filters( 'media_embedded_in_content_allowed_types', array( 'audio', 'video', 'object', 'embed', 'iframe' ) );
	
		if ( ! empty( $types ) ) {
			if ( ! is_array( $types ) ) {
				$types = array( $types );
			}
	
			$allowed_media_types = array_intersect( $allowed_media_types, $types );
		}
	
		$tags = implode( '|', $allowed_media_types );
	
		if ( preg_match_all( '#<(?P<tag>' . $tags . ')[^<]*?(?:>[\s\S]*?<\/(?P=tag)>|\s*\/>)#', $content, $matches ) ) {
			foreach ( $matches[0] as $match ) {
				$html[] = $match;
			}
		}
	
		return $html;
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'add_image_size' ) ) :
	function add_image_size( $name, $width = 0, $height = 0, $crop = false ) {
		global $_wp_additional_image_sizes;
	
		$_wp_additional_image_sizes[ $name ] = array(
			'width'  => absint( $width ),
			'height' => absint( $height ),
			'crop'   => $crop,
		);
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'set_post_thumbnail_size' ) ) :
	function set_post_thumbnail_size( $width = 0, $height = 0, $crop = false ) {
		add_image_size( 'post-thumbnail', $width, $height, $crop );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'image_constrain_size_for_editor' ) ) :
	function image_constrain_size_for_editor( $width, $height, $size = 'medium', $context = null ) {
		global $content_width;
	
		$_wp_additional_image_sizes = wp_get_additional_image_sizes();
	
		if ( ! $context ) {
			$context = is_admin() ? 'edit' : 'display';
		}
	
		if ( is_array( $size ) ) {
			$max_width  = $size[0];
			$max_height = $size[1];
		} elseif ( 'thumb' === $size || 'thumbnail' === $size ) {
			$max_width  = (int) get_option( 'thumbnail_size_w' );
			$max_height = (int) get_option( 'thumbnail_size_h' );
			// Last chance thumbnail size defaults.
			if ( ! $max_width && ! $max_height ) {
				$max_width  = 128;
				$max_height = 96;
			}
		} elseif ( 'medium' === $size ) {
			$max_width  = (int) get_option( 'medium_size_w' );
			$max_height = (int) get_option( 'medium_size_h' );
	
		} elseif ( 'medium_large' === $size ) {
			$max_width  = (int) get_option( 'medium_large_size_w' );
			$max_height = (int) get_option( 'medium_large_size_h' );
	
			if ( (int) $content_width > 0 ) {
				$max_width = min( (int) $content_width, $max_width );
			}
		} elseif ( 'large' === $size ) {
			/*
			 * We're inserting a large size image into the editor. If it's a really
			 * big image we'll scale it down to fit reasonably within the editor
			 * itself, and within the theme's content width if it's known. The user
			 * can resize it in the editor if they wish.
			 */
			$max_width  = (int) get_option( 'large_size_w' );
			$max_height = (int) get_option( 'large_size_h' );
	
			if ( (int) $content_width > 0 ) {
				$max_width = min( (int) $content_width, $max_width );
			}
		} elseif ( ! empty( $_wp_additional_image_sizes ) && in_array( $size, array_keys( $_wp_additional_image_sizes ), true ) ) {
			$max_width  = (int) $_wp_additional_image_sizes[ $size ]['width'];
			$max_height = (int) $_wp_additional_image_sizes[ $size ]['height'];
			// Only in admin. Assume that theme authors know what they're doing.
			if ( (int) $content_width > 0 && 'edit' === $context ) {
				$max_width = min( (int) $content_width, $max_width );
			}
		} else { // $size === 'full' has no constraint.
			$max_width  = $width;
			$max_height = $height;
		}
	
		/**
		 * Filters the maximum image size dimensions for the editor.
		 *
		 * @since 2.5.0
		 *
		 * @param int[]        $max_image_size {
		 *     An array of width and height values.
		 *
		 *     @type int $0 The maximum width in pixels.
		 *     @type int $1 The maximum height in pixels.
		 * }
		 * @param string|int[] $size     Requested image size. Can be any registered image size name, or
		 *                               an array of width and height values in pixels (in that order).
		 * @param string       $context  The context the image is being resized for.
		 *                               Possible values are 'display' (like in a theme)
		 *                               or 'edit' (like inserting into an editor).
		 */
		list( $max_width, $max_height ) = apply_filters( 'editor_max_image_size', array( $max_width, $max_height ), $size, $context );
	
		return wp_constrain_dimensions( $width, $height, $max_width, $max_height );
	}
endif;

// wp-includes/media.php (WP 7.0.2)
if( ! function_exists( 'image_hwstring' ) ) :
	function image_hwstring( $width, $height ) {
		$out = '';
		if ( $width ) {
			$out .= 'width="' . (int) $width . '" ';
		}
		if ( $height ) {
			$out .= 'height="' . (int) $height . '" ';
		}
		return $out;
	}
endif;

