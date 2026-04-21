<?php

namespace Parser;

use Parser\ExtraStrats\File_Head_Init_Copy;
use Parser\ExtraStrats\File_Copy;
use Parser\ExtraStrats\Files_Copy_Strategy;

/**
 * This class is responsible for copying extra files from `wp-core` to runtime extra destinations.
 */
class Extra_Copier {

	public function __construct(
		private readonly Config $config,
	){
	}

	public function run(): void {
		/** @var Files_Copy_Strategy[] $strategies */
		$strategies = [
			new File_Copy( $this->config ),
			new File_Head_Init_Copy( $this->config ),
		];

		foreach( $strategies as $strategy ){
			$strategy->run();
		}
	}

}
