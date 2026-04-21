<?php

return [
	'get_taxonomies'                      => '3.0.0 mockable',
	'get_taxonomy'                        => '2.3.0 mockable',
	'taxonomy_exists'                     => '3.0.0 mockable',
	'is_taxonomy_hierarchical'            => '2.3.0 mockable',
	'register_taxonomy_for_object_type'   => '3.0.0',
	'unregister_taxonomy_for_object_type' => '3.7.0',
	'sanitize_term'                       => '2.3.0',
	'sanitize_term_field'                 => '2.3.0',
	'is_taxonomy_viewable'                => '5.1.0 mockable',
];

/*
Not suitable in isolated PHPUnit env:

create_initial_taxonomies                // why: requires WP_Taxonomy class + full taxonomy registration bootstrap.
get_object_taxonomies                    // why: attachment-object path depends on get_attachment_taxonomies() chain not included.
register_taxonomy                        // why: requires WP_Taxonomy class + rewrite/hooks/default-term DB chain.
unregister_taxonomy                      // why: requires WP_Taxonomy methods + full unregister runtime.
get_taxonomy_labels                      // why: depends on WP_Taxonomy::get_default_labels().
get_objects_in_term                      // why: direct $wpdb query and term cache runtime dependency.
get_tax_sql                              // why: depends on WP_Tax_Query class not copied.
get_term                                 // why: depends on WP_Term model/query runtime chain.
get_term_by                              // why: depends on get_terms()/WP_Term_Query chain.
get_term_children                        // why: depends on _get_term_hierarchy() -> get_terms()/options runtime.
get_term_field                           // why: depends on get_term()/WP_Term chain.
get_term_to_edit                         // why: depends on get_term()/WP_Term chain.
get_terms                                // why: depends on WP_Term_Query class runtime.
add_term_meta                            // why: depends on metadata API + shared-term DB checks.
delete_term_meta                         // why: depends on metadata API runtime.
get_term_meta                            // why: depends on metadata API runtime.
update_term_meta                         // why: depends on metadata API + shared-term DB checks.
update_termmeta_cache                    // why: depends on meta-cache runtime.
wp_lazyload_term_meta                    // why: depends on wp_metadata_lazyloader runtime.
has_term_meta                            // why: direct $wpdb termmeta query dependency.
register_term_meta                       // why: depends on register_meta runtime.
unregister_term_meta                     // why: depends on unregister_meta_key runtime.
term_exists                              // why: depends on get_terms()/WP_Term_Query chain.
term_is_ancestor_of                      // why: depends on get_term()/WP_Term chain.
wp_count_terms                           // why: depends on get_terms()/WP_Term_Query chain.
wp_delete_object_term_relationships      // why: depends on wp_get_object_terms()/wp_remove_object_terms DB chain.
wp_delete_term                           // why: full term deletion lifecycle with DB/cache/hooks.
wp_delete_category                       // why: wrapper around wp_delete_term() DB chain.
wp_get_object_terms                      // why: depends on get_terms()/WP_Term_Query chain.
wp_insert_term                           // why: full term insert lifecycle with DB/cache/hooks.
wp_set_object_terms                      // why: term relationship DB writes + count/cache updates.
wp_add_object_terms                      // why: wrapper around wp_set_object_terms() DB chain.
wp_remove_object_terms                   // why: term relationship DB deletes + count/cache updates.
wp_unique_term_slug                      // why: depends on term_exists/get_term_by + DB uniqueness checks.
wp_update_term                           // why: full term update lifecycle with DB/cache/hooks.
wp_defer_term_counting                   // why: flush path depends on wp_update_term_count() heavy runtime chain.
wp_update_term_count                     // why: depends on wp_update_term_count_now() + deferred runtime state.
wp_update_term_count_now                 // why: depends on _update_*_term_count() + clean_term_cache() chain.
clean_object_term_cache                  // why: object cache + taxonomy registry/runtime dependency.
clean_term_cache                         // why: term cache invalidation + DB/object cache runtime dependency.
clean_taxonomy_cache                     // why: option/cache invalidation runtime dependency.
get_object_term_cache                    // why: depends on relationship cache + _prime_term_caches() chain.
update_object_term_cache                 // why: depends on get_object_taxonomies/wp_get_object_terms cache+DB chain.
update_term_cache                        // why: depends on object cache runtime API.
_get_term_hierarchy                      // why: depends on get_terms()/update_option runtime chain.
_get_term_children                       // why: depends on get_term()/hierarchy runtime chain.
_pad_term_counts                         // why: depends on term/post query runtime chain.
_prime_term_caches                       // why: depends on WP_Term cache/query runtime chain.
_update_post_term_count                  // why: direct DB term-count query/update runtime.
_update_generic_term_count               // why: direct DB term-count query/update runtime.
_split_shared_term                       // why: shared-term split lifecycle with DB/options/hooks.
_wp_batch_split_terms                    // why: cron/options/DB batch job runtime dependency.
_wp_check_for_scheduled_split_terms      // why: depends on options + wp_next_scheduled()/cron runtime.
_wp_check_split_default_terms            // why: depends on get_option()/update_option runtime API not included.
_wp_check_split_terms_in_menus           // why: depends on postmeta DB update runtime.
_wp_check_split_nav_menu_terms           // why: depends on theme mod/nav-menu runtime.
wp_get_split_terms                       // why: depends on get_option() runtime API not included.
wp_get_split_term                        // why: depends on wp_get_split_terms() option runtime chain.
wp_term_is_shared                        // why: depends on get_option() + $wpdb runtime chain.
get_term_link                            // why: depends on WP_Rewrite/get_term/get_ancestors runtime chain.
the_taxonomies                           // why: output helper depending on post/tax query runtime.
get_the_taxonomies                       // why: depends on get_post/wp_get_object_terms/get_term_link runtime.
get_post_taxonomies                      // why: depends on get_post() runtime chain.
is_object_in_term                        // why: depends on wp_get_object_terms()/term cache runtime chain.
is_object_in_taxonomy                    // why: depends on get_object_taxonomies() unresolved chain.
get_ancestors                            // why: taxonomy path depends on get_term(); post path on get_post_ancestors().
wp_get_term_taxonomy_parent_id           // why: depends on get_term()/WP_Term runtime.
wp_check_term_hierarchy_for_loops        // why: depends on wp_find_hierarchy_loop()/wp_update_term() runtime chain.
is_term_publicly_viewable                // why: depends on get_term()/WP_Term runtime.
wp_cache_set_terms_last_changed          // why: depends on wp_cache_set_last_changed() not included.
wp_check_term_meta_support_prefilter     // why: depends on get_option() runtime API not included.
*/
