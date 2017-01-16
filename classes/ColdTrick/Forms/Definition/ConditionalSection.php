<?php

namespace ColdTrick\Forms\Definition;

class ConditionalSection {
	
	/**
	 * @var array the conditional section configuration
	 */
	protected $config;
	
	/**
	 * @var \ColdTrick\Forms\Definition\Field[] all the fields in this section
	 */
	protected $fields;
	
	/**
	 * Create a new conditional section
	 *
	 * @param array $config the conditional section configuration
	 */
	public function __construct($config) {

		$this->config = $config;
	}
	
	/**
	 * Get the matching value for this conditional section
	 *
	 * @return string
	 */
	public function getValue() {
		return elgg_extract('value', $this->config);
	}
	
	/**
	 * Get the fields in this conditional section
	 *
	 * @return \ColdTrick\Forms\Definition\Field[]
	 */
	public function getFields() {
		
		if (isset($this->fields)) {
			return $this->fields;
		}
		
		$this->fields = [];
		
		foreach (elgg_extract('fields', $this->config) as $field_config) {
			$this->fields[] = new Field($field_config);
		}
		
		return $this->fields;
	}
	
	/**
	 * Get all the applied validation rules for this conditional section
	 *
	 * @return array
	 */
	public function getValidationRules() {
		$result = [];
		
		foreach ($this->getFields() as $field) {
			$result = array_merge($result, $field->getValidationRules());
		}
		
		return $result;
	}
	
}
