<?php

class POMO_Reader__Test extends \PHPUnit\Framework\TestCase {

	public function test__integer_reading() {
		$reader = new class( pack( 'V', 42 ) ) extends POMO_Reader {
			private string $data;

			public function __construct( string $data ) {
				parent::__construct();
				$this->data = $data;
			}

			public function read( $bytes ) {
				return substr( $this->data, 0, $bytes );
			}
		};

		$this->assertSame( 42, $reader->readint32() );
	}
}
