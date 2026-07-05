<?php

class load__custom_mocks__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_load_translations_early() {
		$this->assertNull( wp_load_translations_early() );
	}
}
