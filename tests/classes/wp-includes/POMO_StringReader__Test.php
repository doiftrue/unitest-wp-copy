<?php

class POMO_StringReader__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$reader = new POMO_StringReader( 'abcdef' );

		$this->assertSame( 'abc', $reader->read( 3 ) );
		$this->assertSame( 3, $reader->pos() );
		$this->assertSame( 1, $reader->seekto( 1 ) );
		$this->assertSame( 'bcdef', $reader->read_all() );
	}
}
