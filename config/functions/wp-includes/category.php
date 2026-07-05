<?php
return [
	'sanitize_category'       => '2.3.0',
	'sanitize_category_field' => '2.3.0',
	'_make_cat_compat'        => '2.3.0',
];

/*
Not suitable in isolated PHPUnit env:

get_categories       // why: depends on get_terms()
get_category         // why: depends on get_term()
get_category_by_path // why: depends on get_terms()
get_category_by_slug // why: depends on get_term_by()
get_cat_ID           // why: depends on get_term_by()
get_cat_name         // why: depends on get_term()
cat_is_ancestor_of   // why: depends on term_is_ancestor_of()
get_tags             // why: depends on get_terms()
get_tag              // why: depends on get_term()
clean_category_cache // why: depends on clean_term_cache()
*/
