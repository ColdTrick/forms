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
