<?php

namespace ColdTrick\Forms\Menus;

class Page {
	
	/**
	 * Add a menu item to the page menu in forms
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerValidationRules($hook, $type, $return_value, $params) {
		
		if (!elgg_in_context('forms') || !elgg_is_logged_in()) {
			return;
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'validation_rules',
			'text' => elgg_echo('forms:page_menu:validation_rules'),
			'href' => 'forms/validation_rules',
		]);
		
		return $return_value;
	}
}
