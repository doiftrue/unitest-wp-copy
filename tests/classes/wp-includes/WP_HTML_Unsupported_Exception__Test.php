<?php

class WP_HTML_Unsupported_Exception__Test extends \PHPUnit\Framework\TestCase {

	public function test__construct() {
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
