<?php

class WP_Style_Engine_CSS_Rule__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$rule = new WP_Style_Engine_CSS_Rule( '.notice', [ 'color' => 'red' ] );

		$this->assertSame( '.notice', $rule->get_selector() );
		$this->assertSame( '.notice{color:red;}', $rule->get_css() );
	}
}
