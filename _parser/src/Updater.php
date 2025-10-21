<?php

class Updater {

	private const SEP = '// ------------------auto-generated---------------------';

	private string $wp_version;
	private Extra_Replacer $extra_replacer;

	public function __construct(
		private readonly string $dest_dir,
		private readonly string $wp_core_dir,
		private readonly array $config_funcs,   /** @see _parser/config-funcs.php */
		private readonly array $config_classes, /** @see _parser/config-classes.php */
	) {
	}

	public function setup(): void {
		require_once "$this->wp_core_dir/wp-includes/version.php";
		/** @var string $wp_version */
		$this->wp_version = $wp_version;

		$this->extra_replacer = new Extra_Replacer( $wp_version );
	}

	public function run(): void {
		// functions
		foreach( $this->config_funcs as $rel_file => $funcs_names ){
			$this->update_file( $rel_file, $funcs_names, '' );
		}

		// classes
		foreach( $this->config_classes as $rel_file => $class_name ){
			$this->update_file( $rel_file, [], $class_name );
		}

		echo "DONE!\n";
	}

	private function update_file( string $rel_file, array $func_names, string $class_name ): void {
		$is_class = $this->is_class( $rel_file );

		$core_file = "$this->wp_core_dir/$rel_file";
		$dest_file = $this->dest_dir . ( $is_class ? "/classes/$class_name.php" : "/functions/$rel_file" );

		$this->check_create_dest_file( $dest_file );

		$dest_content = file_get_contents( $dest_file );
		$dest_content = explode( self::SEP, $dest_content )[0] . self::SEP . "\n\n";

		$dest_content .= $is_class
			? $this->update_class_file( file_get_contents( $core_file ), $rel_file, $class_name )
			: $this->update_func_file( file_get_contents( $core_file ), $rel_file, $func_names );

		$dest_content = $this->extra_replacer->replace_in_code( $dest_content );

		file_put_contents( $dest_file, $dest_content );

		echo "Updated: $rel_file\n";
	}

	private function is_class( string $rel_file ): bool {
		return str_contains( $rel_file, 'class-' );
	}

	private function update_func_file( string $core_file_content, string $rel_file, array $func_names ): string {
		$funcs_data = Parser_Helpers::get_class_func_code_from_php_code( $core_file_content, [ 'type' => 'func' ] );
		$funcs_data = array_intersect_key( $funcs_data, $func_names );
		$not_found_funcs = array_diff_key( $func_names, $funcs_data );

		if( $not_found_funcs ){
			throw new RuntimeException( "WARNING: Not found funcs in `$rel_file`:\n\t" . implode( "\n\t", array_keys( $not_found_funcs ) ) . "\n" );
		}

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

		return $append;
	}

	private function update_class_file( string $core_file_content, string $rel_file, string $class_name ): string {
		$code_lines = Parser_Helpers::get_class_func_code_from_php_code( $core_file_content, [ 'type' => 'class', 'name' => $class_name ] );
		$comment = "// $rel_file (WP $this->wp_version)";
		$class_code = implode( "\n\t", $code_lines );

		return <<<PHP
			$comment
			if( ! class_exists( '$class_name' ) ) :
				$class_code
			endif;
			PHP . "\n\n";
	}

	private function check_create_dest_file( string $file ): void {
	    if( ! file_exists( $file ) ){
			file_put_contents( $file, "<?php\n\n" );
	    }
	}

}
