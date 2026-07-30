<?php

// ------------------auto-generated---------------------

// wp-includes/embed.php (WP 6.8.6)
if( ! function_exists( 'wp_maybe_enqueue_oembed_host_js' ) ) :
	function wp_maybe_enqueue_oembed_host_js( $html ) {
		if (
			has_action( 'wp_head', 'wp_oembed_add_host_js' )
			&&
			preg_match( '/<blockquote\s[^>]*?wp-embedded-content/', $html )
		) {
			wp_enqueue_script( 'wp-embed' );
		}
		return $html;
	}
endif;

// wp-includes/embed.php (WP 6.8.6)
if( ! function_exists( 'wp_filter_oembed_iframe_title_attribute' ) ) :
	function wp_filter_oembed_iframe_title_attribute( $result, $data, $url ) {
		if ( false === $result || ! in_array( $data->type, array( 'rich', 'video' ), true ) ) {
			return $result;
		}
	
		$title = ! empty( $data->title ) ? $data->title : '';
	
		$pattern = '`<iframe([^>]*)>`i';
		if ( preg_match( $pattern, $result, $matches ) ) {
			$attrs = wp_kses_hair( $matches[1], wp_allowed_protocols() );
	
			foreach ( $attrs as $attr => $item ) {
				$lower_attr = strtolower( $attr );
				if ( $lower_attr === $attr ) {
					continue;
				}
				if ( ! isset( $attrs[ $lower_attr ] ) ) {
					$attrs[ $lower_attr ] = $item;
					unset( $attrs[ $attr ] );
				}
			}
		}
	
		if ( ! empty( $attrs['title']['value'] ) ) {
			$title = $attrs['title']['value'];
		}
	
		/**
		 * Filters the title attribute of the given oEmbed HTML iframe.
		 *
		 * @since 5.2.0
		 *
		 * @param string $title  The title attribute.
		 * @param string $result The oEmbed HTML result.
		 * @param object $data   A data object result from an oEmbed provider.
		 * @param string $url    The URL of the content to be embedded.
		 */
		$title = apply_filters( 'oembed_iframe_title_attribute', $title, $result, $data, $url );
	
		if ( '' === $title ) {
			return $result;
		}
	
		if ( isset( $attrs['title'] ) ) {
			unset( $attrs['title'] );
			$attr_string = implode( ' ', wp_list_pluck( $attrs, 'whole' ) );
			$result      = str_replace( $matches[0], '<iframe ' . trim( $attr_string ) . '>', $result );
		}
		return str_ireplace( '<iframe ', sprintf( '<iframe title="%s" ', esc_attr( $title ) ), $result );
	}
endif;

// wp-includes/embed.php (WP 6.8.6)
if( ! function_exists( 'wp_oembed_ensure_format' ) ) :
	function wp_oembed_ensure_format( $format ) {
		if ( ! in_array( $format, array( 'json', 'xml' ), true ) ) {
			return 'json';
		}
	
		return $format;
	}
endif;

// wp-includes/embed.php (WP 6.8.6)
if( ! function_exists( '_oembed_create_xml' ) ) :
	function _oembed_create_xml( $data, $node = null ) {
		if ( ! is_array( $data ) || empty( $data ) ) {
			return false;
		}
	
		if ( null === $node ) {
			$node = new SimpleXMLElement( '<oembed></oembed>' );
		}
	
		foreach ( $data as $key => $value ) {
			if ( is_numeric( $key ) ) {
				$key = 'oembed';
			}
	
			if ( is_array( $value ) ) {
				$item = $node->addChild( $key );
				_oembed_create_xml( $value, $item );
			} else {
				$node->addChild( $key, esc_html( $value ) );
			}
		}
	
		return $node->asXML();
	}
endif;

// wp-includes/embed.php (WP 6.8.6)
if( ! function_exists( '_oembed_filter_feed_content' ) ) :
	function _oembed_filter_feed_content( $content ) {
		$p = new WP_HTML_Tag_Processor( $content );
		while ( $p->next_tag( array( 'tag_name' => 'iframe' ) ) ) {
			if ( $p->has_class( 'wp-embedded-content' ) ) {
				$p->remove_attribute( 'style' );
			}
		}
		return $p->get_updated_html();
	}
endif;

// wp-includes/embed.php (WP 6.8.6)
if( ! function_exists( 'wp_embed_handler_audio' ) ) :
	function wp_embed_handler_audio( $matches, $attr, $url, $rawattr ) {
		$audio = sprintf( '[audio src="%s" /]', esc_url( $url ) );
	
		/**
		 * Filters the audio embed output.
		 *
		 * @since 3.6.0
		 *
		 * @param string $audio   Audio embed output.
		 * @param array  $attr    An array of embed attributes.
		 * @param string $url     The original URL that was matched by the regex.
		 * @param array  $rawattr The original unmodified attributes.
		 */
		return apply_filters( 'wp_embed_handler_audio', $audio, $attr, $url, $rawattr );
	}
endif;

// wp-includes/embed.php (WP 6.8.6)
if( ! function_exists( 'wp_embed_handler_video' ) ) :
	function wp_embed_handler_video( $matches, $attr, $url, $rawattr ) {
		$dimensions = '';
		if ( ! empty( $rawattr['width'] ) && ! empty( $rawattr['height'] ) ) {
			$dimensions .= sprintf( 'width="%d" ', (int) $rawattr['width'] );
			$dimensions .= sprintf( 'height="%d" ', (int) $rawattr['height'] );
		}
		$video = sprintf( '[video %s src="%s" /]', $dimensions, esc_url( $url ) );
	
		/**
		 * Filters the video embed output.
		 *
		 * @since 3.6.0
		 *
		 * @param string $video   Video embed output.
		 * @param array  $attr    An array of embed attributes.
		 * @param string $url     The original URL that was matched by the regex.
		 * @param array  $rawattr The original unmodified attributes.
		 */
		return apply_filters( 'wp_embed_handler_video', $video, $attr, $url, $rawattr );
	}
endif;

// wp-includes/embed.php (WP 6.8.6)
if( ! function_exists( 'wp_embed_defaults' ) ) :
	function wp_embed_defaults( $url = '' ) {
		if ( ! empty( $GLOBALS['content_width'] ) ) {
			$width = (int) $GLOBALS['content_width'];
		}
	
		if ( empty( $width ) ) {
			$width = 500;
		}
	
		$height = min( (int) ceil( $width * 1.5 ), 1000 );
	
		/**
		 * Filters the default array of embed dimensions.
		 *
		 * @since 2.9.0
		 *
		 * @param int[]  $size {
		 *     Indexed array of the embed width and height in pixels.
		 *
		 *     @type int $0 The embed width.
		 *     @type int $1 The embed height.
		 * }
		 * @param string $url  The URL that should be embedded.
		 */
		return apply_filters( 'embed_defaults', compact( 'width', 'height' ), $url );
	}
endif;

