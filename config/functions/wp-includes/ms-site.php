<?php

return [
	'wp_cache_set_sites_last_changed' => '5.1.0',
];

/*
Not suitable in isolated PHPUnit env:

wp_insert_site                                // why: depends on $wpdb
wp_update_site                                // why: depends on $wpdb
wp_delete_site                                // why: depends on $wpdb
get_site                                      // why: depends on WP_Site::get_instance() (DB/cache)
_prime_site_caches                            // why: depends on $wpdb
wp_lazyload_site_meta                         // why: depends on wp_metadata_lazyloader() (not available)
update_site_cache                             // why: depends on update_sitemeta_cache() -> update_meta_cache() (DB)
update_sitemeta_cache                         // why: depends on update_meta_cache() (DB)
get_sites                                     // why: depends on WP_Site_Query (DB)
wp_prepare_site_data                          // why: depends on DB validation chain
wp_normalize_site_data                        // why: depends on DB validation chain
wp_validate_site_data                         // why: depends on DB validation chain
wp_initialize_site                            // why: heavy DB site initialization
wp_uninitialize_site                          // why: heavy DB site uninitialization
wp_is_site_initialized                        // why: depends on DB
clean_blog_cache                              // why: depends on get_site() -> WP_Site::get_instance() (DB)
add_site_meta                                 // why: depends on add_metadata() (not available)
delete_site_meta                              // why: depends on delete_metadata() (not available)
get_site_meta                                 // why: depends on get_metadata() (not available)
update_site_meta                              // why: depends on update_metadata() (not available)
delete_site_meta_by_key                       // why: depends on delete_metadata() (not available)
wp_maybe_update_network_site_counts_on_update // why: depends on wp_maybe_update_network_site_counts() (DB)
wp_maybe_transition_site_statuses_on_update   // why: complex status transition chain
wp_maybe_clean_new_site_cache_on_update       // why: depends on clean_blog_cache() (DB)
wp_update_blog_public_option_on_site_update   // why: depends on wp_is_site_initialized() (DB), update_blog_option()
wp_check_site_meta_support_prefilter          // why: depends on is_site_meta_supported() (DB), $wpdb
*/
