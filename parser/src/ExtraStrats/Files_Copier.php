<?php

namespace Parser\ExtraStrats;

use Parser\Config;
use Parser\Logger;

/**
 * This class is responsible for copying extra files from `wp-core` to runtime extra destinations.
 */
class Files_Copier {

	public function __construct(
		private readonly Config $config,
		private readonly Logger $logger,
	){
	}

	public function run(): void {
		/** @var Files_Copy_Strategy[] $strategies */
		$strategies = [
			new Files_Full( $this->config, $this->logger ),
			new Files_Head_Init( $this->config, $this->logger ),
		];

		foreach( $strategies as $strategy ){
			$strategy->run();
		}
	}

}
