<?php

namespace ColdTrick\Forms;

/**
 * This class contains the values of a completed form
 */
class Result {
	
	/**
	 * @var \Form the form belonging to this result
	 */
	protected $form;
	
	/**
	 * Create a new result
	 *
	 * @param \Form $form the form related to this result
	 */
	public function __construct(\Form $form) {
		
		$this->form = $form;
	}
	
	/**
	 * Get the form for this result
	 *
	 * @return \Form
	 */
	public function getForm() {
		return $this->form;
	}
}
