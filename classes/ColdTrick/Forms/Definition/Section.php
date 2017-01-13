<?php

namespace ColdTrick\Forms\Definition;

class Section {
	
	protected $config;
	
	public function __construct($config) {

		$this->config = $config;
	}

	public function getTitle() {
		return elgg_extract('title', $this->config, '');
	}
	
	public function getFields() {
		$result = [];
		
		$fields = elgg_extract('fields', $this->config, []);
		foreach ($fields as $field) {
			$result[] = new Field($field);
		}
		return $result;
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
}
