<?php

use PHPUnit\Framework\TestCase;

class l10n__Test extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		unset( $GLOBALS['locale'] );
	}

	public function test__get_locale() {
		$this->assertSame( 'en_US', get_locale() );
	}

	public function test__before_last_bar() {
		$this->assertSame( 'a|b', before_last_bar( 'a|b|c' ) );
		$this->assertSame( 'abc', before_last_bar( 'abc' ) );
	}

	public function test___n_noop() {
		$out = _n_noop( 'one', 'many', 'd' );
		$this->assertSame( 'one', $out['singular'] );
		$this->assertSame( 'many', $out['plural'] );
	}

	public function test___nx_noop() {
		$out = _nx_noop( 'one', 'many', 'ctx', 'd' );
		$this->assertSame( 'ctx', $out['context'] );
	}

	public function test__translate_nooped_plural() {
		$nooped = _n_noop( 'one', 'many', null );
		$this->assertSame( 'many', translate_nooped_plural( $nooped, 2 ) );
	}

	public function test__is_rtl() {
		$this->assertFalse( is_rtl() );
	}

	public function test__wp_get_list_item_separator() {
		$this->assertSame( ', ', wp_get_list_item_separator() );
	}

	public function test__wp_get_word_count_type() {
		$this->assertSame( 'words', wp_get_word_count_type() );
	}

	public function test____() {
		$this->assertSame( 'abc', __( 'abc' ) );
	}

	public function test___e() {
		ob_start();
		_e( 'abc' );
		$out = ob_get_clean();

		$this->assertSame( 'abc', $out );
	}

	public function test___x() {
		$this->assertSame( 'abc', _x( 'abc', 'ctx' ) );
	}

	public function test___n() {
		$this->assertSame( 'one', _n( 'one', 'many', 1 ) );
		$this->assertSame( 'many', _n( 'one', 'many', 2 ) );
	}

	public function test___nx() {
		$this->assertSame( 'one', _nx( 'one', 'many', 1, 'ctx' ) );
		$this->assertSame( 'many', _nx( 'one', 'many', 2, 'ctx' ) );
	}

	public function test__esc_html__() {
		$this->assertSame( 'abc', esc_html__( 'abc' ) );
	}

	public function test__esc_html_e() {
		ob_start();
		esc_html_e( 'abc' );
		$out = ob_get_clean();

		$this->assertSame( 'abc', $out );
	}

	public function test__esc_html_x() {
		$this->assertSame( 'abc', esc_html_x( 'abc', 'ctx' ) );
	}

	public function test__esc_attr__() {
		$this->assertSame( 'abc', esc_attr__( 'abc' ) );
	}

	public function test__esc_attr_e() {
		ob_start();
		esc_attr_e( 'abc' );
		$out = ob_get_clean();

		$this->assertSame( 'abc', $out );
	}

	public function test__esc_attr_x() {
		$this->assertSame( 'abc', esc_attr_x( 'abc', 'ctx' ) );
	}

}
