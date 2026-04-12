<?php

class theme_Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_wp_theme_features'] = [];
		$GLOBALS['_wp_registered_theme_features'] = [];
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	public function test__current_theme_supports() {
		$this->assertFalse( current_theme_supports( 'html5', 'script' ) );

		$GLOBALS['_wp_theme_features'] = [
			'html5' => [ [ 'script', 'style' ] ],
		];

		$this->assertTrue( current_theme_supports( 'html5', 'script' ) );
		$this->assertFalse( current_theme_supports( 'html5', 'comment-form' ) );
	}

	public function test__add_theme_support() {
		add_theme_support( 'html5', [ 'script', 'style' ] );

		$this->assertSame(
			[ [ 'script', 'style' ] ],
			$GLOBALS['_wp_theme_features']['html5']
		);
	}

	public function test__get_theme_support() {
		add_theme_support( 'custom-logo', [ 'width' => 100 ] );

		$this->assertSame( 100, get_theme_support( 'custom-logo', 'width' ) );
	}

	public function test__remove_theme_support() {
		add_theme_support( 'html5', [ 'script' ] );

		$this->assertTrue( remove_theme_support( 'html5' ) );
		$this->assertFalse( get_theme_support( 'html5' ) );
	}

	public function test___remove_theme_support() {
		add_theme_support( 'html5', [ 'script' ] );

		$this->assertTrue( _remove_theme_support( 'html5' ) );
		$this->assertFalse( get_theme_support( 'html5' ) );
	}

	public function test__register_theme_feature() {
		$result = register_theme_feature( 'my-feature', [
			'type' => 'array',
			'show_in_rest' => [
				'schema' => [
					'items' => [ 'type' => 'string' ],
				],
			],
		] );

		$this->assertTrue( $result );
		$this->assertArrayHasKey( 'my-feature', $GLOBALS['_wp_registered_theme_features'] );
	}

	public function test__get_registered_theme_features() {
		register_theme_feature( 'feature-a', [ 'type' => 'boolean' ] );

		$features = get_registered_theme_features();
		$this->assertArrayHasKey( 'feature-a', $features );
	}

	public function test__get_registered_theme_feature() {
		register_theme_feature( 'feature-one', [ 'type' => 'boolean' ] );

		$this->assertSame(
			'boolean',
			get_registered_theme_feature( 'feature-one' )['type']
		);
	}

	public function test__create_initial_theme_features() {
		create_initial_theme_features();

		$this->assertArrayHasKey( 'title-tag', get_registered_theme_features() );
		$this->assertArrayHasKey( 'post-formats', get_registered_theme_features() );
	}

}
