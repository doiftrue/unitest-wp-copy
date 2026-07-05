<?php
namespace Parser\BaseStrats;

use Parser\Config;
use Parser\Symbols_Lister;

abstract class Symbols_Copy_Strategy {

	public function __construct(
		protected readonly Config $config,
		protected readonly Symbols_Lister $lister,
	){
	}

	protected function get_file_comment( string $rel_file ): string {
		return "// $rel_file (WP {$this->config->wp_version})";
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	abstract public function get_items(): array;

	abstract public function get_dest_file( array $item ): string;

	abstract public function generate_content( array $item ): string;

	abstract public function get_log_message( array $item ): string;

	protected function is_supported_for_current_wp( string $since ): bool {
		$wp_version = $this->config->wp_version;

		// add 3d zero - 7.0 >>> 7.0.0 for consistency
		if( preg_match( '/^\d+\.\d+$/', $wp_version ) ){
			$wp_version .= '.0';
		}

		return version_compare( $wp_version, $since, '>=' );
	}

}
