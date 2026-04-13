<?php

class taxonomy__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_taxonomies'] = [];
	}

	public function test__taxonomy_exists() {
		$this->assertFalse( taxonomy_exists( 'genre' ) );

		$GLOBALS['wp_taxonomies']['genre'] = (object) [ 'name' => 'genre' ];

		$this->assertTrue( taxonomy_exists( 'genre' ) );
	}
}
