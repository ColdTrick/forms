<?php

namespace ColdTrick\Forms;

class Access {
	
	/**
	 * Change the write access array for forms
	 *
	 * @param string $hook
	 * @param string $type
	 * @param array $return_value
	 * @param array $params
	 *
	 * @return void|array
	 */
	public static function formWriteAccess($hook, $type, $return_value, $params) {
		
		if (!is_array($return_value)) {
			return;
		}
		
		$input_params = elgg_extract('input_params', $params);
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
