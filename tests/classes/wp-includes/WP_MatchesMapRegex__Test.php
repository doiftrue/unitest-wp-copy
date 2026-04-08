<?php

class WP_MatchesMapRegex__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$obj = new WP_MatchesMapRegex( 'a=$matches[1]', [ 1 => 'x y' ] );
		$this->assertSame( 'a=x+y', $obj->output );

		$out = WP_MatchesMapRegex::apply( 'a=$matches[1]&b=$matches[2]', [ 1 => 'x y', 2 => 'z' ] );
		$this->assertSame( 'a=x+y&b=z', $out );
		$this->assertSame( 'x+y', $obj->callback( [ '$matches[1]' ] ) );
	}
}

