<?php

return [
	// Main API accessors backed by WP_Script_Modules in-memory registry.
	'wp_script_modules'           => '',
	'wp_register_script_module'   => '',
	'wp_enqueue_script_module'    => '',
	'wp_dequeue_script_module'    => '',
	'wp_deregister_script_module' => '',

];

/*
Not suitable in isolated PHPUnit env (filesystem/bootstrap dependency):

wp_default_script_modules  // why: reads assets file via ABSPATH/WPINC and depends on wp_scripts_get_suffix()/includes_url() bootstrap chain.
*/
