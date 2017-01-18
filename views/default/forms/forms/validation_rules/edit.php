<?php

$name = get_input('name');
$rule = false;
if (!empty($name)) {
	$rule = forms_get_validation_rule($name);
}

if (empty($rule)) {
	$rule = [];
}

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('forms:validation_rule:label'),
	'#help' => elgg_echo('forms:validation_rule:label:help'),
	'name' => 'label',
	'value' => elgg_extract('label', $rule),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('forms:validation_rule:regex'),
	'#help' => elgg_echo('forms:validation_rule:regex:help'),
	'name' => 'regex',
	'value' => elgg_extract('regex', $rule),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('forms:validation_rule:error_message'),
	'#help' => elgg_echo('forms:validation_rule:error_message:help'),
	'name' => 'error_message',
	'value' => elgg_extract('error_message', $rule),
]);

// @todo make this selectable for the user
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'input_types[]',
	'value' => 'text',
]);

// footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
if (!empty($name)) {
	$footer .= elgg_view_field([
		'#type' => 'hidden',
		'name' => 'name',
		'value' => $name,
	]);
}

elgg_set_form_footer($footer);
