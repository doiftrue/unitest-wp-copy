<?php
namespace Parser;

use Parser\BaseStrats\Symbols_Copier;
use Parser\ExtraStrats\Files_Copier;

class Updater {

	private Files_Copier $files_copier;
	private Symbols_Copier $symbols_copier;

	private readonly Config $config;

	public function __construct() {
		$this->config = new Config();
	}

	public function setup(): void {
		$this->files_copier = new Files_Copier( $this->config );
		$this->symbols_copier = new Symbols_Copier( $this->config, new Symbols_Lister( $this->config ) );
	}

	public function run(): void {
		$this->symbols_copier->run();

		$this->files_copier->run();

		echo "DONE!\n";
	}

}
