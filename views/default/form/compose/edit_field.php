<?php

$fields = [
	[
		'#type' => 'text',
		'#label' => elgg_echo('forms:compose:field:edit:label'),
		'name' => '#label',
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('forms:compose:field:edit:help'),
		'name' => '#help',
	],
	[
		'#type' => 'checkbox',
		'#label' => elgg_echo('forms:compose:field:edit:required'),
		'name' => 'required',
		'value' => 1,
	],
	[
		'#type' => 'select',
		'#label' => elgg_echo('forms:compose:field:edit:type'),
		'name' => '#type',
		'options_values' => [
			'text' => elgg_echo('forms:compose:field:type:text'),
			'plaintext' => elgg_echo('forms:compose:field:type:plaintext'),
			'longtext' => elgg_echo('forms:compose:field:type:longtext'),
			'checkbox' => elgg_echo('forms:compose:field:type:checkbox'),
			'checkboxes' => elgg_echo('forms:compose:field:type:checkboxes'),
			'radio' => elgg_echo('forms:compose:field:type:radio'),
			'select' => elgg_echo('forms:compose:field:type:select'),
			'file' => elgg_echo('forms:compose:field:type:file'),
			'date' => elgg_echo('forms:compose:field:type:date'),
		],
	],
	
	// conditional fields here
	
	[
		'#type' => 'text',
		'#label' => elgg_echo('forms:compose:field:edit:options'),
		'#help' => elgg_echo('forms:compose:field:edit:options:help'),
		'name' => 'options',
		'show_for_types' => ['select', 'radio', 'checkboxes'],
	],
	
	
	
	// end of conditional fields
	
	[
		'#type' => 'button',
		'class' => 'elgg-button-action forms-compose-field-save',
		'value' => elgg_echo('save'),
	],
];


// set conditional view classes
foreach ($fields as $key => $field) {
	if (!isset($field['show_for_types'])) {
		continue;
	}
	
	$classes = ['hidden'];
	foreach ($field['show_for_types'] as $type) {
		$classes[] = "forms-field-for-$type";
	}
	
	$fields[$key]['#class'] = $classes;
}


echo elgg_view('input/fieldset', [
	'class' => 'hidden forms-compose-edit-field',
	'id' => 'forms-compose-edit-field',
	'fields' => $fields,
]);
