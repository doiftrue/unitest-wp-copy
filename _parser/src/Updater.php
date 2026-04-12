<?php
namespace Parser;

use Parser\Strategy\Copy_Classes;
use Parser\Strategy\Copy_Functions;
use Parser\Strategy\Copy_Static_Methods;
use Parser\Strategy\File_Update_Strategy;

class Updater {

	private const SEP = '// ------------------auto-generated---------------------';

	private Extra_Replacer $extra_replacer;

	private readonly Config $config;


	public function __construct() {
		$this->config = config();
	}

	public function setup(): void {
		$this->extra_replacer = new Extra_Replacer( $this->config );
	}

	public function run(): void {
		$lister = new Copied_Lister( $this->config );

		/** @var File_Update_Strategy[] $strategies */
		$strategies = [
			new Copy_Functions( $this->config, $lister ),
			new Copy_Classes( $this->config, $lister ),
			new Copy_Static_Methods( $this->config, $lister ),
		];

		foreach( $strategies as $strategy ){
			foreach( $strategy->get_items() as $item ){
				$dest_file = $strategy->get_dest_file( $item );

				$this->run_update_pipeline(
					$dest_file,
					fn() => $strategy->generate_content( $item )
				);

				echo $strategy->get_log_message( $item ) . "\n";
			}
		}

		$lister->generate_list();

		echo "DONE!\n";
	}

	/**
	 * Common file update pipeline:
	 * read destination -> reset generated block -> append generated code -> run replacements -> write file.
	 */
	private function run_update_pipeline( string $dest_file, callable $content_generator ): void {
		$this->check_create_dest_file( $dest_file );

		$dest_content = file_get_contents( $dest_file );
		$dest_content = $this->reset_generated_part( $dest_content );
		$dest_content .= $content_generator();

		$dest_content = $this->extra_replacer->replace_in_code( $dest_content );

		file_put_contents( $dest_file, $dest_content );
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
