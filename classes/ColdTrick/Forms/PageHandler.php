<?php

namespace ColdTrick\Forms;

class PageHandler {
	
	/**
	 * Handler /forms pages
	 *
	 * @param string[] $page the url segments
	 *
	 * @return bool
	 */
	public static function forms($page) {
		
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
