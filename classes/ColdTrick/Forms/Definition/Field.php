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
		
		$options_key = 'options_values';
		$options = $this->getOptions();
		unset($result['options']);
		
		switch ($this->getType()) {
			case 'checkboxes':
				$options_key = 'options';
				break;
			case 'plaintext':
				$result['rows'] = 2;
				break;
		}
		
		if (!empty($options)) {
			$result[$options_key] = $options;
		}
		
		$result['required'] = (bool) elgg_extract('required', $result, false);
		
		return $result;
	}
	
	protected function getOptions() {
		$options = elgg_extract('options', $this->config, '');
		
		$result = explode(',', $options);
		$result = array_map('trim', $result);
		$result = array_filter($result);
		$result = array_unique($result);
		
		// values = keys
		$result = array_combine($result, $result);
		
		if ($this->getType() == 'select') {
			$result = array_merge(['' => elgg_echo('forms:view:field:select:empty')], $result);
		}
		
		return $result;
	}
}
