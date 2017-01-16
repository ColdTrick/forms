<?php

namespace ColdTrick\Forms\Definition;

class Page {
	
	/**
	 * @var array the page configuration
	 */
	protected $config;
	
	/**
	 * @var \ColdTrick\Forms\Definition\Section[] all the sections on this page
	 */
	protected $sections;
	
	/**
	 * Create a page based on a config
	 *
	 * @param array $config
	 */
	public function __construct($config) {

		$this->config = $config;
	}
	
	/**
	 * Get the page title
	 *
	 * @return string
	 */
	public function getTitle() {
		return elgg_extract('title', $this->config, '');
	}
	
	/**
	 * Get the sections on this page
	 *
	 * @return \ColdTrick\Forms\Definition\Section[]
	 */
	public function getSections() {
		
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
	public function getValidationRules() {
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
	public function populateFromInput() {
		
		foreach ($this->getSections() as $section) {
			$section->populateFromInput();
		}
	}
}
