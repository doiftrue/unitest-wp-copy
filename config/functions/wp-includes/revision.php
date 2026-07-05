<?php
return [
	'_wp_get_post_revision_version' => '3.6.0',
];
/*
Not suitable in isolated PHPUnit env:

_wp_post_revision_fields                     // why: depends on get_post()
_wp_post_revision_data                       // why: depends on get_post()
wp_save_post_revision_on_insert              // why: depends on wp_save_post_revision()
wp_save_post_revision                        // why: depends on WP_Post runtime
wp_get_post_autosave                         // why: depends on WP_Query runtime
wp_is_post_revision                          // why: depends on wp_get_post_revision()
wp_is_post_autosave                          // why: depends on wp_get_post_revision()
_wp_put_post_revision                        // why: depends on get_post()
wp_save_revisioned_meta_fields               // why: depends on get_post_type()
wp_get_post_revision                         // why: depends on get_post()
wp_restore_post_revision                     // why: depends on wp_get_post_revision()
wp_restore_post_revision_meta                // why: depends on get_post_type()
_wp_copy_post_meta                           // why: depends on get_post_meta()
wp_post_revision_meta_keys                   // why: depends on get_registered_meta_keys()
wp_check_revisioned_meta_fields_have_changed // why: depends on WP_Post runtime
wp_delete_post_revision                      // why: depends on WP_Post runtime
wp_get_post_revisions                        // why: depends on get_post()
wp_get_latest_revision_id_and_total_count    // why: depends on WP_Query runtime
wp_get_post_revisions_url                    // why: depends on WP_Post runtime
wp_revisions_enabled                         // why: depends on wp_revisions_to_keep()
wp_revisions_to_keep                         // why: depends on WP_Post runtime
_set_preview                                 // why: depends on wp_get_post_autosave()
_show_post_preview                           // why: depends on wp_verify_nonce()
_wp_preview_terms_filter                     // why: depends on get_post()
_wp_preview_post_thumbnail_filter            // why: depends on get_post()
_wp_upgrade_revisions_of_post                // why: directly queries or mutates the database via $wpdb
_wp_preview_meta_filter                      // why: depends on get_post()
*/
