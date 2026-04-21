<?php

class link_template__Test extends \PHPUnit\Framework\TestCase {

	private array $tmp_dirs = [];

	protected function setUp(): void {
		parent::setUp();
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

	public function test__home_url() {
		$this->assertSame( 'https://unitest-wp-copy.loc/a', home_url( 'a' ) );
	}

	public function test__get_home_url() {
		$this->assertSame( 'http://unitest-wp-copy.loc/a', get_home_url( null, 'a', 'http' ) );
	}

	public function test__site_url() {
		$this->assertSame( 'http://unitest-wp-copy.loc/a', site_url( 'a' ) );
	}

	public function test__get_site_url() {
		$this->assertSame( 'http://unitest-wp-copy.loc/a', get_site_url( null, 'a', 'http' ) );
	}

	public function test__includes_url() {
		$this->assertStringContainsString( '/wp-includes/x.js', includes_url( 'x.js' ) );
	}

	public function test__content_url() {
		$this->assertStringContainsString( '/wp-content/a.css', content_url( 'a.css' ) );
	}

	public function test__plugins_url() {
		$url = plugins_url( 'asset.js', '/path/to/wp/wp-content/plugins/my-plugin/main.php' );
		$this->assertStringContainsString( '/plugins/my-plugin/asset.js', $url );
	}

	public function test__set_url_scheme() {
		$this->assertSame( '/a', set_url_scheme( 'https://example.com/a', 'relative' ) );
		$this->assertSame( 'http://example.com/a', set_url_scheme( 'https://example.com/a', 'http' ) );
	}

	public function test__wp_internal_hosts() {
		$this->assertContains( 'unitest-wp-copy.loc', wp_internal_hosts() );
	}

	public function test__wp_is_internal_link() {
		$this->assertTrue( wp_is_internal_link( 'https://unitest-wp-copy.loc/a' ) );
		$this->assertFalse( wp_is_internal_link( 'https://example.com/a' ) );
	}

	public function test__get_parent_theme_file_uri() {
		$GLOBALS['stub_wp_options']->template = 'parent-theme';

		$this->assertStringContainsString(
			'/wp-content/themes/parent-theme/inc/config.php',
			get_parent_theme_file_uri( 'inc/config.php' )
		);
	}

	public function test__get_parent_theme_file_path() {
		$tmp_parent = $this->make_temp_dir();
		add_filter( 'template_directory', static fn() => $tmp_parent );

		$this->assertSame(
			"$tmp_parent/inc/config.php",
			get_parent_theme_file_path( 'inc/config.php' )
		);
	}

	public function test__get_theme_file_uri() {
		$tmp_child  = $this->make_temp_dir();
		$tmp_parent = $this->make_temp_dir();

		$child_asset = "$tmp_child/assets/app.js";
		mkdir( dirname( $child_asset ), 0777, true );
		file_put_contents( $child_asset, 'console.log("ok");' );

		add_filter( 'stylesheet_directory', static fn() => $tmp_child );
		add_filter( 'template_directory', static fn() => $tmp_parent );
		add_filter( 'stylesheet_directory_uri', static fn() => 'https://child.test/theme' );
		add_filter( 'template_directory_uri', static fn() => 'https://parent.test/theme' );

		$this->assertSame( 'https://child.test/theme/assets/app.js', get_theme_file_uri( 'assets/app.js' ) );

		unlink( $child_asset );
		$this->assertSame( 'https://parent.test/theme/assets/app.js', get_theme_file_uri( 'assets/app.js' ) );
	}

	public function test__get_theme_file_path() {
		$tmp_child  = $this->make_temp_dir();
		$tmp_parent = $this->make_temp_dir();

		$child_asset = "$tmp_child/assets/app.js";
		mkdir( dirname( $child_asset ), 0777, true );
		file_put_contents( $child_asset, 'console.log("ok");' );

		add_filter( 'stylesheet_directory', static fn() => $tmp_child );
		add_filter( 'template_directory', static fn() => $tmp_parent );

		$this->assertSame( $child_asset, get_theme_file_path( 'assets/app.js' ) );

		unlink( $child_asset );
		$this->assertSame( "$tmp_parent/assets/app.js", get_theme_file_path( 'assets/app.js' ) );
	}

	private function make_temp_dir(): string {
		$dir = sys_get_temp_dir() . '/unitest-wp-copy-link-template-' . uniqid( '', true );
		mkdir( $dir, 0777, true );
		$this->tmp_dirs[] = $dir;
		return $dir;
	}

}
