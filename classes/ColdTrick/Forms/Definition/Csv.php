<?php

namespace ColdTrick\Forms\Definition;

use ColdTrick\Forms\Definition;

/**
 * Extension of the default definition to ensure certain field types aren't used
 */
class Csv extends Definition {

	/**
	 * @var string[]
	 */
	protected $validation_errors;
	
	/**
	 * {@inheritDoc}
	 */
	public function isValid() {
		if (!isset($this->validation_errors)) {
			$this->validate();
		}
		
		return empty($this->validation_errors);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getValidationErrors() {
		if (!isset($this->validation_errors)) {
			$this->validate();
		}
		
		return $this->validation_errors;
	}
	
	/**
	 * Validates the definition
	 *
	 * @return void
	 */
	protected function validate() {
		$this->validation_errors = [];
		
		foreach ($this->getPages() as $page) {
			foreach ($page->getSections() as $section) {
				foreach ($section->getFields() as $field) {
					$this->validateField($field);
					
					foreach ($field->getConditionalSections() as $conditional_section) {
						foreach ($conditional_section->getFields() as $conditional_field) {
							$this->validateField($conditional_field);
						}
					}
				}
			}
		}
	}
	
	/**
	 * Validate a single field on the form
	 *
	 * @param Field $field the field to check
	 *
	 * @return void
	 */
	protected function validateField(Field $field) {
		switch ($field->getType()) {
			case 'file':
				$this->validation_errors[] = elgg_echo('forms:definition:validation:error:csv:file', [$field->getLabel()]);
				break;
			default:
				// all is good
				break;
		}
	}
}
