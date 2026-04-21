<?php

return [
	// Pure label map used by multiple format helpers.
	'get_post_format_strings'          => '3.1.0',
	// Pure slug list derived from get_post_format_strings().
	'get_post_format_slugs'            => '3.1.0',
	// Pure label resolver for a single post format slug.
	'get_post_format_string'           => '3.1.0',
	// Pure term-name adaptation helpers (do not query DB by themselves).
	'_post_format_get_term'            => '3.1.0',
	'_post_format_get_terms'           => '3.1.0',
	'_post_format_wp_get_object_terms' => '3.1.0',

];

/*
Not suitable in isolated PHPUnit env:

get_post_format       // why: requires get_post(), post_type_supports(), get_the_terms().
has_post_format       // why: depends on has_term()/taxonomy runtime.
set_post_format       // why: depends on get_post() and wp_set_post_terms().
get_post_format_link  // why: depends on get_term_by() and get_term_link().
_post_format_request  // why: depends on get_taxonomy() registry/runtime not available in this project by default.
_post_format_link     // why: depends on WP_Rewrite runtime object state.
*/
