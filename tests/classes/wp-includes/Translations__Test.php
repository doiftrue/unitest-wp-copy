<?php

class Translations__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$translations = new Translations();
		$translations->add_entry( [
			'singular'     => 'Save',
			'translations' => [ 'Guardar' ],
		] );

		$this->assertSame( 'Guardar', $translations->translate( 'Save' ) );
		$this->assertSame( 'Missing', $translations->translate( 'Missing' ) );
		$this->assertSame( 'items', $translations->translate_plural( 'item', 'items', 2 ) );
	}
}
