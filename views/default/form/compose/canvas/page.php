<?php

$page = elgg_extract('page', $vars);

$sections_result = '';
foreach (elgg_extract('sections', $page, []) as $section) {
	$sections_result .= elgg_view('form/compose/canvas/section', ['section' => $section]);
}

$sections_result .= elgg_format_element('li', [], elgg_view_field([
	'#type' => 'button',
	'class' => 'elgg-button-action forms-compose-add-section',
	'value' => elgg_echo('forms:compose:section:add'),
]));

$page_title = elgg_view_icon('minus-square-o', [
	'class' => 'forms-compose-toggle-element',
]);

$page_title .= elgg_view('output/url', [
	'link' => false,
	'class' => 'float-alt link forms-compose-delete',
	'text' => elgg_view_icon('delete'),
	'title' => elgg_echo('delete'),
]);

$page_title .= elgg_format_element('span', [
	'class' => 'forms-compose-title'
], elgg_extract('title', $page));

$page_title .= elgg_view_icon('edit', [
	'class' => 'forms-compose-edit-title',
]);

$page_body = elgg_format_element('div', ['class' => 'forms-compose-title-container'], $page_title);
$page_body .= elgg_format_element('ul', [], $sections_result);

echo elgg_format_element('li', [
	'class' => 'forms-compose-list-page',
], $page_body);
