<?php
/**
 * All helper functions are bundled here
 */

use Elgg\Database\QueryBuilder;

/**
 * Check if a friendly url exists and is valid for use
 *
 * @param string $friendly_url the string to test
 * @param int    $entity_guid  if editing don't compare with same entity
 *
 * @return bool
 */
function forms_is_valid_friendly_url(string $friendly_url, int $entity_guid = 0): bool {
	if (empty($friendly_url)) {
		return false;
	}
	
	if (in_array($friendly_url, \ColdTrick\Forms\Controllers\FriendlyForm::HANDLERS)) {
		return false;
	}
	
	return elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($entity_guid, $friendly_url) {
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
 * @return null|string
 */
function forms_generate_valid_friendly_url(string $friendly_url, int $entity_guid = 0): ?string {
	if (empty($friendly_url)) {
		return null;
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
	
	return elgg_trigger_event_results('endpoints', 'forms', $result, $result);
}

/**
 * Get the validation rule definitions
 *
 * @return array
 */
function forms_get_validation_rules(): array {
	$rules = elgg_get_plugin_setting('validation_rules', 'forms');
	return empty($rules) ? [] : json_decode($rules, true);
}

/**
 * Get the validation rule definitions
 *
 * @param array $rules validation rules
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
 * @return null|array
 */
function forms_get_validation_rule(string $rule_name): ?array {
	return elgg_extract($rule_name, forms_get_validation_rules());
}
