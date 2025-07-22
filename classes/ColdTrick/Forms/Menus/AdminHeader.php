<?php

namespace ColdTrick\Forms\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add menu items to the admin_header menu
 */
class AdminHeader {
	
	/**
	 * Add menu items
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return null|MenuItems
	 */
	public function __invoke(\Elgg\Event $event): ?MenuItems {
		if (!elgg_is_admin_logged_in()) {
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'forms',
			'text' => elgg_echo('forms:menu:admin_header:manage'),
			'href' => elgg_generate_url('default:object:form'),
			'parent_name' => 'utilities',
		]);
		
		return $result;
	}
}
