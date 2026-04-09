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
		private readonly Config $config,
	){
	}

	public function replace_in_code( string $code_text ): string {
		$code_text = strtr( $code_text, self::$stub_wp_options );

		// Replace `Class::method( >>> Class__method(` in code functions body.
		$code_text = strtr( $code_text, $this->build_static_method_replace_array() );

		return $code_text;
	}

	private function build_static_method_replace_array(): array {
		$replace = [];

		foreach( $this->config->static_methods_data as $config ){
			$class_name = $config['class'] ?? '';
			$method_names = $config['methods'] ?? [];
			if( ! $class_name || ! $method_names ){
				continue;
			}

			foreach( array_keys( $method_names ) as $method_name ){
				$replace[ "$class_name::$method_name(" ] = "{$class_name}__$method_name(";
			}
		}

		return $replace;
	}

}
