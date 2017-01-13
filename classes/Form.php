<?php

use \ColdTrick\Forms\Definition;
use \ColdTrick\Forms\Endpoint;

class Form extends \ElggObject {
	
	const SUBTYPE = 'form';
	
	/**
	 * @var \ColdTrick\Forms\Definition
	 */
	protected $definition_object;
	
	/**
	 * {@inheritDoc}
	 * @see ElggObject::initializeAttributes()
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$site = elgg_get_site_entity();
		
		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['owner_guid'] = $site->getGUID();
		$this->attributes['container_guid'] = $site->getGUID();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggEntity::getURL()
	 */
	public function getURL() {
		
		if (!empty($this->friendly_url)) {
			return "forms/{$this->friendly_url}";
		}
		
		return "forms/view/{$this->getGUID()}";
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggObject::canComment()
	 */
	public function canComment($user_guid = 0, $default = null) {
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggEntity::__clone()
	 */
	public function __clone() {
		parent::__clone();
		
		$this->attributes['time_created'] = null;
		$this->title = elgg_echo('forms:entity:clone:title', [$this->title]);
		$this->friendly_url = forms_generate_valid_friendly_url("{$this->friendly_url}-copy");
	}
	
	/**
	 * Check if this form has a saved definition
	 *
	 * @return bool
	 */
	public function hasDefinition() {
		return !empty($this->definition);
	}
	
	/**
	 * Get the form definition
	 *
	 * @return \ColdTrick\Forms\Definition
	 */
	public function getDefinition() {
		if (!isset($this->definition_object)) {
			$this->definition_object = new Definition($this);
		}
		
		return $this->definition_object;
	}
	
	/**
	 * Export the form definition
	 *
	 * @return false|string
	 */
	public function exportDefinition() {
		
		if (!$this->hasDefinition()) {
			return false;
		}
		
		$definition = json_decode($this->definition, true);
		$rules = $this->getDefinition()->getValidationRules();
		
		return json_encode([
			'definition' => $definition,
			'rules' => $rules,
		], JSON_PRETTY_PRINT);
	}
	
	/**
	 * Import a form definition
	 *
	 * @param string $definition
	 *
	 * @return bool
	 */
	public function importDefinition($definition) {
		
	}
	
	/**
	 * Get the endpoint configuration
	 *
	 * @param string $endpoint (optional) the endpoint to get the config for
	 *
	 * @return array
	 */
	public function getEndpointConfig($endpoint = null) {
		
		if (empty($this->endpoint_config)) {
			return [];
		}
		
		$endpoint_config = json_decode($this->endpoint_config, true);
		if (!isset($endpoint)) {
			return $endpoint_config;
		}
		
		return (array) elgg_extract($endpoint, $endpoint_config, []);
	}
	
	/**
	 * Get the form endpoint
	 *
	 * @return false|\ColdTrick\Forms\Endpoint
	 */
	public function getEndpoint() {
		
		if (empty($this->endpoint)) {
			return false;
		}
		
		$endpoints = forms_get_available_endpoints();
		if (!is_array($endpoints)) {
			return false;
		}
		
		$endpoint_information = elgg_extract($this->endpoint, $endpoints);
		if (empty($endpoint_information)) {
			return false;
		}
		
		$class = elgg_extract('class', $endpoint_information);
		if (empty($class) || !class_exists($class)) {
			return false;
		}
		
		$endpoint_config = $this->getEndpointConfig($this->endpoint);
		
		try {
			$endpoint = new $class($endpoint_config);
		} catch (Exception $e) {
			elgg_log("Form->getEndpoint() error: {$e->getMessage()}", 'ERROR');
			return false;
		}
		
		if (!($endpoint instanceof Endpoint)) {
			elgg_log('Form endpoint is not an instanceof \ColdTrick\Forms\Endpoint', 'ERROR');
			return false;
		}
		
		return $endpoint;
	}
}
