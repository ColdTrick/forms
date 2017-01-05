<?php

namespace ColdTrick\Forms\Definition;

class Field {
	
	protected $config;
	
	public function __construct($config) {

		$this->config = $config;
	}
	
	public function getType() {
		return elgg_extract('#type', $this->config, '');
	}
	
	public function getInputVars() {
		$result = $this->config;
		
		
		$result['options'] = $this->getOptions();
		
		switch ($this->getType()) {
			case 'plaintext':
				$result['rows'] = 2;
				break;
		}
	
		return $result;
	}
	
	protected function getOptions() {
		$options = elgg_extract('options', $this->config);
				
		$result = string_to_tag_array($options);
		if (!is_array($result)) {
			$result = [];
		}

		if ($this->getType() == 'select') {
			array_unshift($result, 'please select a value');
		}
		
		return $result;
	}
}
