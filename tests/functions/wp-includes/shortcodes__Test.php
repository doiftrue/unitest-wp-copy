<?php

class shortcodes__Test extends \PHPUnit\Framework\TestCase {

	private array $shortcode_tags = [];

	private array $wp_filter = [];

	private array $wp_filters = [];

	private array $wp_current_filter = [];

	protected function setUp(): void {
		parent::setUp();

		$this->shortcode_tags     = $GLOBALS['shortcode_tags'] ?? [];
		$this->wp_filter          = $GLOBALS['wp_filter'] ?? [];
		$this->wp_filters         = $GLOBALS['wp_filters'] ?? [];
		$this->wp_current_filter  = $GLOBALS['wp_current_filter'] ?? [];

		$GLOBALS['shortcode_tags']     = [];
		$GLOBALS['wp_filter']          = [];
		$GLOBALS['wp_filters']         = [];
		$GLOBALS['wp_current_filter']  = [];
	}

	protected function tearDown(): void {
		$GLOBALS['shortcode_tags']     = $this->shortcode_tags;
		$GLOBALS['wp_filter']          = $this->wp_filter;
		$GLOBALS['wp_filters']         = $this->wp_filters;
		$GLOBALS['wp_current_filter']  = $this->wp_current_filter;

		parent::tearDown();
	}

	public function test__add_shortcode(): void {
		add_shortcode( 'x', static fn() => 'ok' );

		$this->assertArrayHasKey( 'x', $GLOBALS['shortcode_tags'] );
		$this->assertIsCallable( $GLOBALS['shortcode_tags']['x'] );
	}

	public function test__remove_shortcode(): void {
		add_shortcode( 'x', static fn() => 'ok' );
		remove_shortcode( 'x' );

		$this->assertArrayNotHasKey( 'x', $GLOBALS['shortcode_tags'] );
	}

	public function test__remove_all_shortcodes(): void {
		add_shortcode( 'x', static fn() => 'ok' );
		remove_all_shortcodes();

		$this->assertSame( [], $GLOBALS['shortcode_tags'] );
	}

	public function test__shortcode_exists(): void {
		add_shortcode( 'x', static fn() => 'ok' );

		$this->assertTrue( shortcode_exists( 'x' ) );
		$this->assertFalse( shortcode_exists( 'missing' ) );
	}

	public function test__has_shortcode(): void {
		add_shortcode( 'x', static fn() => 'ok' );

		$this->assertTrue( has_shortcode( '[wrap][x][/wrap]', 'x' ) );
		$this->assertFalse( has_shortcode( 'plain text', 'x' ) );
	}

	public function test__get_shortcode_tags_in_content(): void {
		add_shortcode( 'x', static fn() => 'ok' );
		add_shortcode( 'wrap', static fn() => 'ok' );

		$this->assertSame( [ 'wrap', 'x' ], get_shortcode_tags_in_content( '[wrap][x][/wrap]' ) );
	}

	public function test__apply_shortcodes(): void {
		add_shortcode( 'x', static fn() => 'ok' );

		$this->assertSame( 'a ok b', apply_shortcodes( 'a [x] b' ) );
	}

	public function test__do_shortcode(): void {
		add_shortcode(
			'x',
			static fn( $atts, $content, $tag ) => strtoupper( $tag ) . ':' . $atts['a'] . ':' . $content
		);

		$this->assertSame( 'X:1:body', do_shortcode( '[x a="1"]body[/x]' ) );
	}

	public function test___filter_do_shortcode_context(): void {
		$this->assertSame( 'do_shortcode', _filter_do_shortcode_context() );
	}

	public function test__get_shortcode_regex(): void {
		add_shortcode( 'x', static fn() => 'ok' );

		$this->assertMatchesRegularExpression( '/' . get_shortcode_regex() . '/', '[x]' );
	}

	public function test__do_shortcode_tag(): void {
		add_shortcode( 'x', static fn( $atts ) => $atts['a'] );
		$match = [ '[x a="1"]', '', 'x', ' a="1"', '', '', '' ];

		$this->assertSame( '1', do_shortcode_tag( $match ) );
	}

	public function test__do_shortcodes_in_html_tags(): void {
		add_shortcode( 'x', static fn() => 'ok' );

		$this->assertSame(
			'<a title="ok">link</a>',
			do_shortcodes_in_html_tags( '<a title="[x]">link</a>', false, [ 'x' ] )
		);
	}

	public function test__unescape_invalid_shortcodes(): void {
		$this->assertSame( '[x]', unescape_invalid_shortcodes( '&#91;x&#93;' ) );
	}

	public function test__get_shortcode_atts_regex(): void {
		$this->assertIsString( get_shortcode_atts_regex() );
	}

	public function test__shortcode_parse_atts(): void {
		$this->assertSame(
			[ 'a' => '1', 'b' => 'two', 0 => 'loose' ],
			shortcode_parse_atts( 'A="1" b=two loose' )
		);
	}

	public function test__shortcode_atts(): void {
		add_filter( 'shortcode_atts_x', static fn( $out ) => array_merge( $out, [ 'filtered' => true ] ) );

		$this->assertSame(
			[ 'a' => 'custom', 'b' => 'default', 'filtered' => true ],
			shortcode_atts( [ 'a' => 'default', 'b' => 'default' ], [ 'a' => 'custom', 'c' => 'ignored' ], 'x' )
		);
	}

	public function test__strip_shortcodes(): void {
		add_shortcode( 'x', static fn() => 'ok' );

		$this->assertSame( 'a  b', strip_shortcodes( 'a [x]hidden[/x] b' ) );
	}

	public function test__strip_shortcode_tag(): void {
		$match = [ '[x]hidden[/x]', '', 'x', '', '', 'hidden', '' ];

		$this->assertSame( '', strip_shortcode_tag( $match ) );
	}

}
