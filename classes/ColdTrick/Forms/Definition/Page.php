<?php

namespace ColdTrick\Forms\Definition;

/**
 * Page of a Form
 */
class Page {
	
	/**
	 * @var \ColdTrick\Forms\Definition\Section[] all the sections on this page
	 */
	protected array $sections;
	
	/**
	 * Create a page based on a config
	 *
	 * @param array $config configuration
	 */
	public function __construct(protected array $config) {
	}
	
	/**
	 * Get the page title
	 *
	 * @return string
	 */
	public function getTitle(): string {
		return (string) elgg_extract('title', $this->config, '');
	}
	
	/**
	 * Get the sections on this page
	 *
	 * @return \ColdTrick\Forms\Definition\Section[]
	 */
	public function getSections(): array {
		if (isset($this->sections)) {
			return $this->sections;
		}
		
		$this->sections = [];
		
		$sections = elgg_extract('sections', $this->config, []);
		foreach ($sections as $section) {
			$this->sections[] = new Section($section);
		}
		
		return $this->sections;
	}
	
	/**
	 * Get all the applied validation rules for this page
	 *
	 * @return array
	 */
	public function getValidationRules(): array {
		$result = [];
		
		foreach ($this->getSections() as $section) {
			$result = array_merge($result, $section->getValidationRules());
		}
		
		return $result;
	}
	
	/**
	 * Fill all the fields from their input submitted value
	 *
	 * @return void
	 */
	public function populateFromInput(): void {
		foreach ($this->getSections() as $section) {
			$section->populateFromInput();
		}
	}
}
