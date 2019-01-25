<?php

namespace ColdTrick\Forms;

class PageHandler {
	
	const HANDLERS = [
		'all',
		'add',
		'edit',
		'view',
		'compose',
		'validation_rules',
		'thankyou',
	];
		
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
		
		$segments = elgg_extract('segments', $return_value);
		if (empty($segments)) {
			return;
		}
		
		$friendly_url = elgg_extract(0, $segments);
		if (empty($friendly_url)) {
			return;
		}
		
		if (in_array($friendly_url, self::HANDLERS)) {
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
