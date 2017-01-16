<?php

$field = elgg_extract('field', $vars);

$input_vars = $field->getConfig();

$part_of_conditional_section = elgg_extract('part_of_conditional_section', $vars, false);

$field_body = elgg_format_element('span', [], elgg_extract('#label', $input_vars));

$field_body .= elgg_view_icon('edit', [
	'title' => elgg_echo('edit'),
	'class' => 'link forms-compose-field-edit',
]);
$field_body .= elgg_view_icon('delete', [
	'title' => elgg_echo('delete'),
	'class' => 'link forms-compose-delete',
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

echo elgg_format_element('li', [
	'class' => 'forms-compose-list-field',
	'data-params' => json_encode($input_vars),
], $field_body);
