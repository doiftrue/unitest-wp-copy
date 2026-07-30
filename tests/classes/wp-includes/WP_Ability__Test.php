<?php

class WP_Ability__Test extends \PHPUnit\Framework\TestCase {

	public function test__execute() {
		if( $wp_ver = wp_version_compare( '< 6.9.0' ) ){
			$this->markTestSkipped( "WP_Ability not exists on WP $wp_ver" );
		}

		$ability = new WP_Ability( 'test/run', [
			'label'               => 'Run',
			'description'         => 'Runs a deterministic callback.',
			'category'            => 'test',
			'execute_callback'    => static fn() => 'done',
			'permission_callback' => static fn() => true,
		] );

		$this->assertSame( 'test/run', $ability->get_name() );
		$this->assertSame( 'done', $ability->execute() );
	}
}
