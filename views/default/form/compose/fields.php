<?php
/**
 * Sidebar view for adding fields to a form
 *
 * @uses $vars['entity'] the form entity
 */

/* @var $entity \Form */
$entity = elgg_extract('entity', $vars);

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
	[
		'#type' => 'hidden',
		'#label' => elgg_echo('forms:compose:field:type:hidden'),
	],
];

$list = '';
foreach ($types as $type_params) {
	
	$field_type = elgg_extract('#type', $type_params);
	if ($entity->endpoint === 'csv' && $field_type=== 'file') {
		// file inputs aren't allowed on CSV endpoints
		continue;
	}
	
	$type_body = elgg_format_element('span', [], elgg_extract('#label', $type_params));
	
	$type_body .= elgg_format_element([
		'#tag_name' => 'span',
		'title' => elgg_echo('field:required'),
		'class' => 'elgg-required-indicator',
		'#text' => "&ast;",
	]);
	
	$type_body .= elgg_view_icon('edit', [
		'title' => elgg_echo('edit'),
		'class' => 'forms-compose-field-edit',
	]);
	$type_body .= elgg_view_icon('delete', [
		'title' => elgg_echo('delete'),
		'class' => 'forms-compose-delete',
	]);
	
	if (in_array($field_type, ['select', 'radio'])) {
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
