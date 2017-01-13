<?php

namespace ColdTrick\Forms;

class Definition {
	
	protected $config;

	protected $form;
	
	public function __construct(\Form $form) {
		
		$this->form = $form;
		$this->config = json_decode($form->definition, true);
	}
		
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
