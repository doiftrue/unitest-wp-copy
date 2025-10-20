<?php

class Updater {

	/** @see src/config.php */
	private array $config;
	private string $dest_dir;
	private string $wp_core_dir;

	private string $wp_version;

	public function __construct(
		string $dest_dir,
		string $wp_core_dir,
		array $config
	) {
		$this->dest_dir = $dest_dir;
		$this->wp_core_dir = $wp_core_dir;
		$this->config = $config;
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

		$this->check_create_dest_file( $dest_file );

		$dest_content = file_get_contents( $dest_file );
		$dest_content = explode( $sep, $dest_content )[0] . "$sep\n\n";

		$core_file_content = file_get_contents( $core_file );
		$funcs_data = Parser_Helpers::get_class_func_code_from_php_code( $core_file_content, [ 'type' => 'func' ] );

		$funcs_data = array_intersect_key( $funcs_data, $func_names );

		$append = '';
		foreach( $funcs_data as $func_name => $code_lines ){
			$comment = "// $rel_file (WP $this->wp_version)";
			$func_code = implode( "\n\t", $code_lines );
			$append .= <<<PHP
				$comment
				if( ! function_exists( '$func_name' ) ) :
					$func_code
				endif;
				PHP . "\n\n";
		}

		$dest_content .= $append;

		$this->extra_replace_in_code( $dest_content );

		file_put_contents( $dest_file, $dest_content );

		echo "Updated: $rel_file\n";
	}

	private function check_create_dest_file( string $file ): void {
	    if( ! file_exists( $file ) ){
			file_put_contents( $file, "<?php\n\n" );
	    }
	}

	private function extra_replace_in_code( string & $text ) {
		static $stub_wp_options;
		$stub_wp_options || $stub_wp_options = require dirname( __DIR__ ) . '/stub_wp_options.php';
		$text = strtr( $text, $stub_wp_options );

		$text = str_replace( "get_bloginfo( 'version' )", "'$this->wp_version'", $text );

		// static class method replacement
		// TODO make it automatic from $config
		$text = str_replace( "WP_Http::make_absolute_url(", "WP_Http__make_absolute_url(", $text );
	}

}
