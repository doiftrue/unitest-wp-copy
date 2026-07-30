<?php

class feed__Test extends \PHPUnit\Framework\TestCase {

	private object $initial_stub_wp_options;

	protected function setUp(): void {
		parent::setUp();

		$this->initial_stub_wp_options = clone $GLOBALS['stub_wp_options'];
		$GLOBALS['wp_filter']         = [];
		$GLOBALS['wp_actions']        = [];
		$GLOBALS['wp_filters']        = [];
		$GLOBALS['wp_current_filter'] = [];
	}

	protected function tearDown(): void {
		$GLOBALS['stub_wp_options'] = clone $this->initial_stub_wp_options;

		parent::tearDown();
	}

	public function test__prep_atom_text_construct() {
		$this->assertSame( [ 'text', 'plain text' ], prep_atom_text_construct( 'plain text' ) );
		$this->assertSame(
			[ 'xhtml', "<div xmlns='http://www.w3.org/1999/xhtml'><strong>text</strong></div>" ],
			prep_atom_text_construct( '<strong>text</strong>' )
		);
		$this->assertSame( [ 'html', '<![CDATA[<broken>]]>' ], prep_atom_text_construct( '<broken>' ) );
	}

	public function test__feed_content_type() {
		$this->assertSame( 'application/rss+xml', feed_content_type( 'rss2' ) );
		$this->assertSame( 'application/atom+xml', feed_content_type( 'atom' ) );
		$this->assertSame( 'application/octet-stream', feed_content_type( 'unknown' ) );
	}

	public function test__get_default_feed() {
		$this->assertSame( 'rss2', get_default_feed() );

		add_filter( 'default_feed', static fn() => 'atom' );
		$this->assertSame( 'atom', get_default_feed() );
	}

	public function test__html_type_rss() {
		$GLOBALS['stub_wp_options']->html_type = 'application/xhtml+xml';

		ob_start();
		html_type_rss();
		$output = ob_get_clean();

		$this->assertSame( 'xhtml', $output );
	}

	public function test__get_bloginfo_rss() {
		$GLOBALS['stub_wp_options']->blogname        = '<strong>Site &amp; Feed</strong>';
		$GLOBALS['stub_wp_options']->blogdescription = 'News &amp; Updates';

		$this->assertSame( 'Site &amp; Feed', get_bloginfo_rss() );
		$this->assertSame( 'News &amp; Updates', get_bloginfo_rss( 'description' ) );

		$cb = static function ( $value, $show ) {
			return ( 'description' === $show ) ? "$value filtered" : $value;
		};
		add_filter( 'get_bloginfo_rss', $cb, 10, 2 );

		$this->assertSame( 'News &amp; Updates filtered', get_bloginfo_rss( 'description' ) );
	}

	public function test__bloginfo_rss() {
		$GLOBALS['stub_wp_options']->blogname = '<em>Site &amp; Feed</em>';

		$cb = static function ( $value, $show ) {
			return ( '' === $show ) ? "$value displayed" : $value;
		};
		add_filter( 'bloginfo_rss', $cb, 10, 2 );

		ob_start();
		bloginfo_rss();
		$out = ob_get_clean();

		$this->assertSame( 'Site &amp; Feed displayed', $out );
	}
}
