<?php

namespace Parser;

final class Logger {

	public function __construct(
		private readonly bool $verbose = false,
	){
	}

	public function progress( string $message ): void {
		echo $this->verbose
			? "$message\n"
			: '.';
	}

	public function done(): void {
		echo $this->verbose
			? "DONE!\n"
			: "\n";
	}

}
