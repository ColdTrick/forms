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
	 * @return void|array
	 */
	public static function formWriteAccess(\Elgg\Event $event) {
		$return_value = $event->getValue();
		if (!is_array($return_value)) {
			return;
		}
		
		$input_params = $event->getParam('input_params');
		if (empty($input_params) || !is_array($input_params)) {
			return;
		}
		
		$entity_type = elgg_extract('entity_type', $input_params);
		$subtype = elgg_extract('entity_subtype', $input_params);
		if (($entity_type !== 'object') || ($subtype !== \Form::SUBTYPE)) {
			return;
		}
		
		unset($return_value[ACCESS_FRIENDS]);
		
		return $return_value;
	}
}
