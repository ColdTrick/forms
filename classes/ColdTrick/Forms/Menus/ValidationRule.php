<?php

namespace ColdTrick\Forms\Menus;

class ValidationRule {
	
	/**
	 * Add a menu item to the page menu in forms
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:validation_rule'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerEdit(\Elgg\Hook $hook) {
		
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$rule = $hook->getParam('rule');
		if (empty($rule)) {
			return;
		}
		
		$name = elgg_extract('name', $rule);
		
		$return_value = $hook->getValue();
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "ajax/form/forms/validation_rules/edit?name={$name}",
			'deps' => 'elgg/lightbox',
			'link_class' => 'elgg-lightbox',
			'priority' => 200,
		]);
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'text' => elgg_view_icon('delete'),
			'title' => elgg_echo('edit'),
			'href' => elgg_generate_action_url('forms/validation_rules/delete', ['name' => $name]),
			'confirm' => elgg_echo('deleteconfirm'),
			'priority' => 300,
		]);
		
		return $return_value;
	}
}
