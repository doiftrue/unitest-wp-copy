<?php

class WP_Style_Engine__Test extends \PHPUnit\Framework\TestCase {

	public function test__compile_css() {
		$this->assertSame(
			'.notice{color:red;}',
			WP_Style_Engine::compile_css( [ 'color' => 'red' ], '.notice' )
		);
	}
}
