<?php

use ColdTrick\Forms\Definition\Field;

$section = elgg_extract('section', $vars);

$fields_result = '';
foreach (elgg_extract('fields', $section, []) as $field) {
	$field = new Field($field);
	$fields_result .= elgg_view('form/compose/canvas/field', ['field' => $field]);
}

$section_title = elgg_view('output/url', [
	'link' => false,
	'class' => 'float-alt link forms-compose-delete',
	'text' => elgg_view_icon('delete'),
	'title' => elgg_echo('delete'),
]);
$section_title .= elgg_extract('title', $section);

$section_body = elgg_format_element('span', [], $section_title);
$section_body .= elgg_format_element('ul', [], $fields_result);

echo elgg_format_element('li', [
	'class' => 'forms-compose-list-section',
], $section_body);
