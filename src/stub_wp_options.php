<?php
/// Runtime extra constants - that needed for some functions to work
// `get_option( 'blog_charset' )` will be replaced with this constant in the code
$default_stub_wp_options = [
	'blog_charset'    => 'UTF-8',
	'timezone_string' => 'UTC',
	'gmt_offset'      => 0,
	'use_smilies'     => true,
	'home'            => 'https://mytest.com',
	'siteurl'         => 'https://mytest.com',
	'use_balanceTags' => true,
	'WPLANG'          => '',
];

// allow to override stub options before including this file
$GLOBALS['stub_wp_options'] = (object) ( ( $GLOBALS['stub_wp_options'] ?? [] ) + $default_stub_wp_options );

// cleanup
unset( $default_stub_wp_options );
