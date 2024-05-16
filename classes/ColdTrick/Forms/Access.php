<?php

namespace ColdTrick\Forms;

/**
 * Access related callbacks
 */
class Access {
	
	/**
	 * Change the write access array for forms
	 *
	 * @param \Elgg\Event $event 'access:collections:write', 'user'
	 *
	 * @return null|array
	 */
	public static function formWriteAccess(\Elgg\Event $event): ?array {
		$return_value = $event->getValue();
		if (!is_array($return_value)) {
			return null;
		}
		
		$input_params = $event->getParam('input_params');
		if (empty($input_params) || !is_array($input_params)) {
			return null;
		}
		
		$entity_type = elgg_extract('entity_type', $input_params);
		$subtype = elgg_extract('entity_subtype', $input_params);
		if (($entity_type !== 'object') || ($subtype !== \Form::SUBTYPE)) {
			return null;
		}
		
		unset($return_value[ACCESS_FRIENDS]);
		
		return $return_value;
	}
}
