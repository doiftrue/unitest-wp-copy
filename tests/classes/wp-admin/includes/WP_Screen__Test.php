<?php

class WP_Screen__Test extends \PHPUnit\Framework\TestCase {

	public static function render_columns_filter() {
		return [
			'title' => 'Title',
			'date' => 'Date',
		];
	}

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['current_screen'] = null;
		$GLOBALS['taxnow'] = null;
		$GLOBALS['typenow'] = null;
		$GLOBALS['wp_post_types'] = [];
		$GLOBALS['wp_taxonomies'] = [];
	}

	protected function tearDown(): void {
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
		$GLOBALS['wp_actions'] = [];

		parent::tearDown();
	}

	public function test__registry_and_flags() {
		$screen = WP_Screen::get( 'dashboard' );

		$this->assertSame( $screen, WP_Screen::get( 'dashboard' ) );
		$this->assertSame( 'dashboard', $screen->id );
		$this->assertSame( 'dashboard', $screen->base );
		$this->assertTrue( $screen->in_admin() );
		$this->assertTrue( $screen->in_admin( 'site' ) );
		$this->assertFalse( $screen->in_admin( 'network' ) );
		$this->assertFalse( $screen->is_block_editor() );

		$screen->is_block_editor( true );
		$this->assertTrue( $screen->is_block_editor() );
	}

	public function test__current_screen_and_options_api() {
		$screen = WP_Screen::get( 'settings' );
		$screen->set_parentage( 'options-general.php?page=test' );
		$screen->set_current_screen();
		$screen->add_option( 'per_page', [ 'default' => 15 ] );

		$this->assertSame( $screen, $GLOBALS['current_screen'] );
		$this->assertSame( 'options-general.php?page=test', $screen->parent_file );
		$this->assertSame( 'options-general', $screen->parent_base );
		$this->assertSame( [ 'default' => 15 ], $screen->get_option( 'per_page' ) );

		$screen->remove_option( 'per_page' );
		$this->assertNull( $screen->get_option( 'per_page' ) );

		$screen->add_option( 'layout_columns', [ 'max' => 2 ] );
		$screen->add_option( 'per_page', [ 'default' => 20 ] );
		$this->assertTrue( $screen->show_screen_options() );

		$screen->remove_options();
		$this->assertSame( [], $screen->get_options() );
	}

	public function test__help_and_screen_reader_api() {
		$screen = WP_Screen::get( 'help-screen' );

		$screen->add_help_tab( [
			'id' => 'tab-b',
			'title' => 'B',
			'content' => 'Second',
			'priority' => 20,
		] );
		$screen->add_help_tab( [
			'id' => 'tab-a',
			'title' => 'A',
			'content' => 'First',
			'priority' => 5,
		] );
		$screen->set_help_sidebar( 'Sidebar' );
		$screen->set_screen_reader_content( [ 'heading_list' => 'Custom list' ] );

		$this->assertSame( [ 'tab-a', 'tab-b' ], array_keys( $screen->get_help_tabs() ) );
		$this->assertSame( 'Sidebar', $screen->get_help_sidebar() );
		$this->assertSame( 'Custom list', $screen->get_screen_reader_text( 'heading_list' ) );
		$this->assertArrayHasKey( 'heading_views', $screen->get_screen_reader_content() );

		$screen->remove_help_tab( 'tab-a' );
		$this->assertNull( $screen->get_help_tab( 'tab-a' ) );

		$screen->remove_help_tabs();
		$this->assertSame( [], $screen->get_help_tabs() );

		$screen->remove_screen_reader_content();
		$this->assertSame( [], $screen->get_screen_reader_content() );
	}

	public function test__not_independent_render_list_table_columns_preferences() {
		$screen = WP_Screen::get( 'render-columns' );

		add_filter( 'manage_render-columns_columns', [ self::class, 'render_columns_filter' ] );

		$this->expectException( Error::class );
		$screen->render_list_table_columns_preferences();
	}
}
