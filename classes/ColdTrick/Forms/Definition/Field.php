<?php

namespace ColdTrick\Forms\Definition;

class Field {
	
	protected $config;

	protected $conditional_sections;
	
	public function __construct($config) {

		$this->config = $config;
		
		$this->conditional_sections = elgg_extract('conditional_sections', $this->config, []);
		unset($this->config['conditional_sections']);
		
		// set new name if missing
		if (!isset($this->config['name'])) {
			$this->config['name'] = '__field_' . (microtime(true) * 1000);
		}
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
		
		$result['pattern'] = $this->getPattern();
		unset($result['validation_rule']);
		
		$result['required'] = (bool) elgg_extract('required', $result, false);
		
		return $result;
	}
	
	public function getConfig() {
		return $this->config;
	}
	
	public function getConditionalSections() {
		$result = [];
		foreach ($this->conditional_sections as $section) {
			$result[] = new ConditionalSection($section);
		}
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
		
		if ($this->getType() == 'select' && !elgg_extract('multiple', $this->config, false)) {
			$result = array_merge(['' => elgg_echo('forms:view:field:select:empty')], $result);
		}
		
		return $result;
	}
	
	/**
	 * Get a validation pattern to put on an input
	 *
	 * @return void|string
	 */
	protected function getPattern() {
		
		$rule = $this->getValidationRule();
		if (empty($rule)) {
			return;
		}
		
		if (!in_array($this->getType(), elgg_extract('input_types', $rule))) {
			return;
		}
		
		return elgg_extract('regex', $rule);
	}
	
	/**
	 * Get all the applied validation rules for this field
	 *
	 * @return array
	 */
	public function getValidationRules() {
		
		$result = [];
		
		$rule = $this->getValidationRule();
		if (!empty($rule)) {
			$result[elgg_extract('name', $rule)] = $rule;
		}
		
		foreach ($this->getConditionalSections() as $conditional_section) {
			$result = array_merge($result, $conditional_section->getValidationRules());
		}
		
		return $result;
	}
	
	/**
	 * Get the validation rule for this field
	 *
	 * @return fasle|array
	 */
	protected function getValidationRule() {
		
		$validation_rule = elgg_extract('validation_rule', $this->config);
		if (empty($validation_rule)) {
			return false;
		}
		
		$rule = forms_get_validation_rule($validation_rule);
		if (empty($rule)) {
			return false;
		}
		
		return $rule;
	}
}
