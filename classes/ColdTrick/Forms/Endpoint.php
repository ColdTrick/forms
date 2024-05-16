<?php

namespace ColdTrick\Forms;

/**
 * Form Endpoint
 */
abstract class Endpoint {
	
	/**
	 * Create a new endpoint
	 *
	 * @param array $config the endpoint configuration
	 */
	public function __construct(protected array $config) {
	}
	
	/**
	 * Get a config value
	 *
	 * @param string $name the name of the configuration value
	 *
	 * @return mixed
	 */
	protected function getConfig(string $name): mixed {
		return elgg_extract($name, $this->config);
	}
	
	/**
	 * Process the result of a form
	 *
	 * @param Result $result result of processing the form
	 *
	 * @return bool
	 */
	abstract public function process(Result $result): bool;
}
