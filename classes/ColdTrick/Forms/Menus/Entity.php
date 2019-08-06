<?php

namespace ColdTrick\Forms\Menus;

class Entity {
	
	/**
	 * Add menu items to the form entity menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerForm(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!($entity instanceof \Form) || !$entity->canEdit()) {
			return;
		}
		
		$return_value = $hook->getValue();
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'compose',
			'icon' => 'list',
			'text' => elgg_echo('forms:entity_menu:compose'),
			'href' => elgg_generate_entity_url($entity, 'compose'),
		]);
		
		if ($entity->hasDefinition()) {
			$return_value[] = \ElggMenuItem::factory([
				'name' => 'export',
				'icon' => 'download',
				'text' => elgg_echo('export'),
				'href' => elgg_generate_action_url('forms/definition/export', ['guid' => $entity->guid]),
				'is_action' => true,
			]);
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'copy',
			'icon' => 'copy',
			'text' => elgg_echo('forms:entity_menu:copy'),
			'href' => elgg_generate_action_url('forms/copy', ['guid' => $entity->guid]),
			'confirm' => elgg_echo('forms:entity_menu:copy:confirm'),
		]);
		
		return $return_value;
	}
}
