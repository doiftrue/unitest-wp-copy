<?php

use PHPUnit\Framework\TestCase;

class Walker_Nav_Menu__Test extends TestCase {

	public function test__not_independent_constructor_dependency() {
		$this->expectException( Error::class );
		new Walker_Nav_Menu();
	}
}

