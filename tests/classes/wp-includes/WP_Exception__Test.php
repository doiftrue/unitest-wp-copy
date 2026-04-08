<?php

class WP_Exception__Test extends \PHPUnit\Framework\TestCase {

	public function test__construct() {
		$ex = new WP_Exception( 'boom' );

		$this->assertInstanceOf( Exception::class, $ex );
		$this->assertSame( 'boom', $ex->getMessage() );
	}
}

