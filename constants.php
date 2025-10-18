<?php
/// Basic constants
defined( 'ABSPATH' )        || define( 'ABSPATH', __DIR__ . '/' );
defined( 'WP_CONTENT_DIR' ) || define( 'WP_CONTENT_DIR', '/path/to/wp/wp-content' );
defined( 'WP_CONTENT_URL' ) || define( 'WP_CONTENT_URL', 'https://mytest.com/wp-content' );

/// Runtime extra constants - that needed for some functions to work
// `get_option( 'blog_charset' )` will be replaced with this constant in the code
defined( 'WPCOPY_OPTION__BLOG_CHARSET' )    || define( 'WPCOPY_OPTION__BLOG_CHARSET', 'UTF-8' );
defined( 'WPCOPY_OPTION__TIMEZONE_STRING' ) || define( 'WPCOPY_OPTION__TIMEZONE_STRING', 'UTC' );
defined( 'WPCOPY_OPTION__GMT_OFFSET' )      || define( 'WPCOPY_OPTION__GMT_OFFSET', 0 );
defined( 'WPCOPY_OPTION__USE_SMILIES' )     || define( 'WPCOPY_OPTION__USE_SMILIES', true );
defined( 'WPCOPY_OPTION__HOME' )            || define( 'WPCOPY_OPTION__HOME', 'https://mytest.com' );
defined( 'WPCOPY_OPTION__USE_BALANCETAGS' ) || define( 'WPCOPY_OPTION__USE_BALANCETAGS', true );
defined( 'WPCOPY_OPTION__WPLANG' )          || define( 'WPCOPY_OPTION__WPLANG', '' );
