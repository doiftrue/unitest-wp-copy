<?php
namespace Parser;

class Source_Code_Replacer {

	private static array $runtime_compat = [
		// Keep rest_is_ip_address() runtime-safe without Requests autoloader.
		'WpOrg\\Requests\\Ipv6::check_ipv6(' => 'WP_Http__is_ip_address(',
	];

	public function __construct(
		private readonly Config $config,
	){
	}

	public function replace_in_code( string $code_text ): string {
		$code_text = strtr( $code_text, self::$runtime_compat );

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
