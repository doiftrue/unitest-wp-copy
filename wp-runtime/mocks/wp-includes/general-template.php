<?php
/**
 * Mock implementations of WordPress template functions.
 * Supports WP_Mock unit testing implementation.
 */

use Unitest_WP_Copy\WP_Mock_Utils;

if ( ! function_exists( 'get_bloginfo' ) ) :
	function get_bloginfo( $show = '', $filter = 'raw' ) {
		if ( WP_Mock_Utils::has_handler( __FUNCTION__ ) ) {
			return WP_Mock_Utils::call( __FUNCTION__, func_get_args() );
		}

		$output = '';

		switch ( $show ) {
			case 'home':    // Deprecated in WP core, but still supported.
			case 'siteurl': // Deprecated in WP core, but still supported.
			case 'url':
				$output = home_url();
				break;
			case 'wpurl':
				$output = site_url();
				break;
			case 'description':
				$output = $GLOBALS['stub_wp_options']->blogdescription;
				break;
			case 'rdf_url':
				$output = home_url( '/feed/rdf' ); // was: get_feed_link( 'rdf' );
				break;
			case 'rss_url':
				$output = home_url( '/feed/rss' ); // was: get_feed_link( 'rss' );
				break;
			case 'rss2_url':
				$output = home_url( '/feed' ); // was: get_feed_link( 'rss2' );
				break;
			case 'atom_url':
				$output = home_url( '/feed/atom' ); // was: get_feed_link( 'atom' );
				break;
			case 'comments_atom_url':
				$output = home_url( '/comments/feed/atom' ); // was: get_feed_link( 'comments_atom' );
				break;
			case 'comments_rss2_url':
				$output = home_url( '/comments/feed' ); // was: get_feed_link( 'comments_rss2' );
				break;
			case 'pingback_url':
				$output = site_url( 'xmlrpc.php' );
				break;
			case 'stylesheet_url':
				$output = get_stylesheet_uri();
				break;
			case 'stylesheet_directory':
				$output = get_stylesheet_directory_uri();
				break;
			case 'template_directory':
			case 'template_url':
				$output = get_template_directory_uri();
				break;
			case 'admin_email':
				$output = $GLOBALS['stub_wp_options']->admin_email;
				break;
			case 'charset':
				$output = $GLOBALS['stub_wp_options']->blog_charset;
				if ( '' === $output ) {
					$output = 'UTF-8';
				}
				break;
			case 'html_type':
				$output = $GLOBALS['stub_wp_options']->html_type;
				break;
			case 'version':
				$output = wp_get_wp_version();
				break;
			case 'language':
				$output = $GLOBALS['stub_wp_options']->language;
				break;
			default:
				$output = '';
		}

		if ( 'display' === $filter ) {
			if (
				str_contains( $show, 'url' )
				|| str_contains( $show, 'directory' )
				|| str_contains( $show, 'home' )
			) {
				$output = apply_filters( 'bloginfo_url', $output, $show );
			} else {
				$output = apply_filters( 'bloginfo', $output, $show );
			}
		}

		return $output;
	}
endif;
