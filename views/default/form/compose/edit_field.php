<?php

$edit_field = elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('forms:compose:field:label'),
	'name' => 'title',
]);

$edit_field .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('forms:compose:field:type'),
	'name' => 'type',
	'options_values' => [
		'text' => elgg_echo('type:text'),
		'longtext' => elgg_echo('type:longtext'),
		'checkbox' => elgg_echo('type:checkbox'),
	],
]);

$edit_field .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('forms:compose:field:required'),
	'name' => 'required',
	'value' => 1,
]);


$edit_field .= elgg_view_field([
	'#type' => 'button',
	'class' => 'elgg-button-action forms-compose-field-save',
	'value' => elgg_echo('save'),
]);



echo elgg_format_element('div', [
	'class' => 'hidden forms-compose-edit-field',
	'id' => 'forms-compose-edit-field',
], $edit_field);
