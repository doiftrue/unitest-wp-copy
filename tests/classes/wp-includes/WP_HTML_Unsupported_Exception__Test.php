<?php

class WP_HTML_Unsupported_Exception__Test extends \PHPUnit\Framework\TestCase {

	public function test__construct() {
		if( wp_version_compare( '< 6.7.0' ) ){
			$exception = new WP_HTML_Unsupported_Exception( 'Unsupported' );
			$this->assertSame( 'Unsupported', $exception->getMessage() );
			return;
		}

		$exception = new WP_HTML_Unsupported_Exception(
			'Unsupported',
			'TABLE',
			12,
			'<table>',
			[ 'HTML', 'BODY' ],
			[ 'B' ]
		);

		$this->assertSame( 'Unsupported', $exception->getMessage() );
		$this->assertSame( 'TABLE', $exception->token_name );
		$this->assertSame( 12, $exception->token_at );
		$this->assertSame( [ 'HTML', 'BODY' ], $exception->stack_of_open_elements );
	}
}
