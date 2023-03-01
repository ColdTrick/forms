<?php

namespace ColdTrick\Forms\Forms;

/**
 * Prepare the fields for the forms/edit form
 */
class PrepareFields {
	
	/**
	 * Prepare fields
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'forms/edit'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$vars = $event->getValue();
		
		// input names => defaults
		$values = [
			'title' => null,
			'description' => null,
			'friendly_url' => null,
			'thankyou' => null,
			'access_id' => ACCESS_PRIVATE,
			'endpoint' => 'email',
			'endpoint_config' => [],
			'container_guid' => elgg_get_page_owner_guid(),
			'guid' => null,
		];
		
		$form = elgg_extract('entity', $vars);
		if ($form instanceof \Form) {
			foreach (array_keys($values) as $field) {
				switch ($field) {
					case 'endpoint_config':
						$values[$field] = $form->getEndpointConfig();
						break;
					default:
						if (isset($form->$field)) {
							$values[$field] = $form->$field;
						}
						break;
				}
			}
		}
		
		return array_merge($values, $vars);
	}
}
