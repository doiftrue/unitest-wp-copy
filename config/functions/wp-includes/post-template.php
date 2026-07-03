<?php

return [];

/*
Not suitable in isolated PHPUnit env:

All functions depend on get_post() / $wpdb / WP_Query / Walker classes not available in runtime:

the_ID                        // why: depends on get_the_ID() → get_post() → DB
get_the_ID                    // why: depends on get_post() → DB
the_title                     // why: depends on get_the_title() → get_post() → DB
the_title_attribute           // why: depends on get_the_title() → get_post() → DB
get_the_title                 // why: depends on get_post() → DB
the_guid                      // why: depends on get_the_guid() → get_post() → DB
get_the_guid                  // why: depends on get_post() → DB
the_content                   // why: depends on get_the_content() → get_post() → DB
get_the_content               // why: depends on get_post() + $more global → DB
the_excerpt                   // why: depends on get_the_excerpt() → get_post() → DB
get_the_excerpt               // why: depends on get_post() → DB
has_excerpt                   // why: depends on get_post() → DB
post_class                    // why: depends on get_post_class() → get_post() → DB
get_post_class                // why: depends on get_post() + get_post_type() → DB
body_class                    // why: depends on get_body_class() → WP_Query conditionals + DB
get_body_class                // why: depends on WP_Query conditionals + DB
post_password_required        // why: depends on get_post() → DB
wp_link_pages                 // why: depends on $page/$numpages globals → WP_Query loop
_wp_link_page                 // why: depends on get_permalink() → DB
post_custom                   // why: depends on get_post_custom() → get_post_meta() → DB
the_meta                      // why: depends on get_post_custom_keys() → DB
wp_dropdown_pages             // why: depends on get_pages() → DB
wp_list_pages                 // why: depends on get_pages() → DB
wp_page_menu                  // why: depends on wp_list_pages() → DB
walk_page_tree                // why: depends on Walker_Page class (not available)
walk_page_dropdown_tree       // why: depends on Walker_PageDropdown class (not available)
the_attachment_link           // why: depends on wp_get_attachment_link() → DB
wp_get_attachment_link        // why: depends on get_post() + wp_get_attachment_image() → DB
prepend_attachment            // why: depends on get_post() → DB
get_the_password_form         // why: depends on get_post() → DB
is_page_template              // why: depends on get_page_template_slug() → get_post() → DB
get_page_template_slug        // why: depends on get_post() + get_post_meta() → DB
wp_post_revision_title        // why: depends on get_post() → DB
wp_post_revision_title_expanded // why: depends on get_post() → DB
wp_list_post_revisions        // why: depends on wp_get_post_revisions() → DB
get_post_parent               // why: depends on get_post() → DB
has_post_parent               // why: depends on get_post_parent() → get_post() → DB
*/
