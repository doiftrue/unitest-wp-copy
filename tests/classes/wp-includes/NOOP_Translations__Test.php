<?php

class NOOP_Translations__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$translations = new NOOP_Translations();

		$this->assertSame( 'Save', $translations->translate( 'Save' ) );
		$this->assertSame( 'items', $translations->translate_plural( 'item', 'items', 2 ) );
		$this->assertFalse( $translations->get_header( 'Language' ) );
	}
}
