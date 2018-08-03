<?php

$default_options = [
	'' => elgg_echo('forms:compose:field:edit:default_value:none'),
	'name' => elgg_echo('name'),
	'username' => elgg_echo('username'),
	'email' => elgg_echo('email'),
];

$profile_fields = elgg_get_config('profile_fields');
if (!empty($profile_fields)) {
	foreach ($profile_fields as $metadata_name => $type) {
		$label = $metadata_name;
		if (elgg_language_key_exists("profile:{$metadata_name}")) {
			$label = elgg_echo("profile:{$metadata_name}") . " ({$metadata_name})";
		}
		$default_options[$metadata_name] = $label;
	}
}

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
			'email' => elgg_echo('forms:compose:field:type:email'),
			'number' => elgg_echo('forms:compose:field:type:number'),
			'plaintext' => elgg_echo('forms:compose:field:type:plaintext'),
			'longtext' => elgg_echo('forms:compose:field:type:longtext'),
			'checkbox' => elgg_echo('forms:compose:field:type:checkbox'),
			'checkboxes' => elgg_echo('forms:compose:field:type:checkboxes'),
			'radio' => elgg_echo('forms:compose:field:type:radio'),
			'select' => elgg_echo('forms:compose:field:type:select'),
			'file' => elgg_echo('forms:compose:field:type:file'),
			'date' => elgg_echo('forms:compose:field:type:date'),
			'hidden' => elgg_echo('forms:compose:field:type:hidden'),
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
	
	[
		'#type' => 'validation_rule',
		'#label' => elgg_echo('forms:compose:field:edit:validation_rule'),
		'name' => 'validation_rule',
		'show_for_types' => ['text'],
	],
	
	[
		'#type' => 'text',
		'#label' => elgg_echo('forms:compose:field:edit:value'),
		'name' => 'value',
		'show_for_types' => ['hidden'],
	],
	
	[
		'#type' => 'radio',
		'#label' => elgg_echo('forms:compose:field:edit:email_recipient'),
		'name' => 'email_recipient',
		'options' => [
			elgg_echo('forms:compose:field:edit:email_recipient:none') => '',
			elgg_echo('forms:compose:field:edit:email_recipient:to') => 'to',
			elgg_echo('forms:compose:field:edit:email_recipient:cc') => 'cc',
			elgg_echo('forms:compose:field:edit:email_recipient:bcc') => 'bcc',
		],
		'value' => '',
		'align' => 'horizontal',
		'show_for_types' => ['email', 'hidden'],
	],
	
	[
		'#type' => 'checkbox',
		'#label' => elgg_echo('forms:compose:field:edit:select:multiple'),
		'name' => 'multiple',
		'default' => '',
		'value' => '1',
		'show_for_types' => ['select'],
	],
	
	[
		'#type' => 'select',
		'#label' => elgg_echo('forms:compose:field:edit:default_value'),
		'#help' => elgg_echo('forms:compose:field:edit:default_value:help'),
		'name' => 'default_value',
		'options_values' => $default_options,
		'show_for_types' => ['text', 'email', 'number', 'plaintext', 'longtext', 'select'],
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
