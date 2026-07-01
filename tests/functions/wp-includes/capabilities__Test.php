<?php

class capabilities__Test extends \PHPUnit\Framework\TestCase {

	public function test__wp_maybe_grant_install_languages_cap() {
		foreach ( [ 'update_core', 'install_plugins', 'install_themes' ] as $capability ) {
			$result = wp_maybe_grant_install_languages_cap( [ $capability => true ] );
			$this->assertTrue( $result['install_languages'] );
		}

		$result = wp_maybe_grant_install_languages_cap( [ 'read' => true, 'edit_posts' => true ] );
		$this->assertArrayNotHasKey( 'install_languages', $result );
	}

	public function test__wp_maybe_grant_resume_extensions_caps() {
		$result = wp_maybe_grant_resume_extensions_caps( [ 'activate_plugins' => true ] );
		$this->assertTrue( $result['resume_plugins'] );
		$this->assertArrayNotHasKey( 'resume_themes', $result );

		$result = wp_maybe_grant_resume_extensions_caps( [ 'switch_themes' => true ] );
		$this->assertTrue( $result['resume_themes'] );
		$this->assertArrayNotHasKey( 'resume_plugins', $result );

		$result = wp_maybe_grant_resume_extensions_caps( [
			'activate_plugins' => true,
			'switch_themes'    => true,
		] );
		$this->assertTrue( $result['resume_plugins'] );
		$this->assertTrue( $result['resume_themes'] );

		$result = wp_maybe_grant_resume_extensions_caps( [ 'read' => true ] );
		$this->assertArrayNotHasKey( 'resume_plugins', $result );
		$this->assertArrayNotHasKey( 'resume_themes', $result );
	}

}
