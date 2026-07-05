<?php

// ------------------auto-generated---------------------

// wp-includes/feed.php (WP 6.9.4)
if( ! function_exists( 'feed_content_type' ) ) :
	function feed_content_type( $type = '' ) {
		if ( empty( $type ) ) {
			$type = get_default_feed();
		}
	
		$types = array(
			'rss'      => 'application/rss+xml',
			'rss2'     => 'application/rss+xml',
			'rss-http' => 'text/xml',
			'atom'     => 'application/atom+xml',
			'rdf'      => 'application/rdf+xml',
		);
	
		$content_type = ( ! empty( $types[ $type ] ) ) ? $types[ $type ] : 'application/octet-stream';
	
		/**
		 * Filters the content type for a specific feed type.
		 *
		 * @since 2.8.0
		 *
		 * @param string $content_type Content type indicating the type of data that a feed contains.
		 * @param string $type         Type of feed. Possible values include 'rss', rss2', 'atom', and 'rdf'.
		 */
		return apply_filters( 'feed_content_type', $content_type, $type );
	}
endif;

// wp-includes/feed.php (WP 6.9.4)
if( ! function_exists( 'prep_atom_text_construct' ) ) :
	function prep_atom_text_construct( $data ) {
		if ( ! str_contains( $data, '<' ) && ! str_contains( $data, '&' ) ) {
			return array( 'text', $data );
		}
	
		if ( ! function_exists( 'xml_parser_create' ) ) {
			wp_trigger_error( '', __( "PHP's XML extension is not available. Please contact your hosting provider to enable PHP's XML extension." ) );
	
			return array( 'html', "<![CDATA[$data]]>" );
		}
	
		$parser = xml_parser_create();
		xml_parse( $parser, '<div>' . $data . '</div>', true );
		$code = xml_get_error_code( $parser );
	
		if ( PHP_VERSION_ID < 80000 ) { // xml_parser_free() has no effect as of PHP 8.0.
			xml_parser_free( $parser );
		}
	
		unset( $parser );
	
		if ( ! $code ) {
			if ( ! str_contains( $data, '<' ) ) {
				return array( 'text', $data );
			} else {
				$data = "<div xmlns='http://www.w3.org/1999/xhtml'>$data</div>";
				return array( 'xhtml', $data );
			}
		}
	
		if ( ! str_contains( $data, ']]>' ) ) {
			return array( 'html', "<![CDATA[$data]]>" );
		} else {
			return array( 'html', htmlspecialchars( $data ) );
		}
	}
endif;

