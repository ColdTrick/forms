<?php

namespace ColdTrick\Forms\Definition;

class ConditionalSection {
	
	protected $config;
	
	public function __construct($config) {

		$this->config = $config;
	}
	
	public function getValue() {
		return elgg_extract('value', $this->config);
	}
	
	public function getFields() {
		$result = [];
		
		foreach (elgg_extract('fields', $this->config) as $field_config) {
			$result[] = new Field($field_config);
		}
		return $result;
	}
	
}
