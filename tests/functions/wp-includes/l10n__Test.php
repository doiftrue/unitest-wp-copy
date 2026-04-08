<?php

class l10n__Test extends \PHPUnit\Framework\TestCase {

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

}
