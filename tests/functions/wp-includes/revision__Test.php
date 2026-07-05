<?php

class revision__Test extends \PHPUnit\Framework\TestCase {
	public function test___wp_get_post_revision_version() {
		$this->assertSame( 3, _wp_get_post_revision_version( [ 'post_name' => '123-revision-v3' ] ) );
		$this->assertSame( 2, _wp_get_post_revision_version( (object) [ 'post_name' => '123-autosave-v2' ] ) );
		$this->assertSame( 0, _wp_get_post_revision_version( [ 'post_name' => 'invalid' ] ) );
		$this->assertFalse( _wp_get_post_revision_version( 'invalid' ) );
	}
}
