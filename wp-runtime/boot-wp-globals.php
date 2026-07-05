<?php
/**
 * @var \Unitest_WP_Copy\Bootstrap $this
 */

smilies_init();

$GLOBALS['timestart'] = microtime( true );
$_SERVER['HTTP_HOST'] = parse_url( $GLOBALS['stub_wp_options']->home, PHP_URL_HOST );

global $wp_plugin_paths;
$wp_plugin_paths || $wp_plugin_paths = [];

// from wp-includes/shortcodes.php
global $shortcode_tags;
$shortcode_tags = [];

global $wp_locale;
$wp_locale = new WP_Locale();

global $wp_object_cache;
$wp_object_cache = new WP_Object_Cache();

global $wp_post_types;
$wp_post_types = is_array( $wp_post_types ?? null ) ? $wp_post_types : [];

global $wp_taxonomies;
$wp_taxonomies = is_array( $wp_taxonomies ?? null ) ? $wp_taxonomies : [];

global $wp_meta_keys;
$wp_meta_keys = [];

// from wp-includes/version.php
global $wp_version, $wp_db_version, $tinymce_version, $required_php_version, $required_php_extensions, $required_mysql_version;
require_once "$this->line_extra_dir/wp-includes/version.php";

// WP 6.8+ uses password_hash() directly; $wp_hasher is only for older versions.
global $wp_hasher;
if ( version_compare( $wp_version, '6.8', '<' ) ) {
	$wp_hasher = new PasswordHash( 8, true );
}

// CUSTOM ADAPTERS

// SQL-string adapter only. It exposes metadata table names and escaping helpers,
// but intentionally has no database connection or query methods.
global $wpdb;
$wpdb || $wpdb = new \Unitest_WP_Copy\WPDB_Runtime();
