<?php

namespace Parser\ExtraStrats;

use Parser\Config;

/**
 * This class is responsible for copying extra files from `wp-core` to runtime extra destinations.
 */
class Files_Copier {

	public function __construct(
		private readonly Config $config,
	){
	}

	public function run(): void {
		/** @var Files_Copy_Strategy[] $strategies */
		$strategies = [
			new Files_Full( $this->config ),
			new Files_Head_Init( $this->config ),
		];

		foreach( $strategies as $strategy ){
			$strategy->run();
		}
	}

}
