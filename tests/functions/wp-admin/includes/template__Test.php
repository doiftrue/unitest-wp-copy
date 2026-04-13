<?php

class template__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wp_post_types'] = [];
		$GLOBALS['wp_taxonomies'] = [];
	}

	public function test__convert_to_screen() {
		$screen = convert_to_screen( 'template-screen' );

		$this->assertInstanceOf( WP_Screen::class, $screen );
		$this->assertSame( 'template-screen', $screen->id );
	}
}
