<?php

use PHPUnit\Framework\TestCase;
use Parser\Config;

class Config__Test extends TestCase {

	public static function setUpBeforeClass(): void {
		require_once dirname( __DIR__, 2 ) . '/_parser/autoload.php';
	}

	public function test__apply_moves_config__moves_for_older_version() {
		$base_config = [
			'wp-includes/load.php'      => [
				'absint' => '2.5.0',
			],
			'wp-includes/functions.php' => [
				'path_is_absolute' => '2.5.0',
			],
		];

		$mv_config = [
			'absint' => [
				'moved_in' => '6.7',
				'from'    => 'wp-includes/functions.php',
				'to'      => 'wp-includes/load.php',
			],
		];

		$result = $this->call_apply_moves_config( $base_config, $mv_config, '6.6' );

		// should be moved
		$this->assertArrayNotHasKey( 'absint', $result['wp-includes/load.php'] );
		$this->assertSame( '2.5.0', $result['wp-includes/functions.php']['absint'] );
		$this->assertSame( '2.5.0', $result['wp-includes/functions.php']['path_is_absolute'] );
	}

	public function test__apply_moves_config__moves_for_newer_version() {
		$base_config = [
			'wp-includes/functions.php' => [
				'path_is_absolute' => '2.5.0',
			],
			'wp-includes/load.php'      => [
				'absint' => '2.5.0',
			],
		];

		$mv_config = [
			'absint' => [
				'moved_in' => '6.7',
				'from'    => 'wp-includes/functions.php',
				'to'      => 'wp-includes/load.php',
			],
		];

		$result = $this->call_apply_moves_config( $base_config, $mv_config, '6.8' );

		// should stay as it was
		$this->assertArrayNotHasKey( 'absint', $result['wp-includes/functions.php'] );
		$this->assertSame( '2.5.0', $result['wp-includes/load.php']['absint'] );
		$this->assertSame( '2.5.0', $result['wp-includes/functions.php']['path_is_absolute'] );
	}

	private function call_apply_moves_config( array $base_config, array $mv_config, string $wp_line ): array {
		return Closure::bind(
			fn() => $this->apply_moves_config( $base_config, $mv_config, $wp_line ),
			new ReflectionClass( Config::class )->newInstanceWithoutConstructor(),
			Config::class
		)();
	}

}
