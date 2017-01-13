<?php

namespace ColdTrick\Forms\Menus;

class ValidationRule {
	
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
	public static function registerEdit($hook, $type, $return_value, $params) {
		
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$rule = elgg_extract('rule', $params);
		if (empty($rule)) {
			return;
		}
		
		$name = elgg_extract('name', $rule);
		
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
			'href' => "action/forms/validation_rules/delete?name={$name}",
			'confirm' => elgg_echo('deleteconfirm'),
			'priority' => 300,
		]);
		
		return $return_value;
	}
}
