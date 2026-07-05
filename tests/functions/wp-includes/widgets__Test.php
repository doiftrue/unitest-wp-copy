<?php

class widgets__Test extends \PHPUnit\Framework\TestCase {

	public function test___get_widget_id_base() {
		$this->assertSame( 'text', _get_widget_id_base( 'text-12' ) );
		$this->assertSame( 'text-custom', _get_widget_id_base( 'text-custom' ) );
	}

	public function test__wp_parse_widget_id() {
		$this->assertSame( [ 'id_base' => 'text', 'number' => 12 ], wp_parse_widget_id( 'text-12' ) );
		$this->assertSame( [ 'id_base' => 'legacy' ], wp_parse_widget_id( 'legacy' ) );
	}
}
