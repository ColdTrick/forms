<?php

$types = [
	[
		'#type' => 'text',
		'#label' => elgg_echo('forms:compose:field:type:text'),
	],
	[
		'#type' => 'email',
		'#label' => elgg_echo('forms:compose:field:type:email'),
	],
	[
		'#type' => 'number',
		'#label' => elgg_echo('forms:compose:field:type:number'),
	],
	[
		'#type' => 'plaintext',
		'#label' => elgg_echo('forms:compose:field:type:plaintext'),
	],
	[
		'#type' => 'longtext',
		'#label' => elgg_echo('forms:compose:field:type:longtext'),
	],
	[
		'#type' => 'checkbox',
		'#label' => elgg_echo('forms:compose:field:type:checkbox'),
	],
	[
		'#type' => 'checkboxes',
		'#label' => elgg_echo('forms:compose:field:type:checkboxes'),
	],
	[
		'#type' => 'radio',
		'#label' => elgg_echo('forms:compose:field:type:radio'),
	],
	[
		'#type' => 'select',
		'#label' => elgg_echo('forms:compose:field:type:select'),
	],
	[
		'#type' => 'file',
		'#label' => elgg_echo('forms:compose:field:type:file'),
	],
	[
		'#type' => 'date',
		'#label' => elgg_echo('forms:compose:field:type:date'),
	],
];

$list = '';
foreach ($types as $type_params) {
	
	$type_body = elgg_format_element('span', [], elgg_extract('#label', $type_params));
			
	$type_body .= elgg_view_icon('edit', [
		'title' => elgg_echo('edit'),
		'class' => 'link forms-compose-field-edit',
	]);
	$type_body .= elgg_view_icon('delete', [
		'title' => elgg_echo('delete'),
		'class' => 'link forms-compose-delete',
	]);
	
	if (in_array(elgg_extract('#type', $type_params), ['select', 'radio'])) {
		$type_body .= elgg_view_icon('indent', [
			'title' => elgg_echo('forms:compose:field:conditional:title'),
			'class' => 'link forms-compose-add-conditional-section',
		]);
	}
	
	$list .= elgg_format_element('li', [
		'class' => 'forms-compose-list-field',
		'data-params' => json_encode($type_params),
	], $type_body);
}

$body = elgg_format_element('ul', ['class' => 'forms-compose-fields'], $list);

echo elgg_view_module('aside', elgg_view_icon('plus-square-o') . '&nbsp;' . elgg_echo('forms:compose:fields:title'), $body);
