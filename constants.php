<?php

defined( 'ABSPATH' ) || define( 'ABSPATH', __DIR__ . '/' );

/// Runtime extra constants - that needed for some functions to work
// `get_option( 'blog_charset' )` will be replaced with this constant in the code
defined( 'WPCOPY__OPTION_BLOG_CHARSET' ) || define( 'WPCOPY__OPTION_BLOG_CHARSET', 'UTF-8' );
