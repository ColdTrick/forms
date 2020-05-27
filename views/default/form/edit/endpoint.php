<?php
/**
 * Bundle available endpoints for edit
 */

$available_endpoints = forms_get_available_endpoints();
if (empty($available_endpoints)) {
	return;
}

elgg_require_js('form/edit/endpoint');

$options_values = [];
$endpoints_config = '';
foreach ($available_endpoints as $endpoint => $config) {
	$label = $endpoint;
	if (elgg_language_key_exists("forms:edit:endpoint:{$endpoint}")) {
		$label = elgg_echo("forms:edit:endpoint:{$endpoint}");
	}
	
	$options_values[$endpoint] = $label;
	if (elgg_view_exists("form/edit/endpoint/{$endpoint}")) {
		$classes = [
			'forms-edit-endpoint',
			"forms-edit-endpoint-{$endpoint}",
		];
		if (elgg_extract('endpoint', $vars) !== $endpoint) {
			$classes[] = 'hidden';
		}
		
		$endpoints_config .= elgg_format_element('div', ['class' => $classes], elgg_view("form/edit/endpoint/{$endpoint}", $vars));
	}
}

$endpoints = '';

$endpoints .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('forms:edit:endpoint'),
	'#help' => elgg_echo('forms:edit:endpoint:help'),
	'id' => 'forms-edit-endpoint-selector',
	'name' => 'endpoint',
	'value' => elgg_extract('endpoint', $vars),
	'options_values' => $options_values,
]);

// available endpoints
$endpoints .= $endpoints_config;

echo elgg_format_element('div', ['class' => 'forms-edit-endpoints-wrapper'], $endpoints);
