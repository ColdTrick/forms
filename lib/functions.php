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
		'endpoint' => '',
		'endpoint_config' => [],
	];
	
	// from entity
	if ($entity instanceof Form) {
		foreach ($vars as $name => $value) {
			
			switch ($name) {
				case 'endpoint_config':
					$vars[$name] = $entity->getEndpointConfig();
					break;
				default:
					$vars[$name] = $entity->$name;
					break;
			}
		}
		
		$vars['entity'] = $entity;
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
 * @param int    $entity_guid  if editing don't compare with same entity
 *
 * @return bool
 */
function forms_is_valid_friendly_url($friendly_url, $entity_guid = null) {
	
	if (empty($friendly_url) || !is_string($friendly_url)) {
		return false;
	}
	
	if (in_array($friendly_url, \ColdTrick\Forms\PageHandler::HANDLERS)) {
		return false;
	}
	
	// ignore access and include hidden (disabled) entities
	$ia = elgg_set_ignore_access(true);
	$hidden = access_show_hidden_entities(true);
	
	$options = [
		'type' => 'object',
		'subtype' => Form::SUBTYPE,
		'count' => true,
		'metadata_name_value_pairs' => [
			'friendly_url' => $friendly_url,
		],
	];
	
	if (!empty($entity_guid)) {
		$entity_guid = (int) $entity_guid;
		$options['wheres'] = "e.guid != {$entity_guid}";
	}
	
	$count = elgg_get_entities_from_metadata($options);
	
	// restore access/hidden
	elgg_set_ignore_access($ia);
	access_show_hidden_entities($hidden);
	
	return empty($count);
}

/**
 * Generate a valid(unique) friendly url
 *
 * @param string $friendly_url the base to start from
 * @param int    $entity_guid  (optional) the entity to generate for
 *
 * @return false|string
 */
function forms_generate_valid_friendly_url($friendly_url, $entity_guid = null) {
	
	if (empty($friendly_url)) {
		return false;
	}
	
	if (forms_is_valid_friendly_url($friendly_url, $entity_guid)) {
		return $friendly_url;
	}
	
	$i = 2;
	while (!forms_is_valid_friendly_url("{$friendly_url}-{$i}", $entity_guid)) {
		$i++;
	}
	
	return "{$friendly_url}-{$i}";
}
