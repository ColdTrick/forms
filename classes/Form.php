<?php

use ColdTrick\Forms\Definition;
use ColdTrick\Forms\Endpoint;
use Elgg\Exceptions\InvalidArgumentException;

/**
 * Form entity
 *
 * @property string $definition      JSON encoded form configuration
 * @property string $endpoint        which endpoint type to use
 * @property string $endpoint_config JSON encoded configuration for the endpoint
 * @property string $friendly_url    friendly URL to the form
 * @property int    $submitted_count the number of submitted forms
 * @property string $thankyou        text to display after the submission of a form
 */
class Form extends \ElggObject {
	
	const SUBTYPE = 'form';

	protected \ColdTrick\Forms\Definition $definition_object;
	
	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$site = elgg_get_site_entity();
		
		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['owner_guid'] = $site->guid;
		$this->attributes['container_guid'] = $site->guid;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getURL(): string {
		if (!empty($this->friendly_url)) {
			return elgg_generate_url('view:object:form:friendly', [
				'title' => $this->friendly_url,
			]);
		}
		
		return (string) elgg_generate_entity_url($this);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function __clone() {
		parent::__clone();
		
		$this->attributes['time_created'] = null;
		$this->title = elgg_echo('forms:entity:clone:title', [$this->title]);
		$this->friendly_url = forms_generate_valid_friendly_url("{$this->friendly_url}-copy");
		unset($this->submitted_count);
	}
	
	/**
	 * Check if this form has a saved definition
	 *
	 * @return bool
	 */
	public function hasDefinition(): bool {
		return !empty($this->definition);
	}
	
	/**
	 * Get the form definition
	 *
	 * @return \ColdTrick\Forms\Definition
	 *
	 * @throws InvalidArgumentException
	 */
	public function getDefinition(): \ColdTrick\Forms\Definition {
		if (!isset($this->definition_object)) {
			$endpoints = forms_get_available_endpoints();
			$endpoint = elgg_extract($this->endpoint, $endpoints);
			$class = elgg_extract('definition', $endpoint, Definition::class);
			
			$definition = new $class($this);
			if (!$definition instanceof Definition) {
				throw new InvalidArgumentException("{$class} must extend " . Definition::class);
			}
			
			$this->definition_object = $definition;
		}
		
		return $this->definition_object;
	}
	
	/**
	 * Can the form be used / filled in
	 *
	 * @return bool
	 */
	public function isValid(): bool {
		if (!$this->hasDefinition()) {
			return false;
		}
		
		return $this->getDefinition()->isValid();
	}
	
	/**
	 * Export the form definition
	 *
	 * @return string|null
	 */
	public function exportDefinition(): ?string {
		
		if (!$this->hasDefinition()) {
			return null;
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
	 * @param string $json_string json definition
	 *
	 * @return bool
	 */
	public function importDefinition(string $json_string): bool {
		
		$data = @json_decode($json_string, true);
		if (empty($data)) {
			return false;
		}
		
		$definition = elgg_extract('definition', $data);
		if (empty($definition)) {
			return false;
		}
		
		$this->definition = json_encode($definition);
		
		$validation_rules = elgg_extract('rules', $data);
		if (!empty($validation_rules)) {
			// proccess validation rules
			$current_validation_rules = forms_get_validation_rules();
			
			foreach ($validation_rules as $name => $rule) {
				if (array_key_exists($name, $current_validation_rules)) {
					// don't override already existing validation rules
					continue;
				}
				
				$current_validation_rules[$name] = $rule;
			}
			
			forms_save_validation_rules($current_validation_rules);
		}
		
		return true;
	}
	
	/**
	 * Get the endpoint configuration
	 *
	 * @param string $endpoint (optional) the endpoint to get the config for
	 *
	 * @return array
	 */
	public function getEndpointConfig(string $endpoint = null): array {
		
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
	 * @return null|\ColdTrick\Forms\Endpoint
	 */
	public function getEndpoint(): ?\ColdTrick\Forms\Endpoint {
		if (empty($this->endpoint)) {
			return null;
		}
		
		$endpoints = forms_get_available_endpoints();
		if (!is_array($endpoints)) {
			return null;
		}
		
		$endpoint_information = elgg_extract($this->endpoint, $endpoints);
		if (empty($endpoint_information)) {
			return null;
		}
		
		$class = elgg_extract('class', $endpoint_information);
		if (empty($class) || !class_exists($class)) {
			return null;
		}
		
		$endpoint_config = $this->getEndpointConfig($this->endpoint);
		
		try {
			$endpoint = new $class($endpoint_config);
		} catch (Exception $e) {
			elgg_log("Form->getEndpoint() error: {$e->getMessage()}", 'ERROR');
			return null;
		}
		
		if (!$endpoint instanceof Endpoint) {
			elgg_log('Form endpoint is not an instanceof \ColdTrick\Forms\Endpoint', 'ERROR');
			return null;
		}
		
		return $endpoint;
	}
	
	/**
	 * Log the submission of this form
	 *
	 * @return void
	 */
	public function logSubmission(): void {
		$count = 0;
		if (isset($this->submitted_count)) {
			$count = (int) $this->submitted_count;
		}
		
		$count++;
		
		$this->submitted_count = $count;
	}
}
