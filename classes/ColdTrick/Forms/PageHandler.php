<?php

namespace ColdTrick\Forms;

class PageHandler {
	
	const HANDLERS = [
		'all',
		'add',
		'edit',
		'view',
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
			case 'view':
				echo elgg_view_resource('forms/view', [
					'guid' => (int) elgg_extract(1, $page),
				]);
				return true;
				break;
			case 'add':
				
				echo elgg_view_resource('forms/edit', [
					'container_guid' => (int) elgg_extract(1, $page),
				]);
				return true;
				break;
			case 'edit':
				
				echo elgg_view_resource('forms/edit', [
					'guid' => (int) elgg_extract(1, $page),
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
	 * @param mixed  $params       supplied params
	 *
	 * @return void|mixed
	 */
	public static function routeRewrite($hook, $type, $return_value, $params) {
		if (in_array($type, self::HANDLERS)) {
			return;
		}
		
		$segments = elgg_extract('segments', $return_value);
		if (empty($segments)) {
			return;
		}
		
		$friendly_url = elgg_extract(0, $segments);
		if (empty($friendly_url)) {
			return;
		}
		
		$entities = elgg_get_entities_from_metadata([
			'type' => 'object',
			'subtype' => \Form::SUBTYPE,
			'limit' => 1,
			'metadata_name_value_pairs' => ['friendly_url' => $friendly_url],
			'metadata_case_sensitive' => false,
		]);
			
		if (empty($entities)) {
			return;
		}
		
		$entity = $entities[0];
		
		$return_value['segments'] = ['view', $entity->getGUID()];

		return $return_value;
	}
}
