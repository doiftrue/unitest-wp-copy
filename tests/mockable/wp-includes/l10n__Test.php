<?php

require_once dirname( __DIR__, 3 ) . '/vendor/autoload.php';

class l10n__mockable__Test extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();
	}

	protected function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
	}

	public function test__is_rtl(): void {
		$this->assertIsBool( is_rtl() );
	}

	public function test__is_rtl__mockable_handler(): void {
		\WP_Mock::userFunction( 'is_rtl', [ 'return' => true ] );
		$this->assertTrue( is_rtl() );
	}

	public function test__wp_get_list_item_separator(): void {
		$this->assertSame( ', ', wp_get_list_item_separator() );
	}

	public function test__wp_get_list_item_separator__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_get_list_item_separator', [ 'return' => '; ' ] );
		$this->assertSame( '; ', wp_get_list_item_separator() );
	}

	public function test__wp_get_word_count_type(): void {
		$this->assertSame( 'words', wp_get_word_count_type() );
	}

	public function test__wp_get_word_count_type__mockable_handler(): void {
		\WP_Mock::userFunction( 'wp_get_word_count_type', [ 'return' => 'characters_including_spaces' ] );
		$this->assertSame( 'characters_including_spaces', wp_get_word_count_type() );
	}

	public function test__determine_locale(): void {
		$this->assertSame( get_locale(), determine_locale() );

		$GLOBALS['pagenow'] = 'wp-login.php';
		$_GET['wp_lang']    = 'de_DE<script>';
		$this->assertSame( 'de_DEscript', determine_locale() );

		unset( $GLOBALS['pagenow'], $_GET['wp_lang'] );

		\WP_Mock::userFunction( 'is_admin', [ 'return' => true ] );
		\WP_Mock::userFunction( 'get_user_locale', [ 'return' => 'fr_FR' ] );
		$this->assertSame( 'fr_FR', determine_locale() );
	}

	public function test__determine_locale__mockable_handler(): void {
		\WP_Mock::userFunction( 'determine_locale', [ 'return' => 'uk' ] );
		$this->assertSame( 'uk', determine_locale() );
	}
}
