<?php

class WP_Font_Face__Test extends \PHPUnit\Framework\TestCase {

	public function test__generate_and_print() {
		ob_start();
		new WP_Font_Face()->generate_and_print( [
			[
				[
					'font-family'             => 'Open Sans',
					'font-weight'             => 700,
					'font-variation-settings' => [ '"wght"' => 700 ],
					'src'                     => [
						'https://example.com/font.ttf',
						'https://example.com/font.woff2',
					],
				],
			],
		] );
		$output = ob_get_clean();

		$style_attribute = wp_version_compare( '< 6.7.0' ) ? 'id' : 'class';
		$this->assertMatchesRegularExpression( "/<style {$style_attribute}=(['\"])wp-fonts-local\\1/", $output );
		$this->assertStringContainsString( 'font-family:"Open Sans"', $output );
		$this->assertStringContainsString(
			"src:url('https://example.com/font.woff2') format('woff2'), url('https://example.com/font.ttf') format('truetype')",
			$output
		);
		$this->assertStringContainsString( 'font-style:normal;font-weight:700;font-display:fallback', $output );
		$this->assertStringContainsString( 'font-variation-settings:"wght" 700', $output );

		ob_start();
		new WP_Font_Face()->generate_and_print( [] );
		$this->assertSame( '', ob_get_clean() );
	}
}
