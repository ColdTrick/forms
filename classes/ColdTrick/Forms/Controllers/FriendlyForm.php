<?php

namespace ColdTrick\Forms\Controllers;

use Elgg\Http\ResponseBuilder;
use Elgg\Exceptions\Http\EntityNotFoundException;

class FriendlyForm {
	
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
	 * @param \Elgg\Request $request Request
	 *
	 * @return ResponseBuilder
	 *
	 * @throws EntityNotFoundException
	 */
	public function __invoke(\Elgg\Request $request) {
		
		$friendly_url = $request->getParam('title');
		if (empty($friendly_url)) {
			return;
		}
		
		if (in_array($friendly_url, self::HANDLERS)) {
			return;
		}
		
		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => \Form::SUBTYPE,
			'limit' => 1,
			'metadata_name_value_pairs' => ['friendly_url' => $friendly_url],
			'metadata_case_sensitive' => false,
		]);
		
		if (empty($entities)) {
			throw new EntityNotFoundException();
		}
		
		$entity = $entities[0];
		
		return elgg_ok_response(elgg_view_resource('forms/view', ['guid' => $entity->guid]));
	}
}
