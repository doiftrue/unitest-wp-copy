<?php

class bookmark__Test extends \PHPUnit\Framework\TestCase {
	public function test__sanitize_bookmark() {
		$result = sanitize_bookmark( [ 'link_id' => '7', 'link_name' => 'Name' ], 'raw' );
		$this->assertSame( 7, $result['link_id'] );
	}

	public function test__sanitize_bookmark_field() {
		$this->assertSame( 7, sanitize_bookmark_field( 'link_id', '7', 7, 'raw' ) );
		$this->assertSame( '', sanitize_bookmark_field( 'link_target', 'bad', 7, 'raw' ) );
	}
}
