<?php

namespace Parser;

function config(): Config {
	static $config;
	$config = $config ?? new Config();
	return $config;
}
