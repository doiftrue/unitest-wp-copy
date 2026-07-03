<?php

return [
	'_wp_object_name_sort_cb'   => '3.1.0',
	'_wp_object_count_sort_cb'  => '3.1.0',
	'default_topic_count_scale' => '2.9.0',
	'wp_generate_tag_cloud'     => '2.3.0',
];

/*
Not suitable in isolated PHPUnit env:

get_category_link          // why: depends on get_term_link() → DB
get_category_parents       // why: depends on get_category() → DB
get_the_category           // why: depends on get_the_terms() → get_post() + DB
get_the_category_by_ID     // why: depends on get_term() → DB
get_the_category_list      // why: depends on get_the_category() → DB
in_category                // why: depends on has_category() → is_object_in_term() → DB
the_category               // why: depends on get_the_category_list() → DB
category_description       // why: depends on term_description() → get_term_field() → DB
wp_dropdown_categories     // why: depends on get_terms() → DB
wp_list_categories         // why: depends on get_terms() → DB
wp_tag_cloud               // why: depends on get_terms() → DB
walk_category_tree         // why: depends on Walker_Category class (not available)
walk_category_dropdown_tree // why: depends on Walker_CategoryDropdown class (not available)
get_tag_link               // why: depends on get_category_link() → get_term_link() → DB
get_the_tags               // why: depends on get_the_terms() → get_post() + DB
get_the_tag_list           // why: depends on get_the_term_list() → get_the_terms() → DB
the_tags                   // why: depends on get_the_tag_list() → DB
tag_description            // why: depends on term_description() → get_term_field() → DB
term_description           // why: depends on get_term_field() → DB
get_the_terms              // why: depends on get_post() + wp_get_object_terms() → DB
get_the_term_list          // why: depends on get_the_terms() → DB
get_term_parents_list      // why: depends on get_term() → DB
the_terms                  // why: depends on get_the_term_list() → DB
has_category               // why: depends on has_term() → is_object_in_term() → DB
has_tag                    // why: depends on has_term() → is_object_in_term() → DB
has_term                   // why: depends on is_object_in_term() → DB
*/
