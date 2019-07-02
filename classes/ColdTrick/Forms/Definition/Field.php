<?php

namespace ColdTrick\Forms\Definition;

use ColdTrick\Forms\Exception\InvalidInputException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
	 * @var \ColdTrick\Forms\Definition\ConditionalSection[] the confitional sections
	 */
	protected $conditional_sections_objects;
	
	/**
	 * @var mixed the submitted value of this field
	 */
	protected $value;
	
	/**
	 * @var UploadedFile[] information about an uploaded file (if type is 'file')
	 */
	protected $file_info;
	
	/**
	 * Create a new form field
	 *
	 * @param array $config the field configiration
	 */
	public function __construct($config) {

		$this->config = $config;
		
		$this->conditional_sections = elgg_extract('conditional_sections', $this->config, []);
		unset($this->config['conditional_sections']);
		
		// set new name if missing
		if (!isset($this->config['name'])) {
			$this->config['name'] = '__field_' . (microtime(true) * 10000);
		}
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
	 * Get the name of the field
	 *
	 * @return string
	 */
	public function getName() {
		return elgg_extract('name', $this->config, '');
	}
	
	/**
	 * Get the label of the field
	 *
	 * @return string
	 */
	public function getLabel() {
		return elgg_extract('#label', $this->config, '');
	}
	
	/**
	 * Get the input variables
	 *
	 * @param array $additional_vars additional input vars
	 *
	 * @return array
	 */
	public function getInputVars(array $additional_vars = []) {
		$result = $this->config;
		
		// remove unneeded vars
		if (isset($result['multiple']) && $result['multiple'] === '') {
			unset($result['multiple']);
		}
		// value (with support for default value)
		if (isset($result['value']) && $result['value'] === '') {
			unset($result['value']);
		}
		$supports_default = ['text', 'email', 'number', 'plaintext', 'longtext', 'select'];
		if (!isset($result['value']) && in_array($this->getType(), $supports_default) && !empty($result['default_value'])) {
			$profile_field = $result['default_value'];
			$user = elgg_get_logged_in_user_entity();
			if (!empty($user)) {
				$result['value'] = $user->$profile_field;
			}
		}
		unset($result['default_value']);
		
		// futher cleanup
		$options_key = 'options_values';
		$options = $this->getOptions();
		unset($result['options']);
		
		switch ($this->getType()) {
			case 'checkboxes':
			case 'radio':
				$options_key = 'options';
				break;
			case 'plaintext':
				$result['rows'] = 2;
				break;
			case 'hidden':
				unset($result['required']);
				unset($result['#help']);
				unset($result['#label']);
				unset($result['multiple']);
				break;
			case 'file':
				unset($result['value']);
				break;
		}
		
		if (!empty($options)) {
			$result[$options_key] = $options;
		}
		
		$result['pattern'] = $this->getPattern();
		unset($result['validation_rule']);
		$result['data-custom-error-message'] = $this->getCustomErrorMessage();
		
		$result['required'] = (bool) elgg_extract('required', $result, false);
		
		// set sticky form value
		$sticky_value = elgg_extract('sticky_value', $additional_vars);
		unset($additional_vars['sticky_value']);
		if (isset($sticky_value)) {
			switch ($this->getType()) {
				case 'checkbox':
					// @todo how does this work
				case 'hidden':
					// value is pre-programmed
				case 'file':
					// doesn't work
					break;
				default:
					$result['value'] = $sticky_value;
					break;
			}
		}
		
		return array_merge($result, $additional_vars);
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
	 * @param bool $apply_section_filter appy the conditional section filter
	 *
	 * @return \ColdTrick\Forms\Definition\ConditionalSection[]
	 */
	public function getConditionalSections($apply_section_filter = false) {
		
		$apply_section_filter = (bool) $apply_section_filter;
		if ($apply_section_filter && !isset($this->conditional_sections_objects)) {
			// load sections
			$this->getConditionalSections();
		}
		
		if ($apply_section_filter) {
			$sections = [];
			$field_value = (array) $this->value;
			foreach ($this->conditional_sections_objects as $section) {
				if (!in_array($section->getValue(), $field_value)) {
					continue;
				}
				
				$sections[] = $section;
			}
			
			return $sections;
		}
		
		if (isset($this->conditional_sections_objects)) {
			return $this->conditional_sections_objects;
		}
		
		$this->conditional_sections_objects = [];
		foreach ($this->conditional_sections as $section) {
			$this->conditional_sections_objects[] = new ConditionalSection($section);
		}
		
		return $this->conditional_sections_objects;
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
	 * Get the custom error message related to the validation rule
	 *
	 * @return void|string
	 */
	protected function getCustomErrorMessage() {
		
		$rule = $this->getValidationRule();
		if (empty($rule)) {
			return;
		}
		
		if (!in_array($this->getType(), elgg_extract('input_types', $rule))) {
			return;
		}
		
		return elgg_extract('error_message', $rule);
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
	 * @return false|array
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
	
	/**
	 * Fill the field from its input submitted value
	 *
	 * @return void
	 */
	public function populateFromInput() {
		
		foreach ($this->getConditionalSections() as $conditional_section) {
			$conditional_section->populateFromInput();
		}
		
		switch ($this->getType()) {
			case 'file':
				$uploaded_files = elgg_get_uploaded_files($this->getName());
				if (empty($uploaded_files)) {
					break;
				}
				
				$this->file_info = $uploaded_files;
				$paths = [];
				foreach ($uploaded_files as $file) {
					if (!$file->isValid()) {
						continue;
					}
					
					$paths[] = $file->getPathname();
				}
				
				if (empty($paths)) {
					// all errors
					break;
				}
				
				if (count($paths) === 1) {
					$paths = $paths[0];
				}
				
				$this->setValue($paths);
				break;
			case 'hidden':
				// hidden values are pre-programmed
				$this->setValue(elgg_extract('value', $this->config));
				break;
			default:
				$this->setValue(get_input($this->getName()));
				break;
		}
	}
	
	/**
	 * Set the value for this input field
	 *
	 * @param mixed $value the new value
	 *
	 * @return void
	 */
	public function setValue($value) {
		$this->value = $value;
	}
	
	/**
	 * Get the value of this field
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * Get the uploaded files
	 *
	 * @param bool $only_valid_files only return valid files
	 *
	 * @return false|\Symfony\Component\HttpFoundation\File\UploadedFile[]
	 */
	public function getUploadedFiles($only_valid_files = false) {
		
		if ($this->getType() !== 'file' || empty($this->file_info)) {
			return false;
		}
		
		if (!$only_valid_files) {
			return $this->file_info;
		}
		
		$result = [];
		foreach ($this->file_info as $uploaded_file) {
			if (!$uploaded_file->isValid()) {
				continue;
			}
			
			$result[] = $uploaded_file;
		}
		
		return $result;
	}
	
	/**
	 * Validate if the field value is valid according to the configuration
	 *
	 * @throws InvalidInputException
	 * @return void
	 */
	public function validate() {
		
		$this->validateValue();
		
		$this->validateRequired();
		
		$this->validatePattern();
	}
	
	/**
	 * Validate if the value matches the input type
	 *
	 * @throws InvalidInputException
	 * @return void
	 */
	protected function validateValue() {
		
		if (!isset($this->value) || $this->value === '') {
			return;
		}
		
		switch ($this->getType()) {
			case 'email':
				if (!is_email_address($this->value)) {
					throw new InvalidInputException(elgg_echo('forms:invalid_input_exception:value:email'));
				}
				break;
			case 'number':
				if (!is_numeric($this->value)) {
					throw new InvalidInputException(elgg_echo('forms:invalid_input_exception:value:number'));
				}
				break;
		}
	}
	
	/**
	 * Validate if the field is required
	 *
	 * @throws InvalidInputException
	 * @return void
	 */
	protected function validateRequired() {
		
		if ($this->getType() === 'hidden') {
			// hidden fields can't be required
			return;
		}
		
		$required = (bool) elgg_extract('required', $this->config, false);
		if (!$required) {
			return;
		}
		
		if (isset($this->value) && ($this->value !== '')) {
			return;
		}
		
		throw new InvalidInputException(elgg_echo('forms:invalid_input_exception:required'));
	}
	
	/**
	 * Validate the regex pattern on the field
	 *
	 * @throws InvalidInputException
	 * @return void
	 */
	protected function validatePattern() {
		
		// pattern only applies to non-empty fields (eg same as HTML5)
		if (!isset($this->value) || $this->value === '') {
			return;
		}
		
		$pattern = $this->getPattern();
		if (empty($pattern)) {
			return;
		}
		
		if (preg_match('/' . $pattern . '/', $this->value)) {
			return;
		}
		
		$error_message = $this->getCustomErrorMessage();
		if (empty($error_message)) {
			$error_message = elgg_echo('forms:invalid_input_exception:pattern');
		}
		
		throw new InvalidInputException($error_message);
	}
}
