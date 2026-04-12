<?php

class theme_Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_wp_theme_features'] = [];
	}

	public function test__current_theme_supports() {
		$this->assertFalse( current_theme_supports( 'html5', 'script' ) );

		$GLOBALS['_wp_theme_features'] = [
			'html5' => [ [ 'script', 'style' ] ],
		];

		$this->assertTrue( current_theme_supports( 'html5', 'script' ) );
		$this->assertFalse( current_theme_supports( 'html5', 'comment-form' ) );
	}

}
