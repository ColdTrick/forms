<?php

$conditional_value = elgg_extract('value', $vars);
$fields = elgg_extract('fields', $vars);

$delete_icon = elgg_view_icon('delete', [
	'class' => 'float-alt forms-compose-delete',
	'title' => elgg_echo('delete'),
]);

$output = elgg_view_field([
	'#type' => 'text',
	'#label' => $delete_icon . elgg_echo('forms:compose:conditional_section:value:label'),
	'name' => 'conditional_value',
	'value' => $conditional_value,
]);

$list_items = elgg_format_element('li', ['class' => 'forms-field-unsortable'], elgg_echo('forms:compose:conditional_section:placeholder'));

if ($fields) {
	foreach ($fields as $field) {
		$list_items .= elgg_view('form/compose/canvas/field', ['field' => $field]);
	}
}

$output .= elgg_format_element('ul', [], $list_items);

$params = [
	'class' => ['forms-compose-conditional-section'],
];

if ($fields === null) {
	// assuming template form is requested
	$params['class'][] = 'hidden';
	$params['id'] = 'forms-compose-conditional-section';
}

echo elgg_format_element('div', $params, $output);
