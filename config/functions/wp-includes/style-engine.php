<?php

return [];

/*
Not suitable in isolated PHPUnit env:

wp_style_engine_get_styles                 // why: requires WP_Style_Engine and its CSS runtime classes.
wp_style_engine_get_stylesheet_from_css_rules // why: requires WP_Style_Engine and WP_Style_Engine_CSS_Rule.
wp_style_engine_get_stylesheet_from_context // why: requires WP_Style_Engine stores and CSS runtime classes.
*/
