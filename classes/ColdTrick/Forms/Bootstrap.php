<?php

namespace ColdTrick\Forms;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
	
	const HANDLERS = [
		'all',
		'add',
		'edit',
		'view',
		'compose',
		'validation_rules',
		'thankyou',
	];
	
	/**
	 * {@inheritdoc}
	 */
	public function init() {
		$this->initViews();
	}

	/**
	 * Init views
	 *
	 * @return void
	 */
	protected function initViews() {
		elgg_register_ajax_view('form/friendly_title');
		elgg_register_ajax_view('forms/forms/validation_rules/edit');
		elgg_register_ajax_view('forms/forms/definition/import');
	}
}
