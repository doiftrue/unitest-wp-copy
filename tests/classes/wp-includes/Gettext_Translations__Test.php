<?php

class Gettext_Translations__Test extends \PHPUnit\Framework\TestCase {

	public function test__plural_forms() {
		$translations = new Gettext_Translations();
		$translations->set_header( 'Plural-Forms', 'nplurals=2; plural=n != 1;' );

		$this->assertSame( 0, $translations->select_plural_form( 1 ) );
		$this->assertSame( 1, $translations->select_plural_form( 2 ) );
		$this->assertSame( 2, $translations->get_plural_forms_count() );
	}
}
