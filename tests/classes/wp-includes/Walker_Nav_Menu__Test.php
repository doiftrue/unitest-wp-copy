<?php

class Walker_Nav_Menu__Test extends \PHPUnit\Framework\TestCase {

	public function test__not_independent_constructor_dependency() {
		$this->expectException( Error::class );
		new Walker_Nav_Menu();
	}
}

