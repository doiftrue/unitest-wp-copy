<?php

class post_formats_Test extends \PHPUnit\Framework\TestCase {

	public function test__get_post_format_strings() {
		$formats = get_post_format_strings();

		$this->assertArrayHasKey( 'standard', $formats );
		$this->assertArrayHasKey( 'audio', $formats );
	}

	public function test__get_post_format_slugs() {
		$slugs = get_post_format_slugs();

		$this->assertArrayHasKey( 'standard', $slugs );
		$this->assertSame( 'standard', $slugs['standard'] );
	}

}
