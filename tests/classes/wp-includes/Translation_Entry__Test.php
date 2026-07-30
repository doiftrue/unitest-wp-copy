<?php

class Translation_Entry__Test extends \PHPUnit\Framework\TestCase {

	public function test__public_methods() {
		$entry = new Translation_Entry( [
			'singular'     => 'Save',
			'context'      => 'button',
			'translations' => [ 'Guardar' ],
		] );
		$other = new Translation_Entry( [
			'singular'   => 'Save',
			'context'    => 'button',
			'flags'      => [ 'php-format' ],
			'references' => [ 'file.php:10' ],
		] );

		$entry->merge_with( $other );

		$this->assertSame( "button\4Save", $entry->key() );
		$this->assertSame( [ 'php-format' ], $entry->flags );
		$this->assertSame( [ 'file.php:10' ], $entry->references );
	}
}
