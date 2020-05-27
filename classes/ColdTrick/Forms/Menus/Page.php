<?php

namespace ColdTrick\Forms\Menus;

class Page {
	
	/**
	 * Add a menu item to the page menu in forms
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerValidationRules(\Elgg\Hook $hook) {
		
		if (!elgg_in_context('forms') || elgg_in_context('compose')) {
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggGroup) {
			$page_owner = elgg_get_site_entity();
		}
		
		if (!$page_owner->canWriteToContainer(0, 'object', 'form')) {
			return;
		}
		
		$return_value = $hook->getValue();
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'forms',
			'text' => elgg_echo('forms:page_menu:all'),
			'href' => elgg_generate_url('collection:object:form:all'),
		]);
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'validation_rules',
			'text' => elgg_echo('forms:page_menu:validation_rules'),
			'href' => elgg_generate_url('collection:validation_rules'),
		]);
		
		return $return_value;
	}
}
