<?php

namespace ColdTrick\Forms\Definition;

class Section {
	
	/**
	 * @var array the configuration of this section
	 */
	protected $config;
	
	/**
	 * @var \ColdTrick\Forms\Definition\Field[] the fields in this section
	 */
	protected $fields;
	
	/**
	 * Create a new section
	 *
	 * @param array $config the section configuration
	 */
	public function __construct($config) {

		$this->config = $config;
	}
	
	/**
	 * Get the section title
	 *
	 * @return string
	 */
	public function getTitle() {
		return elgg_extract('title', $this->config, '');
	}
	
	/**
	 * Get all the fields in this section
	 *
	 * @return \ColdTrick\Forms\Definition\Field[]
	 */
	public function getFields() {
		
		if (isset($this->fields)) {
			return $this->fields;
		}
		
		$this->fields = [];
		
		$fields = elgg_extract('fields', $this->config, []);
		foreach ($fields as $field) {
			$this->fields[] = new Field($field);
		}
		
		return $this->fields;
	}
	
	/**
	 * Get all the applied validation rules for this section
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
	
	/**
	 * Fill all the fields from their input submitted value
	 *
	 * @return void
	 */
	public function populateFromInput() {
		
		foreach ($this->getFields() as $field) {
			$field->populateFromInput();
		}
	}
}
