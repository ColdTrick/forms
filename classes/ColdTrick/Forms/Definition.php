<?php

namespace ColdTrick\Forms;

/**
 * Form Definition
 */
class Definition {
	
	/**
	 * @var array the definition configuration
	 */
	protected array $config;

	/**
	 * @var \ColdTrick\Forms\Definition\Page[] the pages in this definition
	 */
	protected array $pages;
	
	/**
	 * Make a new definition
	 *
	 * @param \Form $form the form
	 */
	public function __construct(protected \Form $form) {
		$this->config = json_decode($form->definition ?? '', true) ?? [];
	}
	
	/**
	 * Get the form for this result
	 *
	 * @return \Form
	 */
	public function getForm(): \Form {
		return $this->form;
	}
	
	/**
	 * Get the definition pages
	 *
	 * @return \ColdTrick\Forms\Definition\Page[]
	 */
	public function getPages(): array {
		if (isset($this->pages)) {
			return $this->pages;
		}
		
		$this->pages = [];
		
		$pages = elgg_extract('pages', $this->config, []);
		foreach ($pages as $page) {
			$this->pages[] = new Definition\Page($page);
		}
		
		return $this->pages;
	}
	
	/**
	 * Get all the applied validation rules for this definition
	 *
	 * @return array
	 */
	public function getValidationRules(): array {
		$result = [];
		
		foreach ($this->getPages() as $page) {
			$result = array_merge($result, $page->getValidationRules());
		}
		
		return $result;
	}
	
	/**
	 * Check if a definition is valid
	 *
	 * @return bool
	 */
	public function isValid(): bool {
		return true;
	}
	
	/**
	 * When a definition isn't valid use this function to get the validation errors
	 *
	 * @return string[]
	 */
	public function getValidationErrors(): array {
		return [];
	}
}
