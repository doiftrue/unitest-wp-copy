<?php

class WP_Style_Engine_CSS_Declarations__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$declarations = new WP_Style_Engine_CSS_Declarations( [ 'color' => 'red' ] );
		$declarations->add_declaration( 'margin-top', '1rem' );

		$this->assertSame( [ 'color' => 'red', 'margin-top' => '1rem' ], $declarations->get_declarations() );
		$this->assertSame( 'color:red;margin-top:1rem;', $declarations->get_declarations_string() );
	}
}
