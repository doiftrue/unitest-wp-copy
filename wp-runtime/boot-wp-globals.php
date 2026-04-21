<?php
/**
 * @var \Unitest_WP_Copy\Bootstrap $this
 */

smilies_init();

$GLOBALS['timestart'] = microtime( true );
$_SERVER['HTTP_HOST'] = parse_url( $GLOBALS['stub_wp_options']->home, PHP_URL_HOST );

global $wp_plugin_paths;
$wp_plugin_paths || $wp_plugin_paths = [];

global $shortcode_tags;
$shortcode_tags = [];

global $wp_locale;
$wp_locale = new \WP_Locale();

global $wp_post_types;
$wp_post_types = is_array( $wp_post_types ?? null ) ? $wp_post_types : [];

global $wp_taxonomies;
$wp_taxonomies = is_array( $wp_taxonomies ?? null ) ? $wp_taxonomies : [];

// set globals from version.php
global $wp_version, $wp_db_version, $tinymce_version, $required_php_version, $required_php_extensions, $required_mysql_version;
require_once "$this->line_extra_dir/wp-includes/version.php";
