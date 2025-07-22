<?php

namespace ColdTrick\Forms\Definition;

/**
 * Form Section
 */
class Section {
	
	/**
	 * @var \ColdTrick\Forms\Definition\Field[] the fields in this section
	 */
	protected array $fields;
	
	/**
	 * Create a new section
	 *
	 * @param array $config the section configuration
	 * @param \Form $form   the Form this section is a part of
	 */
	public function __construct(protected array $config, protected \Form $form) {
	}
	
	/**
	 * Get the section title
	 *
	 * @return null|string
	 */
	public function getTitle(): ?string {
		return elgg_extract('title', $this->config);
	}
	
	/**
	 * Get all the fields in this section
	 *
	 * @return \ColdTrick\Forms\Definition\Field[]
	 */
	public function getFields(): array {
		if (isset($this->fields)) {
			return $this->fields;
		}
		
		$this->fields = [];
		
		$fields = (array) elgg_extract('fields', $this->config);
		foreach ($fields as $field) {
			$this->fields[] = new Field($field, $this->form);
		}
		
		return $this->fields;
	}
	
	/**
	 * Get all the applied validation rules for this section
	 *
	 * @return array
	 */
	public function getValidationRules(): array {
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
	public function populateFromInput(): void {
		foreach ($this->getFields() as $field) {
			$field->populateFromInput();
		}
	}
}
