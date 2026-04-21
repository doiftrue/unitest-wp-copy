<?php

namespace Parser\BaseStrats;

use Parser\Config;
use Parser\Source_Code_Replacer;
use Parser\Symbols_Lister;

class Symbols_Copier {

	private const SEP = '// ------------------auto-generated---------------------';

	private readonly Source_Code_Replacer $extra_replacer;

	public function __construct(
		private readonly Config $config,
		private readonly Symbols_Lister $lister,
	){
		$this->extra_replacer = new Source_Code_Replacer( $this->config );
	}

	public function run(): void {
		/** @var Symbols_Copy_Strategy[] $strategies */
		$strategies = [
			new Functions_Copier( $this->config, $this->lister ),
			new Classes_Copier( $this->config, $this->lister ),
			new Static_Methods_Copier( $this->config, $this->lister ),
		];

		foreach( $strategies as $strategy ){
			foreach( $strategy->get_items() as $items_data ){
				$dest_file = $strategy->get_dest_file( $items_data );
				$content = $strategy->generate_content( $items_data );

				$this->run_update_pipeline( $dest_file, $content );

				echo $strategy->get_log_message( $items_data ) . "\n";
			}
		}

		$this->lister->generate_list();
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
		if( ! \str_contains( $dest_content, self::SEP ) ){
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
