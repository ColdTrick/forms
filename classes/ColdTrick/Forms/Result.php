<?php

namespace ColdTrick\Forms;

use ColdTrick\Forms\Definition\Field;
use ColdTrick\Forms\Exception\InvalidInputException;

/**
 * This class contains the values of a completed form
 */
class Result extends Definition {
	
	/**
	 * Create a form result
	 *
	 * @param \Form $form the form for this result
	 */
	public function __construct(\Form $form) {
		parent::__construct($form);
		
		$this->populateFromInput();
	}
	
	/**
	 * Fill all the fields from their input submitted value
	 *
	 * @return void
	 */
	protected function populateFromInput() {
		
		foreach ($this->getPages() as $page) {
			$page->populateFromInput();
		}
	}
	
	/**
	 * Validate a submitted form
	 *
	 * @return bool
	 */
	public function validate() {
		$result = true;
		
		// pages
		foreach ($this->getPages() as $page) {
			// sections
			foreach ($page->getSections() as $section) {
				// fields
				foreach ($section->getFields() as $field) {
					$result &= $this->validateField($field);
					
					// only validate applied conditional sections
					foreach ($field->getConditionalSections(true) as $conditional_section) {
						// fields in conditional sections
						foreach ($conditional_section->getFields() as $conditional_field) {
							$result &= $this->validateField($conditional_field);
						}
					}
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Validate one field
	 *
	 * @param Field $field the field to validate
	 * @return bool
	 */
	protected function validateField(Field $field) {
		
		try {
			$field->validate();
		} catch (InvalidInputException $e) {
			register_error(elgg_echo('forms:result:validate:error', [$field->getLabel(), $e->getMessage()]));
			return false;
		}
		
		return true;
	}
}
