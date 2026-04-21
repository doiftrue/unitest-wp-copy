<?php

return [
	'_n_noop'                    => '2.5.0',
	'_nx_noop'                   => '2.8.0',
	'is_rtl'                     => '3.0.0',
	'translate_nooped_plural'    => '3.1.0',
	'get_locale'                 => '1.5.0 mockable',
	'wp_get_list_item_separator' => '6.0.0',
	'wp_get_word_count_type'     => '6.2.0',
	'before_last_bar'            => '2.8.0',
	// '__'         => '', // mocked
	// '_e'         => '', // mocked
	// '_x'         => '', // mocked
	// '_n'         => '', // mocked
	// '_nx'        => '', // mocked
	// 'esc_html__' => '', // mocked
	// 'esc_html_e' => '', // mocked
	// 'esc_html_x' => '', // mocked
	// 'esc_attr__' => '', // mocked
	// 'esc_attr_e' => '', // mocked
	// 'esc_attr_x' => '', // mocked
];

/*
Not suitable in isolated PHPUnit env:

determine_locale                      // why: depends on is_admin() runtime context.
_ex                                   // why: i18n runtime dependency (context translation).
_load_textdomain_just_in_time         // why: textdomain loading/filesystem/runtime dependency.
get_available_languages               // why: language pack filesystem dependency.
get_translations_for_domain           // why: translation registry/runtime dependency.
get_user_locale                       // why: user/session runtime dependency.
has_translation                       // why: translation registry/runtime dependency.
is_locale_switched                    // why: locale switching runtime dependency.
is_textdomain_loaded                  // why: translation registry/runtime dependency.
load_child_theme_textdomain           // why: theme/filesystem runtime dependency.
load_default_textdomain               // why: default translation files/runtime dependency.
load_muplugin_textdomain              // why: plugin/filesystem runtime dependency.
load_plugin_textdomain                // why: plugin/filesystem runtime dependency.
load_script_textdomain                // why: script translation files/runtime dependency.
load_script_translations              // why: script translation files/runtime dependency.
load_textdomain                       // why: MO/PHP translation file loading dependency.
load_theme_textdomain                 // why: theme/filesystem runtime dependency.
restore_current_locale                // why: locale switching runtime dependency.
restore_previous_locale               // why: locale switching runtime dependency.
switch_to_locale                      // why: locale switching runtime dependency.
switch_to_user_locale                 // why: user/session + locale runtime dependency.
translate                             // why: full translation registry/runtime dependency.
translate_settings_using_i18n_schema  // why: settings/i18n schema runtime dependency.
translate_user_role                   // why: roles/capabilities + translation runtime dependency.
translate_with_gettext_context        // why: full translation registry/runtime dependency.
unload_textdomain                     // why: translation registry/runtime dependency.
wp_dropdown_languages                 // why: admin UI + translation files/runtime dependency.
wp_get_installed_translations         // why: translation files/filesystem dependency.
wp_get_l10n_php_file_data             // why: translation file parsing/filesystem dependency.
wp_get_pomo_file_data                 // why: translation file parsing/filesystem dependency.
*/
