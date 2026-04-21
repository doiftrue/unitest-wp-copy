<?php
namespace Parser\ExtraStrats;

use RuntimeException;

/**
 * Copies head part of the file (before first function code)
 * to `init-parts` dir, which is used for runtime initialization.
 */
class Files_Head_Init extends Files_Copy_Strategy {

	protected array $files_map = [
		'wp-includes/kses.php' => 'wp-includes/kses.php',
	];

	protected function get_content( string $rel_file ): string {
		$code = file_get_contents( "{$this->config->wp_core_dir}/$rel_file" );
		preg_match( '/^function\s+\w+\s*\(/m', $code, $m, PREG_OFFSET_CAPTURE );
		if( ! $m ){
			throw new RuntimeException( "WARNING: Not found first function in extra file `$rel_file`." );
		}

		$code = rtrim( substr( $code, 0, $m[0][1] ) );
		//$code = $this->remove_last_block_comment( $code );

		return  "$code\n";
	}

	protected function get_dest_file( string $rel_file ): string {
		return "$this->init_parts_dir/$rel_file";
	}

	protected function get_log_message( string $rel_file ): string {
		return "Updated init-part file: $rel_file";
	}

	private function remove_last_block_comment( string $code ): string {
		$code = rtrim( $code );
		$code = preg_replace( '/\/\*\*[\s\S]*?\*\/\s*$/', '', $code ); // TODO: fix regex & write tests
		return $code;
	}

}
