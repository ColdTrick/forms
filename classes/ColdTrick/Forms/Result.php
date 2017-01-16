<?php

namespace ColdTrick\Forms;

/**
 * This class contains the values of a completed form
 */
class Result extends Definition {
	
	/**
	 * Create a form result
	 *
	 * @param Form $form the form for this result
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
}
