<?php

class widgets__Test extends \PHPUnit\Framework\TestCase {

	private array $registered_sidebars;
	private ?array $theme_features;

	protected function setUp(): void {
		parent::setUp();

		$this->registered_sidebars = $GLOBALS['wp_registered_sidebars'];
		$this->theme_features      = $GLOBALS['_wp_theme_features'] ?? null;
		$GLOBALS['wp_registered_sidebars'] = [];
	}

	protected function tearDown(): void {
		$GLOBALS['wp_registered_sidebars'] = $this->registered_sidebars;
		if ( null === $this->theme_features ) {
			unset( $GLOBALS['_wp_theme_features'] );
		} else {
			$GLOBALS['_wp_theme_features'] = $this->theme_features;
		}

		parent::tearDown();
	}

	public function test__register_sidebars() {
		register_sidebars( 2, [
			'id'   => 'unitest-sidebar',
			'name' => 'Unitest Sidebar %d',
		] );

		$this->assertSame(
			[ 'unitest-sidebar', 'unitest-sidebar-2' ],
			array_keys( $GLOBALS['wp_registered_sidebars'] )
		);
	}

	public function test__register_sidebar() {
		$this->assertSame(
			'unitest-sidebar',
			register_sidebar( [
				'id'   => 'unitest-sidebar',
				'name' => 'Unitest Sidebar',
			] )
		);
		$this->assertSame( 'Unitest Sidebar', $GLOBALS['wp_registered_sidebars']['unitest-sidebar']['name'] );
	}

	public function test__unregister_sidebar() {
		$GLOBALS['wp_registered_sidebars']['unitest-sidebar'] = [ 'id' => 'unitest-sidebar' ];

		unregister_sidebar( 'unitest-sidebar' );

		$this->assertArrayNotHasKey( 'unitest-sidebar', $GLOBALS['wp_registered_sidebars'] );
	}

	public function test___get_widget_id_base() {
		$this->assertSame( 'text', _get_widget_id_base( 'text-12' ) );
		$this->assertSame( 'text-custom', _get_widget_id_base( 'text-custom' ) );
	}

	public function test__wp_parse_widget_id() {
		$this->assertSame( [ 'id_base' => 'text', 'number' => 12 ], wp_parse_widget_id( 'text-12' ) );
		$this->assertSame( [ 'id_base' => 'legacy' ], wp_parse_widget_id( 'legacy' ) );
	}
}
