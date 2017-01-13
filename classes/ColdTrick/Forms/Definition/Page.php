<?php

namespace ColdTrick\Forms\Definition;

class Page {
	
	protected $config;
	
	public function __construct($config) {

		$this->config = $config;
	}
	
	public function getTitle() {
		return elgg_extract('title', $this->config, '');
	}
		
	public function getSections() {
		$result = [];
		
		$sections = elgg_extract('sections', $this->config, []);
		foreach ($sections as $section) {
			$result[] = new Section($section);
		}
		return $result;
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
}
