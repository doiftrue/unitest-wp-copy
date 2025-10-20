<?php

use PHPUnit\Framework\TestCase;

class compat_Test extends TestCase {

	public function test___() {
		$this->assertSame( 'x', _( 'x' ) );
	}

	public function test___wp_can_use_pcre_u() {
		_wp_can_use_pcre_u( 1 );
		$this->assertSame( 1, _wp_can_use_pcre_u() );

		_wp_can_use_pcre_u( 0 );
		$this->assertSame( 0, _wp_can_use_pcre_u() );
	}

	public function test___is_utf8_charset() {
		$this->assertTrue( _is_utf8_charset( 'UTF-8' ) );
		$this->assertTrue( _is_utf8_charset( 'utf8' ) );
		$this->assertFalse( _is_utf8_charset( 'latin1' ) );
		$this->assertFalse( _is_utf8_charset( null ) );
	}

	public function test__mb_substr() {
		$this->assertSame( 'Пр', _mb_substr( 'Привет', 0, 2, 'UTF-8' ) );
		$this->assertSame( 'ab', _mb_substr( 'abcd', 0, 2, 'latin1' ) );
		$this->assertSame( '', _mb_substr( null, 0, 2, 'UTF-8' ) );
	}

	public function test__mb_strlen() {
		$this->assertSame( 6, _mb_strlen( 'Привет', 'UTF-8' ) );
		$this->assertSame( 4, _mb_strlen( 'test', 'latin1' ) );
	}

	public function test__mb_substr_wrapper() {
		$this->assertSame( 'lo', mb_substr( 'hello', 3, 2, 'UTF-8' ) );
	}

	public function test__mb_strlen_wrapper() {
		$this->assertSame( 5, mb_strlen( 'hello', 'UTF-8' ) );
	}

	public function test__is_countable() {
		$this->assertTrue( is_countable( [ 1, 2 ] ) );
		$this->assertTrue( is_countable( new ArrayObject( [ 1 ] ) ) );
		$this->assertTrue( is_countable( new SimpleXMLElement( '<a/>' ) ) );
		$this->assertFalse( is_countable( 'x' ) );
	}

	public function test__array_key_first() {
		$this->assertSame( 'a', array_key_first( [ 'a' => 1, 'b' => 2 ] ) );
		$this->assertNull( array_key_first( [] ) );
	}

	public function test__array_key_last() {
		$this->assertSame( 'b', array_key_last( [ 'a' => 1, 'b' => 2 ] ) );
		$this->assertNull( array_key_last( [] ) );
	}

	public function test__array_is_list() {
		$this->assertTrue( array_is_list( [ 'a', 'b', 'c' ] ) );
		$this->assertFalse( array_is_list( [ 1 => 'a', 2 => 'b' ] ) );
	}

	public function test__str_contains() {
		$this->assertTrue( str_contains( 'abc', '' ) );
		$this->assertTrue( str_contains( 'abc', 'b' ) );
		$this->assertFalse( str_contains( 'abc', 'x' ) );
	}

	public function test__str_starts_with() {
		$this->assertTrue( str_starts_with( 'abc', '' ) );
		$this->assertTrue( str_starts_with( 'abc', 'ab' ) );
		$this->assertFalse( str_starts_with( 'abc', 'bc' ) );
	}

	public function test__str_ends_with() {
		$this->assertTrue( str_ends_with( '', '' ) );
		$this->assertTrue( str_ends_with( 'abc', 'bc' ) );
		$this->assertFalse( str_ends_with( 'abc', 'ab' ) );
	}

	public function test__array_find() {
		$a = [ 1, 2, 3 ];
		$this->assertSame( 2, array_find( $a, static function( $v ) { return 2 === $v; } ) );
		$this->assertNull( array_find( $a, static function( $v ) { return 9 === $v; } ) );
	}

	public function test__array_find_key() {
		$a = [ 'x' => 10, 'y' => 20 ];
		$this->assertSame( 'y', array_find_key( $a, static function( $v ) { return 20 === $v; } ) );
		$this->assertNull( array_find_key( $a, static function( $v ) { return 5 === $v; } ) );
	}

	public function test__array_any() {
		$a = [ 1, 2, 3 ];
		$this->assertTrue( array_any( $a, static function( $v ) { return $v > 2; } ) );
		$this->assertFalse( array_any( $a, static function( $v ) { return $v > 5; } ) );
	}

	public function test__array_all() {
		$a = [ 2, 4, 6 ];
		$this->assertTrue( array_all( $a, static function( $v ) { return 0 === ( $v % 2 ); } ) );
		$this->assertFalse( array_all( $a, static function( $v ) { return $v > 4; } ) );
	}

}
