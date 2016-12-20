<?php

namespace ColdTrick\Forms;

class PageHandler {
	
	public const HANDLERS = [
		'all',
		'add',
	];
	
	/**
	 * Handler /forms pages
	 *
	 * @param string[] $page the url segments
	 *
	 * @return bool
	 */
	public static function forms($page) {
		
		switch (elgg_extract(0, $page)) {
			case 'all':
				echo elgg_view_resource('forms/all');
				return true;
				break;
			case 'add':
				
				echo elgg_view_resource('forms/edit', [
					'container_guid' => (int) elgg_extract(1, $page),
				]);
				return true;
				break;
		}
		
		return false;
	}
	
	/**
	 * Rewrite /forms/form-name to a workable page handler
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param mixed  $return_value current return value
	 * @param miixed $params       supplied params
	 *
	 * @return void|mixed
	 */
	public static function routeRewrite($hook, $type, $return_value, $params) {
		
	}
}
