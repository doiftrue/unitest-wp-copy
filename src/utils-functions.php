<?php

/**
 * Checks whether WP_Mock has a registered handler for the specified function.
 */
function wp_mock_has_handler( string $func_name ): bool {
	$class = 'WP_Mock\\Functions\\Handler';

	return class_exists( $class )
		&& is_callable( [ $class, 'handlerExists' ] )
		&& $class::handlerExists( $func_name );
}

/**
 * Calls WP_Mock predefined return handler for a function.
 * Should only be used if wp_mock_has_handler() returns true.
 */
function wp_mock_call( string $func_name, array $args ): mixed {
	return \WP_Mock\Functions\Handler::handlePredefinedReturnFunction( $func_name, $args );
}

/**
 * Calls WP_Mock predefined echo handler for a function.
 * Should only be used if wp_mock_has_handler() returns true.
 */
function wp_mock_echo_call( string $func_name, array $args ): void {
	\WP_Mock\Functions\Handler::handlePredefinedEchoFunction( $func_name, $args );
}
