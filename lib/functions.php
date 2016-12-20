<?php

/**
 * All helper functions are bundled here
 */

/**
 * Prepare the create/edit vars for the form
 *
 * @param int  $container_guid the container_guid for the entity
 * @param Form $entity         (optional) the entity to edit
 *
 * @return array
 */
function forms_prepare_form_vars($container_guid, $entity = null) {
	
	// defaults
	$vars = [
		'title' => '',
		'friendly_url' => '',
		'description' => '',
		'access_id' => ACCESS_PRIVATE,
		'container_guid' => (int) $container_guid,
	];
	
	// from entity
	if ($entity instanceof Form) {
		foreach ($vars as $name => $value) {
			$vars[$name] = $entity->$name;
		}
	}
	
	// from sticky form
	$sticky_values = elgg_get_sticky_values('forms/edit');
	if (!empty($sticky_values)) {
		$vars = array_merge($vars, $sticky_values);
		
		elgg_clear_sticky_form('forms/edit');
	}
	
	return $vars;
}

/**
 * Check if a friendly url exists and is valid for use
 *
 * @param string $friendly_url the string to test
 *
 * @return bool
 */
function forms_is_valid_friendly_url($friendly_url) {
	
	if (empty($friendly_url) || !is_string($friendly_url)) {
		return false;
	}
	
	if (in_array($friendly_url, \ColdTrick\Forms\PageHandler::HANDLERS)) {
		return false;
	}
	
	// ignore access and include hidden (disabled) entities
	$ia = elgg_set_ignore_access(true);
	$hidden = access_show_hidden_entities(true);
	
	$count = elgg_get_entities_from_metadata([
		'type' => 'object',
		'subtype' => Form::SUBTYPE,
		'count' => true,
		'metadata_name_value_pairs' => [
			'friendly_url' => $friendly_url,
		],
	]);
	
	// restore access/hidden
	elgg_set_ignore_access($ia);
	access_show_hidden_entities($hidden);
	
	return empty($count);
}
