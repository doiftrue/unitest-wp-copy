<?php

class WP_Block_Editor_Context__Test extends \PHPUnit\Framework\TestCase {

	public function test__construct() {
		$post = (object) [ 'ID' => 10 ];
		$context = new WP_Block_Editor_Context( [
			'name' => 'core/edit-site',
			'post' => $post,
		] );

		$this->assertSame( 'core/edit-site', $context->name );
		$this->assertSame( $post, $context->post );
	}
}
