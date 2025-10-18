<?php

class Updater {

	/** @see config.php */
	private array $config;
	private string $dest_dir;

	private string $wp_core_dir = WP_CORE_DIR;
	private string $wp_version;

	public function __construct( string $root_dir ) {
		$this->dest_dir = "$root_dir/functions";
		$this->config = include "$root_dir/config.php";
	}

	public function setup(): void {
		require_once "$this->wp_core_dir/wp-includes/version.php";
		/** @var string $wp_version */
		$this->wp_version = $wp_version;
	}

	public function run(): void {
		foreach( $this->config as $rel_file => $funcs_names ){
			$this->update_file( $rel_file, $funcs_names );
		}

		echo "DONE!\n";
	}

	private function update_file( string $rel_file, array $func_names ): void {
		$sep = '// ------------------auto-generated---------------------';

		$core_file = "$this->wp_core_dir/$rel_file";
		$dest_file = "$this->dest_dir/$rel_file";

		$dest_content = file_get_contents( $dest_file );
		$dest_content = explode( $sep, $dest_content )[0] . "$sep\n\n";

		$core_file_content = file_get_contents( $core_file );
		$funcs_data = Parser_Helpers::get_class_func_code_from_php_code( $core_file_content, [ 'type' => 'func' ] );

		$funcs_data = array_intersect_key( $funcs_data, $func_names );

		$append = '';
		foreach( $funcs_data as $func_name => $code_lines ){
			$code = "// $rel_file (WP $this->wp_version)\n";
			$code .= implode( "\n", $code_lines );
			$code .= "\n\n";

			$append .= $code;
		}

		$dest_content .= $append;

		$this->extra_replace_in_code( $dest_content );

		file_put_contents( $dest_file, $dest_content );

		echo "Updated: $rel_file\n";
	}

	private function extra_replace_in_code( string & $dest_content ) {
		$dest_content = str_replace( "get_option( 'blog_charset' )", 'WPCOPY__OPTION_BLOG_CHARSET', $dest_content );
	}

}
