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
}
