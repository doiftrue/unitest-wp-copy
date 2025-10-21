<?php

class Extra_Replacer {

	private static array $stub_wp_options = [
		"get_option( 'blog_charset' )"    => "\$GLOBALS['stub_wp_options']->blog_charset",
		"get_option( 'timezone_string' )" => "\$GLOBALS['stub_wp_options']->timezone_string",
		"get_option( 'gmt_offset' )"      => "\$GLOBALS['stub_wp_options']->gmt_offset",
		"get_option( 'use_smilies' )"     => "\$GLOBALS['stub_wp_options']->use_smilies",
		"get_option( 'home' )"            => "\$GLOBALS['stub_wp_options']->home",
		"get_option( 'siteurl' )"         => "\$GLOBALS['stub_wp_options']->siteurl",
		"get_site_option( 'siteurl' )"    => "\$GLOBALS['stub_wp_options']->siteurl",
		"get_option( 'use_balanceTags' )" => "\$GLOBALS['stub_wp_options']->use_balanceTags",
		"get_option( 'WPLANG' )"          => "\$GLOBALS['stub_wp_options']->WPLANG",
		"get_site_option( 'WPLANG' )"     => "\$GLOBALS['stub_wp_options']->WPLANG",
	];

	public function __construct(
		private readonly string $wp_version
	){
	}

	public function replace_in_code( string $code_text ): string {
		$code_text = strtr( $code_text, self::$stub_wp_options );

		$code_text = str_replace( "get_bloginfo( 'version' )", "'$this->wp_version'", $code_text );

		// static class method replacement
		// TODO make it automatic from config for functions class like
		$code_text = str_replace( "WP_Http::make_absolute_url(", "WP_Http__make_absolute_url(", $code_text );

		return $code_text;
	}

}
