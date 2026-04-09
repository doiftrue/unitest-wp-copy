<?php

class Config {

	public readonly string $dest_dir;

	public readonly string $wp_core_dir;

	/** @see _parser/config/functions.php */
	public readonly array $funcs_data;

	/** @see _parser/config/classes.php */
	public readonly array $classes_data;

	/** @see _parser/config/static-methods.php */
	public readonly array $static_methods_data;

	public function __construct() {
		$parser_dir = dirname( __DIR__ );
		$project_dir = dirname( $parser_dir );

		$this->dest_dir = "$project_dir/copy";
		$this->wp_core_dir = "$project_dir/vendor/wordpress/wordpress";
		$this->funcs_data = include "$parser_dir/config/functions.php";
		$this->classes_data = include "$parser_dir/config/classes.php";
		$this->static_methods_data = include "$parser_dir/config/static-methods.php";
	}

}
