<?php

class WP_HTML_Tag_Processor__Test extends \PHPUnit\Framework\TestCase {

	public function test__basic_html_flow() {
		$p = new WP_HTML_Tag_Processor( '<div class="a b">Hi</div>' );

		$this->assertTrue( $p->next_tag() );
		$this->assertSame( 'DIV', $p->get_tag() );
		$this->assertTrue( $p->has_class( 'a' ) );
		wp_version_compare( '>= 6.7' ) && $this->assertSame( 'html', $p->get_namespace() );

		$this->assertTrue( $p->set_bookmark( 'x' ) );
		$this->assertTrue( $p->has_bookmark( 'x' ) );
		$this->assertTrue( $p->seek( 'x' ) );
		$this->assertTrue( $p->release_bookmark( 'x' ) );

		$this->assertTrue( $p->add_class( 'c' ) );
		$this->assertTrue( $p->remove_class( 'a' ) );
		$this->assertTrue( $p->set_attribute( 'data-id', '42' ) );
		$this->assertTrue( $p->remove_attribute( 'class' ) );

		$updated = $p->get_updated_html();
		$this->assertStringNotContainsString( 'class=', $updated );
		$this->assertSame( $updated, (string) $p );
	}

	public function test__incomplete_token() {
		$p = new WP_HTML_Tag_Processor( '<div class="a"' );

		$this->assertFalse( $p->next_token() );
		$this->assertTrue( $p->paused_at_incomplete_token() );
	}
}
