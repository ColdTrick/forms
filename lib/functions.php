<?php
/**
 * All helper functions are bundled here
 */

use Elgg\Database\QueryBuilder;

/**
 * Prepare the create/edit vars for the form
 *
 * @param int  $container_guid the container_guid for the entity
 * @param Form $entity         (optional) the entity to edit
 *
 * @return array
 */
function forms_prepare_form_vars(int $container_guid, Form $entity = null) {
	
	// defaults
	$vars = [
		'title' => '',
		'friendly_url' => '',
		'description' => '',
		'thankyou' => '',
		'access_id' => ACCESS_PRIVATE,
		'container_guid' => (int) $container_guid,
		'endpoint' => 'email',
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
function forms_is_valid_friendly_url(string $friendly_url, int $entity_guid = null): bool {
	
	if (empty($friendly_url)) {
		return false;
	}
	
	if (in_array($friendly_url, \ColdTrick\Forms\Controllers\FriendlyForm::HANDLERS)) {
		return false;
	}
	
	return elgg_call(ELGG_IGNORE_ACCESS|ELGG_SHOW_DISABLED_ENTITIES, function() use ($entity_guid, $friendly_url) {
		$options = [
			'type' => 'object',
			'subtype' => Form::SUBTYPE,
			'metadata_name_value_pairs' => [
				'friendly_url' => $friendly_url,
			],
			'wheres' => [],
		];
		
		if (!empty($entity_guid)) {
			$options['wheres'][] = function (QueryBuilder $qb, $main_alias) use ($entity_guid) {
				return $qb->compare("{$main_alias}.guid", '!=', $entity_guid, ELGG_VALUE_GUID);
			};
		}
		return empty(elgg_count_entities($options));
	});
}

/**
 * Generate a valid(unique) friendly url
 *
 * @param string $friendly_url the base to start from
 * @param int    $entity_guid  (optional) the entity to generate for
 *
 * @return false|string
 */
function forms_generate_valid_friendly_url(string $friendly_url, int $entity_guid = null) {
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

/**
 * Get the available endpoints information
 *
 * @return array
 */
function forms_get_available_endpoints(): array {
	$result = [
		'csv' => [
			'class' => '\ColdTrick\Forms\Endpoint\Csv',
			'definition' => '\ColdTrick\Forms\Definition\Csv',
		],
		'email' => [
			'class' => '\ColdTrick\Forms\Endpoint\Email',
		],
	];
	
	return elgg_trigger_plugin_hook('endpoints', 'forms', $result, $result);
}

/**
 * Get the vaidation rule definitions
 *
 * @return array
 */
function forms_get_validation_rules(): array {
	$rules = elgg_get_plugin_setting('validation_rules', 'forms');
	return empty($rules) ? [] : json_decode($rules, true);}

/**
 * Get the vaidation rule definitions
 *
 * @internal
 * @return bool
 */
function forms_save_validation_rules(array $rules = []): bool {
	$plugin = elgg_get_plugin_from_id('forms');
	if (empty($rules)) {
		return $plugin->unsetSetting('validation_rules');
	}
	
	return $plugin->setSetting('validation_rules', json_encode($rules));
}

/**
 * Get a validation rule
 *
 * @param string $rule_name the name of the rule to get
 *
 * @return false|array
 */
function forms_get_validation_rule(string $rule_name) {
	return elgg_extract($rule_name, forms_get_validation_rules(), false);
}
