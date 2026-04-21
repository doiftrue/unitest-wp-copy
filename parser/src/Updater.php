<?php
namespace Parser;

use Parser\Strategy\Copy_Classes;
use Parser\Strategy\Copy_Functions;
use Parser\Strategy\Copy_Static_Methods;
use Parser\Strategy\File_Update_Strategy;

class Updater {

	private const SEP = '// ------------------auto-generated---------------------';

	private Source_Code_Replacer $extra_replacer;
	private Extra_Copier $extra_copier;

	private readonly Config $config;
	private readonly Copied_Lister $lister;


	public function __construct() {
		$this->config = config();
	}

	public function setup(): void {
		$this->extra_replacer = new Source_Code_Replacer( $this->config );
		$this->extra_copier = new Extra_Copier( $this->config );
		$this->lister = new Copied_Lister( $this->config );
	}

	public function run(): void {
		/** @var File_Update_Strategy[] $strategies */
		$strategies = [
			new Copy_Functions( $this->config, $this->lister ),
			new Copy_Classes( $this->config, $this->lister ),
			new Copy_Static_Methods( $this->config, $this->lister ),
		];

		foreach( $strategies as $strategy ){
			foreach( $strategy->get_items() as $items_data ){
				$dest_file = $strategy->get_dest_file( $items_data );
				$content = $strategy->generate_content( $items_data );

				$this->run_update_pipeline( $dest_file, $content );

				echo $strategy->get_log_message( $items_data ) . "\n";
			}
		}

		$this->extra_copier->run();
		$this->lister->generate_list();

		echo "DONE!\n";
	}

	/**
	 * Common file update pipeline:
	 * read destination -> reset generated block -> append generated code -> run replacements -> write file.
	 */
	private function run_update_pipeline( string $dest_file, string $new_content ): void {
		$this->check_create_dest_file( $dest_file );

		$dest_content = file_get_contents( $dest_file );
		$dest_content = $this->reset_generated_part( $dest_content );
		$dest_content .= $new_content;

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
