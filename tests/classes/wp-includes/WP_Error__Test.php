<?php

use PHPUnit\Framework\TestCase;

class WP_Error__Test extends TestCase {

	public function test__public_methods() {
		$err = new WP_Error( 'e_code', 'E message', [ 'x' => 1 ] );

		$this->assertTrue( $err->has_errors() );
		$this->assertSame( [ 'e_code' ], $err->get_error_codes() );
		$this->assertSame( 'e_code', $err->get_error_code() );
		$this->assertSame( [ 'E message' ], $err->get_error_messages( 'e_code' ) );
		$this->assertSame( 'E message', $err->get_error_message() );
		$this->assertSame( [ 'x' => 1 ], $err->get_error_data( 'e_code' ) );

		$err->add_data( [ 'y' => 2 ], 'e_code' );
		$this->assertCount( 2, $err->get_all_error_data( 'e_code' ) );

		$to = new WP_Error();
		$to->merge_from( $err );
		$this->assertTrue( $to->has_errors() );

		$to2 = new WP_Error();
		$err->export_to( $to2 );
		$this->assertSame( 'e_code', $to2->get_error_code() );

		$err->remove( 'e_code' );
		$this->assertFalse( $err->has_errors() );
	}
}

