<?php
namespace Parser\ExtraStrats;

use Parser\Config;
use RuntimeException;

abstract class Files_Copy_Strategy {

	protected readonly string $wp_line_dir;

	protected readonly string $init_parts_dir;

	public function __construct(
		protected readonly Config $config,
	){
		$this->wp_line_dir = sprintf( '%s/wp-line-extra/%s', $this->config->runtime_dir, $this->config->wp_version_line );
		$this->init_parts_dir = "$this->wp_line_dir/init-parts";
	}

	public function run(): void {
		foreach( $this->files_map as $src_file => $dest_rel_file ){
			$dest_file = $this->get_dest_file( $dest_rel_file );
			$content = $this->get_content( $src_file );

			$this->write_file( $dest_file, $content );
			echo $this->get_log_message( $dest_rel_file ) . "\n";
		}
	}

	abstract protected function get_content( string $rel_file ): string;

	abstract protected function get_log_message( string $rel_file ): string;

	abstract protected function get_dest_file( string $rel_file ): string;

	private function write_file( string $file_path, string $content ): void {
		$dest_dir = dirname( $file_path );
		is_dir( $dest_dir ) || mkdir( $dest_dir, 0777, true );

		file_put_contents( $file_path, $content );
	}

}
