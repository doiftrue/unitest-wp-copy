<?php

use PHPUnit\Framework\TestCase;

class compat_Test extends TestCase {

	public function test__returns_true_for_utf8() {
		$this->assertTrue( _is_utf8_charset( 'UTF-8' ) );
		$this->assertTrue( _is_utf8_charset( 'utf-8' ) );
		$this->assertTrue( _is_utf8_charset( 'UTF8' ) );
		$this->assertTrue( _is_utf8_charset( 'utf8' ) );

		$this->assertFalse( _is_utf8_charset( 'ISO-8859-1' ) );
		$this->assertFalse( _is_utf8_charset( '' ) );
		$this->assertFalse( _is_utf8_charset( null ) );
		$this->assertFalse( _is_utf8_charset( [] ) );
	}

}
