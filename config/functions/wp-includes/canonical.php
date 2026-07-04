<?php

return [
	'_remove_qs_args_if_not_in_url' => '3.4.0',
	'strip_fragment_from_url'       => '4.4.0',
];

/*
Not suitable in isolated PHPUnit env:

redirect_canonical               // why: requires full query/request/rewrite redirect lifecycle.
redirect_guess_404_permalink     // why: depends on $wpdb and post query/permalink runtime.
wp_redirect_admin_locations      // why: depends on live request, rewrite, and redirect lifecycle.
*/
