<?php

// ------------------auto-generated---------------------

// wp-includes/l10n.php (WP 6.8.6)
if( ! function_exists( 'translate_nooped_plural' ) ) :
	function translate_nooped_plural( $nooped_plural, $count, $domain = 'default' ) {
		if ( $nooped_plural['domain'] ) {
			$domain = $nooped_plural['domain'];
		}
	
		if ( $nooped_plural['context'] ) {
			return _nx( $nooped_plural['singular'], $nooped_plural['plural'], $count, $nooped_plural['context'], $domain );
		} else {
			return _n( $nooped_plural['singular'], $nooped_plural['plural'], $count, $domain );
		}
	}
endif;

// wp-includes/l10n.php (WP 6.8.6)
if( ! function_exists( '_nx_noop' ) ) :
	function _nx_noop( $singular, $plural, $context, $domain = null ) {
		return array(
			0          => $singular,
			1          => $plural,
			2          => $context,
			'singular' => $singular,
			'plural'   => $plural,
			'context'  => $context,
			'domain'   => $domain,
		);
	}
endif;

// wp-includes/l10n.php (WP 6.8.6)
if( ! function_exists( 'before_last_bar' ) ) :
	function before_last_bar( $text ) {
		$last_bar = strrpos( $text, '|' );
		if ( false === $last_bar ) {
			return $text;
		} else {
			return substr( $text, 0, $last_bar );
		}
	}
endif;

// wp-includes/l10n.php (WP 6.8.6)
if( ! function_exists( '_n_noop' ) ) :
	function _n_noop( $singular, $plural, $domain = null ) {
		return array(
			0          => $singular,
			1          => $plural,
			'singular' => $singular,
			'plural'   => $plural,
			'context'  => null,
			'domain'   => $domain,
		);
	}
endif;

