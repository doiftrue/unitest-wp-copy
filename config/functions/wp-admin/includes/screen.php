<?php

return [
	'get_column_headers' => '',
	'add_screen_option' => '',
	'get_current_screen' => '',
	'set_current_screen' => '',
];

/*
Not suitable in isolated PHPUnit env (user/meta runtime dependency):

get_hidden_columns    // why: depends on get_user_option() user/meta runtime.
meta_box_prefs        // why: depends on get_hidden_meta_boxes() and current user/meta runtime.
get_hidden_meta_boxes // why: depends on get_user_option() user/meta runtime.
*/
