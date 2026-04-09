<?php

class WP_Mock_Utils {

	/**
	 * Checks whether WP_Mock has a registered handler for the specified function.
	 */
	public static function has_handler( string $func_name ): bool {
		$class = self::get_handler_class();
		if( $class ){
			return self::is_new()
				? $class::handlerExists( $func_name )
				: $class::handler_exists( $func_name );
		}

		return false;
	}

	/**
	 * Calls WP_Mock predefined return handler for a function.
	 * Should only be used if WP_Mock_Utils::has_handler() returns true.
	 */
	public static function call( string $func_name, array $args ): mixed {
		$class = self::get_handler_class();

		return self::is_new()
			? $class::handleFunction( $func_name, $args )
			: $class::handle_function( $func_name, $args );
	}

	/**
	 * Calls WP_Mock predefined echo handler for a function.
	 * Should only be used if WP_Mock_Utils::has_handler() returns true.
	 */
	public static function echo_call( string $func_name, array $args ): void {
		$class = self::get_handler_class();

		self::is_new()
			? $class::handlePredefinedEchoFunction( $func_name, $args )
			: $class::predefined_echo_function_helper( $func_name, $args );
	}

	private static function get_handler_class(): string {
		$class = \WP_Mock\Functions\Handler::class; // v1.1
		if( class_exists( $class ) ){
			return $class;
		}

		$class = \WP_Mock\Handler::class; // v0.4
		if( class_exists( $class ) ){
			return $class;
		}

		return '';
	}

	private static function is_new(): bool {
		return str_contains( self::get_handler_class(), '\\Functions\\' );
	}

}
