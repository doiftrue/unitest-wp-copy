<?php

use Parser\Config;
use PHPUnit\Framework\TestCase;

abstract class Parser_TestCase extends TestCase {

	private array $tmp_dirs = [];

	public static function setUpBeforeClass(): void {
		require_once PROJECT_ROOT_DIR . '/parser/autoload.php';
	}

	protected function tearDown(): void {
		foreach( $this->tmp_dirs as $dir ){
			$this->remove_dir( $dir );
		}

		$this->tmp_dirs = [];
	}

	protected function make_config( array $props ): Config {
		$config = new ReflectionClass( Config::class )->newInstanceWithoutConstructor();

		Closure::bind(
			function() use ( $props ) {
				foreach( $props as $name => $value ){
					$this->$name = $value;
				}
			},
			$config,
			Config::class
		)();

		return $config;
	}

	protected function make_temp_dir( string $prefix = 'parser-test' ): string {
		$dir = PROJECT_TMP_DIR . '/' . $prefix . '-' . uniqid( '', true );
		mkdir( $dir, 0777, true );
		$this->tmp_dirs[] = $dir;

		return $dir;
	}

	private function remove_dir( string $dir ): void {
		if( ! is_dir( $dir ) ){
			return;
		}

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach( $iterator as $item ){
			if( $item->isDir() ){
				rmdir( $item->getPathname() );
				continue;
			}

			unlink( $item->getPathname() );
		}

		rmdir( $dir );
	}

}
