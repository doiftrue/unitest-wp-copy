<?php

class PasswordHash__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$hasher = new PasswordHash( 8, true );
		$hasher->PasswordHash( 8, true );

		$bytes = $hasher->get_random_bytes( 8 );
		$this->assertSame( 8, strlen( $bytes ) );

		$encoded = $hasher->encode64( str_repeat( 'A', 8 ), 6 );
		$this->assertNotEmpty( $encoded );

		$salt = $hasher->gensalt_private( $hasher->get_random_bytes( 6 ) );
		$this->assertStringStartsWith( '$P$', $salt );

		$hash = $hasher->HashPassword( 'secret-pass' );
		$this->assertNotSame( '*', $hash );
		$this->assertTrue( $hasher->CheckPassword( 'secret-pass', $hash ) );
		$this->assertFalse( $hasher->CheckPassword( 'wrong', $hash ) );

		$this->assertNotEmpty( $hasher->gensalt_blowfish( $hasher->get_random_bytes( 16 ) ) );
		$this->assertNotEmpty( $hasher->crypt_private( 'x', $salt ) );
	}
}

