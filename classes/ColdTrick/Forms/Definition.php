<?php

namespace ColdTrick\Forms;

class Definition {
	
	protected $config;

	protected $form;
	
	public function __construct(\Form $form) {
		
		$this->form = $form;
		$this->config = json_decode($form->definition, true);
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
