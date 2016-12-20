<?php

namespace ColdTrick\Forms;

class Definition {
	
	protected $config;
	
	public function __construct(\Form $form) {
		$file_contents = '{}';
		
		$config = json_decode($file_contents, true);
		
		$this->config = [
			'fields' => [
				[
					'#type' => 'text',
					'#label' => 'Label of field',
					'name' => 'field_1'
				],
			],
		];
	}
	
	public function setConfig($config) {
		$this->config = $config;
	}

	public function getConfig() {
		return $this->config;
	}
	
	public function getFields() {
		return elgg_extract('fields', $this->getConfig(), []);
	}
}
