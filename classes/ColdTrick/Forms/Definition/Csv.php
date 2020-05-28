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
	 */
	protected function validate() {
		$this->validation_errors = [];
		
		foreach ($this->getPages() as $page) {
			foreach ($page->getSections() as $section) {
				foreach ($section->getFields() as $field) {
					
					switch ($field->getType()) {
						case 'file':
							$this->validation_errors[] = elgg_echo('forms:definition:validation:error:csv:file', [$field->getLabel()]);
							break;
						default:
							// all is good
							continue(2);
					}
				}
			}
		}
	}
}
