<?php

class post__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_post_types'] = [];
	}

	public function test__get_post_type_object() {
		$object = (object) [ 'name' => 'book' ];
		$GLOBALS['wp_post_types']['book'] = $object;

		$this->assertSame( $object, get_post_type_object( 'book' ) );
		$this->assertNull( get_post_type_object( 'missing' ) );
	}

	public function test__post_type_exists() {
		$this->assertFalse( post_type_exists( 'book' ) );

		$GLOBALS['wp_post_types']['book'] = (object) [ 'name' => 'book' ];

		$this->assertTrue( post_type_exists( 'book' ) );
	}
}
