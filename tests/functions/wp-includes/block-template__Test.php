<?php

class block_template__Test extends \PHPUnit\Framework\TestCase {

	public function test___block_template_add_skip_link() {
		if( $wp_ver = wp_version_compare( '< 7.0.0' ) ){
			$this->markTestSkipped( "_block_template_add_skip_link() not exists on WP $wp_ver" );
		}

		$html = '<div class="wp-site-blocks"><main>Content</main></div>';
		$result = _block_template_add_skip_link( $html );
		$this->assertStringContainsString( 'id="wp-skip-link"', $result );
		$this->assertStringContainsString( '<main id="wp--skip-link--target">', $result );
		$this->assertSame( '<main>Content</main>', _block_template_add_skip_link( '<main>Content</main>' ) );
	}

	public function test___block_template_viewport_meta_tag() {
		ob_start();
		_block_template_viewport_meta_tag();
		$this->assertSame( "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />\n", ob_get_clean() );
	}

	public function test___strip_template_file_suffix() {
		$this->assertSame( 'single', _strip_template_file_suffix( 'single.html' ) );
		$this->assertSame( 'page', _strip_template_file_suffix( 'page.php' ) );
		$this->assertSame( 'archive.json', _strip_template_file_suffix( 'archive.json' ) );
	}

	public function test___block_template_render_without_post_block_context() {
		$this->assertSame( [ 'foo' => 'bar' ], _block_template_render_without_post_block_context( [
			'postId' => 7, 'postType' => 'wp_template', 'foo' => 'bar',
		] ) );
		$this->assertSame( [ 'postId' => 7, 'postType' => 'post' ], _block_template_render_without_post_block_context( [
			'postId' => 7, 'postType' => 'post',
		] ) );
	}
}
