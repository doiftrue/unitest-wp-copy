<?php

class rewrite__Test extends \PHPUnit\Framework\TestCase {

	public function test___wp_filter_taxonomy_base() {
		$this->assertSame( 'topics', _wp_filter_taxonomy_base( '/index.php/topics/' ) );
		$this->assertSame( 'topics', _wp_filter_taxonomy_base( '/topics/' ) );
		$this->assertSame( '', _wp_filter_taxonomy_base( '' ) );
	}
}
