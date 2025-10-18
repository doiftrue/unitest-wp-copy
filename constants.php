<?php
/// Basic constants
defined( 'ABSPATH' )        || define( 'ABSPATH', __DIR__ . '/' );
defined( 'WP_CONTENT_DIR' ) || define( 'WP_CONTENT_DIR', '/path/to/wp/wp-content' );
defined( 'WP_CONTENT_URL' ) || define( 'WP_CONTENT_URL', 'https://mytest.com/wp-content' );

/// Runtime extra constants - that needed for some functions to work
// `get_option( 'blog_charset' )` will be replaced with this constant in the code
defined( 'WPCOPY__OPTION_BLOG_CHARSET' ) || define( 'WPCOPY__OPTION_BLOG_CHARSET', 'UTF-8' );
