<?php

class style_engine__Test extends \PHPUnit\Framework\TestCase {

	public function test__style_engine_helpers(): void {
		$styles = wp_style_engine_get_styles( [ 'color' => [ 'text' => '#123456' ] ], [ 'selector' => '.unitest' ] );
		$this->assertStringContainsString( 'color:#123456', $styles['css'] );

		$stylesheet = wp_style_engine_get_stylesheet_from_css_rules( [ [
			'selector' => '.unitest',
			'declarations' => [ 'color' => '#123456' ],
		] ] );
		$this->assertStringContainsString( '.unitest', $stylesheet );
		$this->assertSame( '', wp_style_engine_get_stylesheet_from_context( 'missing-unitest-context' ) );
	}
}
