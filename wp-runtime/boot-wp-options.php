<?php
/// Runtime extra constants - that needed for some functions to work
// `get_option( 'blog_charset' )` will be replaced with this constant in the code
$default_stub_wp_options = [
	'home'            => 'https://unitest-wp-copy.loc',
	'siteurl'         => 'https://unitest-wp-copy.loc',
	'gmt_offset'      => 0,
	'timezone_string' => 'UTC',
	'language'        => 'en-US',
	'blogdescription' => 'unitest-wp-copy runtime',
	'admin_email'     => 'admin@unitest-wp-copy.loc',
	'stylesheet'      => 'wp-copy-stylesheet',
	'template'        => 'wp-copy-template',
	'use_smilies'     => true,
	'use_balanceTags' => true,
	'WPLANG'          => '',
	'blog_charset'    => 'UTF-8',
	'html_type'       => 'text/html',
];

// allow to override stub options before including this file
$GLOBALS['stub_wp_options'] = (object) ( (array) ( $GLOBALS['stub_wp_options'] ?? [] ) + $default_stub_wp_options );

// cleanup
unset( $default_stub_wp_options );
