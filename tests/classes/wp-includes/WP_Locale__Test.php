<?php

class WP_Locale__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$locale = new WP_Locale();
		$locale->init();
		$locale->register_globals();

		$this->assertSame( 'Sunday', $locale->get_weekday( 0 ) );
		$this->assertNotEmpty( $locale->get_weekday_initial( 'Sunday' ) );
		$this->assertSame( 'Sun', $locale->get_weekday_abbrev( 'Sunday' ) );
		$this->assertSame( 'January', $locale->get_month( '01' ) );
		$this->assertNotEmpty( $locale->get_month_abbrev( 'January' ) );
		$this->assertSame( 'am', $locale->get_meridiem( 'am' ) );
		$this->assertFalse( $locale->is_rtl() );
		$this->assertNotEmpty( $locale->get_list_item_separator() );
		$this->assertSame( 'words', $locale->get_word_count_type() );

		wp_version_compare( '>= 6.8.0' )
			&& $this->assertSame( 'January', $locale->get_month_genitive( 1 ) );

		$locale->_strings_for_pot();
		$this->assertTrue( true );
	}
}

