<?php

class WP_HTML_Processor_State__Test extends \PHPUnit\Framework\TestCase {

	public function test__construct() {
		$state = new WP_HTML_Processor_State();

		$this->assertInstanceOf( WP_HTML_Open_Elements::class, $state->stack_of_open_elements );
		$this->assertInstanceOf( WP_HTML_Active_Formatting_Elements::class, $state->active_formatting_elements );
	}
}
