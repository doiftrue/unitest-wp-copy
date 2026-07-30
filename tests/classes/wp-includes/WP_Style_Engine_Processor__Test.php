<?php

class WP_Style_Engine_Processor__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$processor = new WP_Style_Engine_Processor();
		$processor->add_rules( new WP_Style_Engine_CSS_Rule( '.notice', [ 'color' => 'red' ] ) );

		$this->assertSame( '.notice{color:red;}', $processor->get_css() );
	}
}
