<?php

$types = [
	[
		'type' => 'text',
		'label' => elgg_echo('forms:compose:field:type:text'),
	],
	[
		'type' => 'longtext',
		'label' => elgg_echo('forms:compose:field:type:longtext'),
	],
	[
		'type' => 'checkbox',
		'label' => elgg_echo('forms:compose:field:type:checkbox'),
	],
	[
		'type' => 'radio',
		'label' => elgg_echo('forms:compose:field:type:radio'),
	],
	[
		'type' => 'select',
		'label' => elgg_echo('forms:compose:field:type:select'),
	],
];

$list = '';
foreach ($types as $type_params) {
	
	$type_body = elgg_format_element('span', [], elgg_extract('label', $type_params));
			
	$type_body .= elgg_view_icon('edit', [
		'title' => elgg_echo('edit'),
		'class' => 'link forms-compose-field-edit',
	]);
	$type_body .= elgg_view_icon('delete', [
		'title' => elgg_echo('delete'),
		'class' => 'link forms-compose-delete',
	]);
	
	$list .= elgg_format_element('li', [
		'class' => 'forms-compose-list-field',
		'data-params' => json_encode($type_params),
	], $type_body);
}

$body = elgg_format_element('ul', ['class' => 'forms-compose-fields'], $list);

echo elgg_view_module('aside', elgg_view_icon('plus-square-o') . '&nbsp;' . elgg_echo('forms:compose:fields:title'), $body);
