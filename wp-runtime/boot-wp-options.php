<?php
/// Runtime option values needed by copied and custom-mock functions.
$default_stub_wp_options = [
	'home'                        => 'https://wp.test',
	'siteurl'                     => 'https://wp.test',
	'gmt_offset'                  => 0,
	'timezone_string'             => 'UTC',
	'start_of_week'               => 1,
	'language'                    => 'en-US',
	'blogname'                    => 'Unitest WP Copy',
	'blogdescription'             => 'unitest-wp-copy runtime',
	'admin_email'                 => 'admin@wp.test',
	'stylesheet'                  => 'wp-test-stylesheet',
	'template'                    => 'wp-test-template',
	'use_smilies'                 => true,
	'use_balanceTags'             => true,
	'WPLANG'                      => '',
	'blog_charset'                => 'UTF-8',
	'html_type'                   => 'text/html',
	'thumbnail_size_w'            => 150,
	'thumbnail_size_h'            => 150,
	'thumbnail_crop'              => true,
	'medium_size_w'               => 300,
	'medium_size_h'               => 300,
	'medium_crop'                 => false,
	'medium_large_size_w'         => 768,
	'medium_large_size_h'         => 0,
	'medium_large_crop'           => false,
	'large_size_w'                => 1024,
	'large_size_h'                => 1024,
	'large_crop'                  => false,
	'banned_email_domains'        => [],
	'upload_filetypes'            => 'jpg jpeg png gif',
	'upload_space_check_disabled' => false,
	'fileupload_maxk'             => 1500,
	'registration'               => 'none',
	'blog_upload_space'          => 100,
	'https_migration_required'   => false,
];

// Allow overriding stub options before including this file.
$GLOBALS['stub_wp_options'] = (object) ( (array) ( $GLOBALS['stub_wp_options'] ?? [] ) + $default_stub_wp_options );

$default_stub_wp_site_options = [
	'siteurl'                     => $GLOBALS['stub_wp_options']->siteurl,
	'WPLANG'                      => $GLOBALS['stub_wp_options']->WPLANG,
	'banned_email_domains'        => [],
	'upload_filetypes'            => 'jpg jpeg png gif',
	'upload_space_check_disabled' => false,
	'fileupload_maxk'             => 1500,
	'registration'                => 'none',
	'blog_upload_space'           => 100,
];

// Network options are separate when the runtime is switched to multisite mode.
$GLOBALS['stub_wp_site_options'] = (object) ( (array) ( $GLOBALS['stub_wp_site_options'] ?? [] ) + $default_stub_wp_site_options );

// Cleanup.
unset( $default_stub_wp_options, $default_stub_wp_site_options );
