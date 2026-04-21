<?php

class theme_Test extends \PHPUnit\Framework\TestCase {

	private array $tmp_dirs = [];

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_wp_theme_features'] = [];
		$GLOBALS['_wp_registered_theme_features'] = [];
		$GLOBALS['wp_filter'] = [];
		$GLOBALS['wp_actions'] = [];
		$GLOBALS['wp_filters'] = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	protected function tearDown(): void {
		foreach ( $this->tmp_dirs as $dir ) {
			if ( ! is_dir( $dir ) ) {
				continue;
			}

			$items = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ),
				RecursiveIteratorIterator::CHILD_FIRST
			);

			foreach ( $items as $item ) {
				if ( $item->isDir() ) {
					rmdir( $item->getPathname() );
					continue;
				}
				unlink( $item->getPathname() );
			}

			rmdir( $dir );
		}

		$this->tmp_dirs = [];

		parent::tearDown();
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

	public function test__get_stylesheet_and_get_template() {
		$GLOBALS['stub_wp_options']->stylesheet = 'child-theme';
		$GLOBALS['stub_wp_options']->template   = 'parent-theme';

		$this->assertSame( 'child-theme', get_stylesheet() );
		$this->assertSame( 'parent-theme', get_template() );
	}

	public function test__get_stylesheet_uri() {
		$GLOBALS['stub_wp_options']->stylesheet = 'child-theme';

		$this->assertStringContainsString( '/wp-content/themes/child-theme/style.css', get_stylesheet_uri() );
	}

	public function test__get_locale_stylesheet_uri() {
		$tmp_dir = sys_get_temp_dir() . '/unitest-wp-copy-theme-' . uniqid( '', true );
		mkdir( $tmp_dir, 0777, true );
		$this->tmp_dirs[] = $tmp_dir;

		file_put_contents( "$tmp_dir/en_US.css", '/* locale */' );

		add_filter( 'stylesheet_directory', static fn() => $tmp_dir );
		add_filter( 'stylesheet_directory_uri', static fn() => 'https://example.test/theme' );

		$this->assertSame( 'https://example.test/theme/en_US.css', get_locale_stylesheet_uri() );
	}

}
