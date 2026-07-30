<?php

class Plural_Forms__Test extends \PHPUnit\Framework\TestCase {

	public function test__get() {
		$forms = new Plural_Forms( 'n != 1' );

		$this->assertSame( 0, $forms->get( 1 ) );
		$this->assertSame( 1, $forms->get( 2 ) );
	}
}
