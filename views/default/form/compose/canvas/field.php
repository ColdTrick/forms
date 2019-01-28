<?php

/* @var $field \ColdTrick\Forms\Definition\Field */
$field = elgg_extract('field', $vars);

$input_vars = $field->getConfig();

$part_of_conditional_section = elgg_extract('part_of_conditional_section', $vars, false);

$field_body = elgg_format_element('span', [], elgg_extract('#label', $input_vars));

$field_body .= elgg_format_element([
	'#tag_name' => 'span',
	'title' => elgg_echo('field:required'),
	'class' => 'elgg-required-indicator',
	'#text' => "&ast;",
]);

$field_body .= elgg_view_icon('edit', [
	'title' => elgg_echo('edit'),
	'class' => 'forms-compose-field-edit',
]);
$field_body .= elgg_view_icon('delete', [
	'title' => elgg_echo('delete'),
	'class' => 'forms-compose-delete',
]);

if (in_array($field->getType(), ['select', 'radio'])) {
	$field_body .= elgg_view_icon('indent', [
		'title' => elgg_echo('forms:compose:field:conditional:title'),
		'class' => 'link forms-compose-add-conditional-section',
	]);
}

if (!$part_of_conditional_section) {
	foreach ($field->getConditionalSections() as $conditional_section) {
 		$field_body .= elgg_view('form/compose/canvas/conditional_section', ['conditional_section' => $conditional_section]);
	}
}

$field_class = [
	'forms-compose-list-field',
];
if (elgg_extract('required', $input_vars)) {
	$field_class[] = 'forms-compose-list-field-required';
}

echo elgg_format_element('li', [
	'class' => $field_class,
	'data-params' => json_encode($input_vars),
], $field_body);
