<?php
return [
	'sanitize_bookmark'       => '2.3.0',
	'sanitize_bookmark_field' => '2.3.0',
];

/*
Not suitable in isolated PHPUnit env:

get_bookmark         // why: directly queries or mutates the database via $wpdb
get_bookmarks        // why: directly queries or mutates the database via $wpdb
get_bookmark_field   // why: depends on get_bookmark()
clean_bookmark_cache // why: depends on clean_object_term_cache()
*/
