<?php

namespace ColdTrick\Forms;

class Definition {
	
	/**
	 *
	 * @var array the definition configuration
	 */
	protected $config;

	/**
	 * @var Form The form for this definition
	 */
	protected $form;
	
	/**
	 * Make a new definition
	 *
	 * @param \Form $form
	 */
	public function __construct(\Form $form) {
		
		$this->form = $form;
		$this->config = json_decode($form->definition, true);
	}
	
	/**
	 * Get the form for this result
	 *
	 * @return \Form
	 */
	public function getForm() {
		return $this->form;
	}
	
	/**
	 * Get the definition pages
	 *
	 * @return \ColdTrick\Forms\Definition\Page[]
	 */
	public function getPages() {
		$result = [];
		
		$pages = elgg_extract('pages', $this->config, []);
		foreach ($pages as $page) {
			$result[] = new Definition\Page($page);
		}
		return $result;
	}
	
	/**
	 * Get all the applied validation rules for this definition
	 *
	 * @return array
	 */
	public function getValidationRules() {
		$result = [];
		
		foreach ($this->getPages() as $page) {
			$result = array_merge($result, $page->getValidationRules());
		}
		
		return $result;
	}
}
