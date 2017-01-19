<?php

namespace ColdTrick\Forms;

abstract class Endpoint {
	
	/**
	 * @var array $config the endpoint configuration
	 */
	protected $config;
	
	/**
	 * Create a new endpoint
	 *
	 * @param array $configuration the endpoint configuration
	 */
	public function __construct(array $configuration) {
		
		$this->config = $configuration;
	}
	
	/**
	 * Get a config value
	 *
	 * @param string $name the name of the configuration value
	 *
	 * @return void|mixed
	 */
	protected function getConfig($name) {
		return elgg_extract($name, $this->config);
	}
	
	/**
	 * Process the result of a form
	 *
	 * @param Result $result
	 *
	 * @return bool
	 */
	abstract public function process(Result $result);
}
