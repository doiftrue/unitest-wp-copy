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
		private readonly array $config_class_statics = [], /** @see _parser/config-class-statics.php */
	) {
	}

	public function setup(): void {
		require_once "$this->wp_core_dir/wp-includes/version.php";
		/** @var string $wp_version */
		$this->wp_version = $wp_version;

		$this->extra_replacer = new Extra_Replacer( $this->config_class_statics );
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

		// static class methods copied as plain functions
		foreach( $this->config_class_statics as $rel_file => $config ){
			$class_name = $config['class'] ?? '';
			$method_names = $config['methods'] ?? [];
			$this->update_class_static_file( $rel_file, $class_name, $method_names );
		}

		echo "DONE!\n";
	}

	private function update_file( string $rel_file, array $func_names, string $class_name ): void {
		$is_class = $this->is_class( $rel_file );

		$core_file = "$this->wp_core_dir/$rel_file";
		$dest_file = $this->dest_dir . ( $is_class ? "/classes/$class_name.php" : "/functions/$rel_file" );

		$this->check_create_dest_file( $dest_file );

		$dest_content = file_get_contents( $dest_file );
		$dest_content = $this->reset_generated_part( $dest_content );

		$dest_content .= $is_class
			? $this->update_class_file( $rel_file, $class_name )
			: $this->update_func_file( $rel_file, $func_names );

		$dest_content = $this->extra_replacer->replace_in_code( $dest_content );

		file_put_contents( $dest_file, $dest_content );

		echo "Updated: $rel_file\n";
	}

	private function is_class( string $rel_file ): bool {
		return str_contains( $rel_file, 'class-' );
	}

	private function update_func_file( string $rel_file, array $func_names ): string {
		$core_file_content = file_get_contents( "$this->wp_core_dir/$rel_file" );
		$funcs_data = Helpers::get_class_func_code_from_php_code( $core_file_content, [ 'type' => 'func' ] );
		$funcs_data = array_intersect_key( $funcs_data, $func_names );
		$not_found_funcs = array_diff_key( $func_names, $funcs_data );

		if( $not_found_funcs ){
			throw new RuntimeException( "WARNING: Not found funcs in `$rel_file`:\n\t" . implode( "\n\t", array_keys( $not_found_funcs ) ) . "\n" );
		}

		$append = '';
		foreach( $funcs_data as $func_name => $code_lines ){
			$comment = "// $rel_file (WP $this->wp_version)";
			$func_code = implode( "\n\t", $code_lines );
			$append .= <<<CODE
				$comment
				if( ! function_exists( '$func_name' ) ) :
					$func_code
				endif;
				CODE . "\n\n";
		}

		return $append;
	}

	private function update_class_file( string $rel_file, string $class_name ): string {
		$file_content = file_get_contents( "$this->wp_core_dir/$rel_file" );
		$code_lines = Helpers::get_class_func_code_from_php_code( $file_content, [ 'type' => 'class', 'name' => $class_name ] );
		$comment = "// $rel_file (WP $this->wp_version)";
		$class_code = implode( "\n\t", $code_lines );

		return <<<CODE
			$comment
			if( ! class_exists( '$class_name' ) ) :
				$class_code
			endif;
			CODE . "\n\n";
	}

	private function update_class_static_file( string $rel_file, string $class_name, array $method_names ): void {
		if( ! $class_name || ! $method_names ){
			throw new RuntimeException( "WARNING: Invalid static-method config for `$rel_file`. Expected keys: class, methods." );
		}

		$file_content = file_get_contents( "$this->wp_core_dir/$rel_file" );
		$dest_file = "$this->dest_dir/classes-statics/$class_name.php";

		$content = file_get_contents( $dest_file );
		$content = $this->reset_generated_part( $content );
		$content .= $this->get_class_static_methods_file_content( $file_content, $rel_file, $class_name, $method_names );

		$content = $this->extra_replacer->replace_in_code( $content );

		$this->check_create_dest_file( $dest_file );
		file_put_contents( $dest_file, $content );

		echo "Updated static methods: $rel_file\n";
	}

	/**
	 * Static class method replacement: `Class::method -> Class__method()`
	 */
	private function get_class_static_methods_file_content( string $core_file_content, string $rel_file, string $class_name, array $method_names ): string {
		$methods_data = Helpers::get_class_func_code_from_php_code( $core_file_content, [ 'type' => 'method' ] );
		$methods_data = array_intersect_key( $methods_data, $method_names );
		$not_found_methods = array_diff_key( $method_names, $methods_data );

		if( $not_found_methods ){
			throw new RuntimeException( "WARNING: Not found static methods:\n\t" . implode( "\n\t", array_keys( $not_found_methods ) ) . "\n" );
		}

		$append = '';
		foreach( $methods_data as $method_name => $code_lines ){
			$comment = "// $rel_file (WP $this->wp_version)";
			$func_name = "{$class_name}__$method_name";
			$method_code = implode( "\n\t", $code_lines );
			$func_code = $this->rename_method_name( $method_code, $method_name, $func_name );

			$append .= <<<CODE
				$comment
				if( ! function_exists( '$func_name' ) ) :
					$func_code
				endif;
				CODE . "\n\n";
		}

		return $append;
	}

	private function rename_method_name( string $method_code, string $method_name, string $func_name ): string {
		$method_code = trim( $method_code );
		$lines = explode( "\n", $method_code );

		$line = & $lines[0];
		$line = preg_replace( '~\b(?:final|abstract|public|protected|private|static)\s+~', '', $line );
		$line = str_replace( " $method_name(", " $func_name(", $line );

		return implode( "\n", $lines );
	}

	private function reset_generated_part( string $dest_content ): string {
		if( ! str_contains( $dest_content, self::SEP ) ){
			return "<?php\n\n" . self::SEP . "\n\n";
		}

		$prefix = explode( self::SEP, $dest_content )[0];
		return rtrim( $prefix ) . "\n\n" . self::SEP . "\n\n";
	}

	private function check_create_dest_file( string $file ): void {
	    if( ! file_exists( $file ) ){
			file_put_contents( $file, "<?php\n\n" );
	    }
	}

}
