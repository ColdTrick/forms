<?php

namespace ColdTrick\Forms;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritdoc}
	 */
	public function init() {
		$this->initViews();
		$this->initRegisterHooks();
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
		
		elgg_extend_view('css/elgg', 'css/forms.css');
	}
	
	/**
	 * Register plugin hooks
	 *
	 * @return void
	 */
	protected function initRegisterHooks() {
		$hooks = $this->elgg()->hooks;
		
		$hooks->registerHandler('access:collections:write', 'user', '\ColdTrick\Forms\Access::formWriteAccess');
		$hooks->registerHandler('register', 'menu:entity', '\ColdTrick\Forms\Menus\Entity::registerForm');
		$hooks->registerHandler('register', 'menu:page', '\ColdTrick\Forms\Menus\Page::registerValidationRules');
		$hooks->registerHandler('register', 'menu:validation_rule', '\ColdTrick\Forms\Menus\ValidationRule::registerEdit');
		$hooks->registerHandler('route:rewrite', 'forms', '\ColdTrick\Forms\PageHandler::routeRewrite');
	}
}
