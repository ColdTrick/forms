<?php

namespace ColdTrick\Forms;

class EntityMenu {
	
	/**
	 * Add menu items to the form entity menu
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return value
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerForm($hook, $type, $return_value, $params) {
		
		$entity = elgg_extract('entity', $params);
		if (!($entity instanceof \Form) || !$entity->canEdit()) {
			return;
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'compose',
			'text' => elgg_echo('forms:entity_menu:compose'),
			'href' => "forms/compose/{$entity->getGUID()}",
		]);
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'export',
			'text' => elgg_echo('export'),
			'href' => "action/forms/export?guid={$entity->getGUID()}",
			'is_action' => true,
		]);
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'copy',
			'text' => elgg_echo('forms:entity_menu:copy'),
			'href' => "action/forms/copy?guid={$entity->getGUID()}",
			'confirm' => elgg_echo('forms:entity_menu:copy:confirm'),
		]);
		
		return $return_value;
	}
}