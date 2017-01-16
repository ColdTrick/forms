<?php

namespace ColdTrick\Forms\Definition;

class Field {
	
	/**
	 * @var array the field configiration
	 */
	protected $config;

	/**
	 * @var array the conditional section configuration
	 */
	protected $conditional_sections;
	
	/**
	 * Create a new form field
	 *
	 * @param array $config the field configiration
	 */
	public function __construct($config) {

		$this->config = $config;
		
		$this->conditional_sections = elgg_extract('conditional_sections', $this->config, []);
		unset($this->config['conditional_sections']);
	}
	
	/**
	 * Get the field type
	 *
	 * @return string
	 */
	public function getType() {
		return elgg_extract('#type', $this->config, '');
	}
	
	/**
	 * Get the input variables
	 *
	 * @return array
	 */
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
	
	/**
	 * Get the field configuration
	 *
	 * @return array
	 */
	public function getConfig() {
		return $this->config;
	}
	
	/**
	 * Get all the conditional sections for this field
	 *
	 * @return \ColdTrick\Forms\Definition\ConditionalSection[]
	 */
	public function getConditionalSections() {
		$result = [];
		foreach ($this->conditional_sections as $section) {
			$result[] = new ConditionalSection($section);
		}
		return $result;
	}
	
	/**
	 * Create the options for use in the inputVars
	 *
	 * @return array
	 */
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
