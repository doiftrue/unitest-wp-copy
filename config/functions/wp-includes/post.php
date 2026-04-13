<?php

return [
	'post_type_exists'     => '',
	'get_post_type_object' => '',
];

/*
Not suitable in isolated PHPUnit env (post object runtime dependency):

get_post_type  // why: depends on get_post().
*/
