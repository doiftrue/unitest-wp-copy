<?php

defined( 'ABSPATH' )             || define( 'ABSPATH', '/path/to/wp/' );
defined( 'WPINC' )               || define( 'WPINC', 'wp-includes' );
defined( 'WP_CONTENT_DIR' )      || define( 'WP_CONTENT_DIR', '/path/to/wp/wp-content' );
defined( 'WP_CONTENT_URL' )      || define( 'WP_CONTENT_URL', 'https://test.dev/wp-content' );
defined( 'WP_ENVIRONMENT_TYPE' ) || define( 'WP_ENVIRONMENT_TYPE', 'local' );

// Salt constants for wp_salt() / wp_hash() — unique test values.
defined( 'AUTH_KEY' )         || define( 'AUTH_KEY',         'test-auth-key-unitest-wp-copy' );
defined( 'SECURE_AUTH_KEY' )  || define( 'SECURE_AUTH_KEY',  'test-secure-auth-key-unitest-wp-copy' );
defined( 'LOGGED_IN_KEY' )    || define( 'LOGGED_IN_KEY',    'test-logged-in-key-unitest-wp-copy' );
defined( 'NONCE_KEY' )        || define( 'NONCE_KEY',        'test-nonce-key-unitest-wp-copy' );
defined( 'AUTH_SALT' )        || define( 'AUTH_SALT',        'test-auth-salt-unitest-wp-copy' );
defined( 'SECURE_AUTH_SALT' ) || define( 'SECURE_AUTH_SALT', 'test-secure-auth-salt-unitest-wp-copy' );
defined( 'LOGGED_IN_SALT' )   || define( 'LOGGED_IN_SALT',   'test-logged-in-salt-unitest-wp-copy' );
defined( 'NONCE_SALT' )       || define( 'NONCE_SALT',       'test-nonce-salt-unitest-wp-copy' );
defined( 'SECRET_KEY' )       || define( 'SECRET_KEY',       'test-secret-key-unitest-wp-copy' );

/// from class-wpdb.php
defined( 'EZSQL_VERSION' ) || define( 'EZSQL_VERSION', 'WP1.25' );
defined( 'OBJECT' )        || define( 'OBJECT', 'OBJECT' );
defined( 'object' )        || define( 'object', 'OBJECT' );
defined( 'OBJECT_K' )      || define( 'OBJECT_K', 'OBJECT_K' );
defined( 'ARRAY_A' )       || define( 'ARRAY_A', 'ARRAY_A' );
defined( 'ARRAY_N' )       || define( 'ARRAY_N', 'ARRAY_N' );
