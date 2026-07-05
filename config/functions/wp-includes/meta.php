<?php

return [
	'_wp_register_meta_args_allowed_list' => '5.5.0',
	'get_metadata_default'                => '5.5.0',
	'get_registered_meta_keys'            => '4.6.0 mockable',
	'registered_meta_key_exists'          => '4.6.0',
	'unregister_meta_key'                 => '4.6.0',
	'is_protected_meta'                   => '3.1.3 mockable',
	'sanitize_meta'                       => '3.1.3',
	'register_meta'                       => '3.3.0',
];

/*
Not suitable in isolated PHPUnit env:

add_metadata            // why: directly queries or mutates the database via $wpdb
update_metadata         // why: directly queries or mutates the database via $wpdb
delete_metadata         // why: directly queries or mutates the database via $wpdb
get_metadata_by_mid     // why: directly queries or mutates the database via $wpdb
update_metadata_by_mid  // why: directly queries or mutates the database via $wpdb
delete_metadata_by_mid  // why: directly queries or mutates the database via $wpdb
update_meta_cache       // why: directly queries or mutates the database via $wpdb
_get_meta_table         // why: directly queries or mutates the database via $wpdb
get_metadata            // why: depends on get_metadata_raw()
get_metadata_raw        // why: depends on update_meta_cache()
metadata_exists         // why: depends on update_meta_cache()
wp_metadata_lazyloader  // why: depends on WP_Metadata_Lazyloader()
get_meta_sql            // why: depends on WP_Meta_Query
filter_default_metadata // why: depends on get_object_subtype()
get_registered_metadata // why: depends on get_object_subtype()
get_object_subtype      // why: depends on get_post_type()
*/
