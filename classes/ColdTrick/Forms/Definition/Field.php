<?php

namespace ColdTrick\Forms\Definition;

use ColdTrick\Forms\Exception\InvalidInputException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Form Field
 */
class Field {
	
	/**
	 * @var array the conditional section configuration
	 */
	protected array $conditional_sections;
	
	/**
	 * @var \ColdTrick\Forms\Definition\ConditionalSection[] the conditional sections
	 */
	protected array $conditional_sections_objects;
	
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
	 * @param array      $config the field configuration
	 * @param null|\Form $form   the Form this field is a part of
	 */
	public function __construct(protected array $config, protected ?\Form $form = null) {
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
	public function getType(): string {
		return elgg_extract('#type', $this->config, '');
	}
	
	/**
	 * Get the name of the field
	 *
	 * @return string
	 */
	public function getName(): string {
		return elgg_extract('name', $this->config, '');
	}
	
	/**
	 * Get the label of the field
	 *
	 * @return string
	 */
	public function getLabel(): string {
		return elgg_extract('#label', $this->config, '');
	}
	
	/**
	 * Get the input variables
	 *
	 * @param array $additional_vars additional input vars
	 *
	 * @return array
	 */
	public function getInputVars(array $additional_vars = []): array {
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
		
		// further cleanup
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
				unset($result['max_file_size']);
				$result['max_size'] = $this->getMaxFileSizeInBytes();
				
				// original max file size
				$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
				$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');
				$file_size = $result['max_size'] ?? max($post_max_size, $upload_max_filesize);
				$form_size = $this->form->getMaxFileSizeBytes() ?? max($post_max_size, $upload_max_filesize);
				
				$result['data-original-max-size'] = min($post_max_size, $upload_max_filesize, $file_size, $form_size);
				
				break;
		}
		
		if (!empty($options)) {
			$result[$options_key] = $options;
		}
		
		$result['pattern'] = $this->getPattern();
		unset($result['validation_rule']);

		$custom_error = $this->getCustomErrorMessage();
		if (!empty($custom_error)) {
			$result['oninvalid'] = 'this.setCustomValidity("' . $custom_error . '")';
		}
		
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
	public function getConfig(): array {
		return $this->config;
	}
	
	/**
	 * Get all the conditional sections for this field
	 *
	 * @param bool $apply_section_filter apply the conditional section filter
	 *
	 * @return \ColdTrick\Forms\Definition\ConditionalSection[]
	 */
	public function getConditionalSections(bool $apply_section_filter = false): array {
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
			$this->conditional_sections_objects[] = new ConditionalSection($section, $this->form);
		}
		
		return $this->conditional_sections_objects;
	}
	
	/**
	 * Create the options for use in the inputVars
	 *
	 * @return array
	 */
	protected function getOptions(): array {
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
	 * @return null|string
	 */
	protected function getPattern(): ?string {
		$rule = $this->getValidationRule();
		if (empty($rule)) {
			return null;
		}
		
		if (!in_array($this->getType(), elgg_extract('input_types', $rule))) {
			return null;
		}
		
		return elgg_extract('regex', $rule);
	}
	
	/**
	 * Get the custom error message related to the validation rule
	 *
	 * @return null|string
	 */
	protected function getCustomErrorMessage(): ?string {
		$rule = $this->getValidationRule();
		if (empty($rule)) {
			return null;
		}
		
		if (!in_array($this->getType(), elgg_extract('input_types', $rule))) {
			return null;
		}
		
		return elgg_extract('error_message', $rule);
	}
	
	/**
	 * Get all the applied validation rules for this field
	 *
	 * @return array
	 */
	public function getValidationRules(): array {
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
	 * @return null|array
	 */
	protected function getValidationRule(): ?array {
		$validation_rule = elgg_extract('validation_rule', $this->config);
		if (empty($validation_rule)) {
			return null;
		}
		
		return forms_get_validation_rule($validation_rule);
	}
	
	/**
	 * Fill the field from its input submitted value
	 *
	 * @return void
	 */
	public function populateFromInput(): void {
		foreach ($this->getConditionalSections() as $conditional_section) {
			$conditional_section->populateFromInput();
		}
		
		switch ($this->getType()) {
			case 'text_output':
				// field does not support input
				break;
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
	public function setValue(mixed $value): void {
		$this->value = $value;
	}
	
	/**
	 * Get the value of this field
	 *
	 * @return mixed
	 */
	public function getValue(): mixed {
		return $this->value;
	}
	
	/**
	 * Get the uploaded files
	 *
	 * @param bool $only_valid_files only return valid files
	 *
	 * @return null|\Symfony\Component\HttpFoundation\File\UploadedFile[]
	 */
	public function getUploadedFiles(bool $only_valid_files = false): ?array {
		if ($this->getType() !== 'file' || empty($this->file_info)) {
			return null;
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
	public function validate(): void {
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
	protected function validateValue(): void {
		if (!isset($this->value) || $this->value === '') {
			return;
		}
		
		switch ($this->getType()) {
			case 'email':
				if (!elgg_is_valid_email((string) $this->value)) {
					throw new InvalidInputException(elgg_echo('forms:invalid_input_exception:value:email'));
				}
				break;
			case 'number':
				if (!is_numeric($this->value)) {
					throw new InvalidInputException(elgg_echo('forms:invalid_input_exception:value:number') . var_export($this->value, true));
				}
				break;
			case 'file':
				$files = $this->file_info;
				$valid_files = [];
				foreach ($files as $file) {
					if (!$file->isValid()) {
						throw new InvalidInputException($file->getErrorMessage());
					}
					
					$valid_files[] = $file;
				}
				
				$max_file_size = $this->getMaxFileSizeInBytes();
				if (!empty($max_file_size)) {
					foreach ($valid_files as $file) {
						$file_size = $file->getSize();
						if ($file_size > $max_file_size) {
							throw new InvalidInputException(elgg_echo('upload:error:ini_size'));
						}
						
						$max_file_size -= $file_size;
					}
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
	protected function validateRequired(): void {
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
	protected function validatePattern(): void {
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
	
	/**
	 * Get the max file size in bytes
	 *
	 * @return int|null
	 */
	protected function getMaxFileSizeInBytes(): ?int {
		if ($this->getType() !== 'file') {
			return null;
		}
		
		$max_file_size = elgg_extract('max_file_size', $this->config);
		$form_max_size = $this->form->getMaxFileSizeBytes();
		if (empty($max_file_size) && empty($form_max_size)) {
			return null;
		}
		
		$php_upload_file_size = elgg_get_ini_setting_in_bytes('upload_max_filesize');
		
		if (empty($max_file_size)) {
			return min($form_max_size, $php_upload_file_size);
		}
		
		$matches = [];
		if (!preg_match_all('/^(\d+)([kmg]?)$/i', $max_file_size, $matches)) {
			return min($form_max_size, $php_upload_file_size);
		}
		
		$value = (int) $matches[1][0];
		if (isset($matches[2][0])) {
			switch (strtolower($matches[2][0])) {
				case 'g':
					$value *= 1024;
					// gigabytes
				case 'm':
					$value *= 1024;
					// megabytes
				case 'k':
					$value *= 1024;
					// kilobytes
					break;
			}
		}
		
		return empty($form_max_size) ? min($php_upload_file_size, $value) : min($php_upload_file_size, $form_max_size, $value);
	}
}
